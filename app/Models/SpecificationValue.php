<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specification;

class SpecificationValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specification_id',
        'created_at',
        'updated_at',
    ];

    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }
}
