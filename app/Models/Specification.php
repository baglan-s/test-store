<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SpecificationValue;

class Specification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    public function values()
    {
        return $this->hasMany(SpecificationValue::class);
    }

    public function productValues()
    {
        return $this->belongsToMany(
            SpecificationValue::class, 
            'product_specifications', 
            'specification_id', 
            'specification_value_id'
        )->withPivot('product_id');
    }
}
