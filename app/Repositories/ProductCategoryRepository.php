<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Support\Str;

class ProductCategoryRepository
{
    public function model(): ProductCategory
    {
        return new ProductCategory();
    }

    public function getById(int $id): ProductCategory | null
    {
        return ProductCategory::find($id);
    }

    public function create(array $attributes): ProductCategory
    {
        if (!isset($attributes['slug'])) {
            $attributes['slug'] = Str::slug($attributes['name']);
        }

        return ProductCategory::create($attributes);
    }

    public function update(int $id, array $attributes): ProductCategory
    {
        if (!$category = $this->getById($id)) {
            throw new EntityNotFoundException('Category not found!');
        }

        if (!isset($attributes['slug']) || $category->name != $attributes['name']) {
            $attributes['slug'] = Str::slug($attributes['name']);
        }

        $category->update($attributes);

        return $this->getById($id);
    }

    public function delete(int $id)
    {
        if (!$category = $this->getById($id)) {
            throw new EntityNotFoundException('Category not found!');
        }

        $category->delete();
    }
}