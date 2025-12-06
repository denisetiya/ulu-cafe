<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $lunchProducts = Product::whereHas('category', fn($q) => $q->where('slug', 'business-lunch'))->take(4)->get();
        $desserts = Product::whereHas('category', fn($q) => $q->where('slug', 'dessert'))->take(4)->get();
        
        // Group ingredients by type for the WOK builder
        $wokIngredients = Ingredient::all()->groupBy('type');
        
        return view('home', compact('lunchProducts', 'desserts', 'wokIngredients'));
    }
}
