<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;

class Cart extends Model
{
    use HasFactory;

    protected $with = ['items'];

    protected $fillable = [
        'total_quantity',
        'total_price',
        'created_at',
        'updated_at'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
