<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'opening_stock', 'price'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getStockAttribute()
    {
        // Optional: You can use this to calculate current stock dynamically
        return $this->opening_stock - $this->sales()->sum('quantity');
    }
}

