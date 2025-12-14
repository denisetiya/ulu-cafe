<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount_amount <= 0) {
            return $this->price;
        }

        if ($this->discount_type === 'percent') {
            $discount = ($this->price * $this->discount_amount) / 100;
            return max(0, $this->price - $discount);
        }

        return max(0, $this->price - $this->discount_amount);
    }
}
