<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = [];
        $total = 0;

        if (Auth::check()) {
            $dbCart = CartItem::with('product')->where('user_id', Auth::id())->get();
            foreach ($dbCart as $item) {
                $cart[$item->product_id] = [
                    "id" => $item->product_id,
                    "name" => $item->product->name,
                    "quantity" => $item->quantity,
                    "price" => $item->product->price,
                    "image" => $item->product->image
                ];
                $total += $item->product->price * $item->quantity;
            }
        } else {
            $cart = Session::get('cart', []);
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity++;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity']++;
            } else {
                $cart[$product->id] = [
                    "id" => $product->id,
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image
                ];
            }
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->update(['quantity' => $request->quantity]);
            } else {
                $cart = Session::get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                Session::put('cart', $cart);
            }
            session()->flash('success', 'Keranjang berhasil diperbarui!');
        }
        return redirect()->route('cart.index');
    }

    public function removeFromCart(Request $request)
    {
        if($request->id) {
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())
                    ->where('product_id', $request->id)
                    ->delete();
            } else {
                $cart = Session::get('cart');
                if(isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    Session::put('cart', $cart);
                }
            }
            session()->flash('success', 'Produk berhasil dihapus dari keranjang!');
        }
        return redirect()->route('cart.index');
    }
}
