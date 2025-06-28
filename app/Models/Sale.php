<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'discount',
        'vat',
        'total',
        'paid_amount'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function getDueAmountAttribute()
    {
        return $this->total - $this->paid_amount;
    }
}

