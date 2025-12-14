<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('dashboard.products.index', compact('products'));
    }

    public function menu(Request $request)
    {
        $categories = Category::with(['products' => function($query) {
            $query->where('is_active', true);
        }])->get();
        
        $banners = Banner::where('is_active', true)->latest()->get();

        return view('menu.index', compact('categories', 'banners'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'discount_type' => 'nullable|in:fixed,percent',
            'discount_amount' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'discount_type' => $request->discount_type ?? 'fixed',
            'discount_amount' => $request->discount_amount ?? 0,
            'image' => $imagePath,
            'is_active' => true
        ]);

        return redirect()->route('products.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'discount_type' => 'nullable|in:fixed,percent',
            'discount_amount' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'discount_type' => $request->discount_type ?? 'fixed',
            'discount_amount' => $request->discount_amount ?? 0,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists and is not a URL
            if ($product->image && !Str::startsWith($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !Str::startsWith($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Menu berhasil dihapus');
    }
}
