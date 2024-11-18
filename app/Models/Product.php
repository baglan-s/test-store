<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategory;
use App\Models\Specification;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'slug',
        'description',
        'product_category_id',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function specifications()
    {
        return $this->belongsToMany(
            Specification::class, 
            'product_specifications', 
            'product_id', 
            'specification_id'
        );
    }
}
