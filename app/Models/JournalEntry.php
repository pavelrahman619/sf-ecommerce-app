<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'type', 'amount'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
