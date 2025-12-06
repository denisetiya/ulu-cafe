<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $orders = Order::latest()->take(10)->get();
        return view('admin.index', compact('products', 'orders'));
    }
}
