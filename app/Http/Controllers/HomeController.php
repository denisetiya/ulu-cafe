<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache lunch products for 1 hour
        $lunchProducts = Cache::remember('home_lunch_products', 3600, function() {
            return Product::with('category')
                ->whereHas('category', fn($q) => $q->where('slug', 'business-lunch'))
                ->take(4)
                ->get();
        });

        // Cache desserts for 1 hour
        $desserts = Cache::remember('home_desserts', 3600, function() {
            return Product::with('category')
                ->whereHas('category', fn($q) => $q->where('slug', 'dessert'))
                ->take(4)
                ->get();
        });
        
        // Cache WOK ingredients for 1 hour
        $wokIngredients = Cache::remember('home_wok_ingredients', 3600, function() {
            return Ingredient::all()->groupBy('type');
        });
        
        return view('home', compact('lunchProducts', 'desserts', 'wokIngredients'));
    }
}
