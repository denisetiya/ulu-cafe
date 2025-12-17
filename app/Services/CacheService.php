<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Clear all menu-related cache
     */
    public static function clearMenuCache(): void
    {
        Cache::forget('menu_categories');
        Cache::forget('menu_banners');
        Cache::forget('home_lunch_products');
        Cache::forget('home_desserts');
        Cache::forget('home_wok_ingredients');
    }

    /**
     * Clear owner dashboard cache
     */
    public static function clearOwnerCache(): void
    {
        Cache::forget('owner_today_sales_' . today()->format('Y-m-d'));
        Cache::forget('owner_monthly_sales_' . now()->format('Y-m'));
        Cache::forget('owner_best_sellers');
    }
}
