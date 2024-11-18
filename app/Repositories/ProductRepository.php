<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Contracts\Database\Query\Builder;

class ProductRepository
{
    public function model(): Product
    {
        return new Product();
    }

    public function getById(int $id): Product | null
    {
        return Product::find($id);
    }

    public function create(array $attributes): Product
    {
        if (!isset($attributes['slug'])) {
            $attributes['slug'] = Str::slug($attributes['name']);
        }

        $specifications = $attributes['specifications'] ?? [];
        unset($attributes['specifications']);

        $product = Product::create($attributes);

        if (!empty($specifications)) {
            foreach ($specifications as $specification) {
                $product->specifications()->attach($specification['specification_id'], ['specification_value_id' => $specification['specification_value_id']]);
            }
        }

        return $product;
    }

    public function update(int $id, array $attributes): Product
    {
        if (!$product = $this->getById($id)) {
            throw new EntityNotFoundException('Product not found!');
        }

        if (!isset($attributes['slug']) || $product->name != $attributes['name']) {
            $attributes['slug'] = Str::slug($attributes['name']);
        }

        $product->update($attributes);

        if (isset($attributes['specifications']) &&!empty($attributes['specifications'])) {
            $product->specifications()->detach();

            foreach ($attributes['specifications'] as $specification) {
                $product->specifications()->attach($specification['specification_id'], ['specification_value_id' => $specification['specification_value_id']]);
            }
        }

        return $this->model()
            ->with([
                'category', 
                'specifications', 
            ])
            ->where('id', $id)
            ->first();
    }

    public function delete(int $id)
    {
        if (!$product = $this->getById($id)) {
            throw new EntityNotFoundException('Product not found!');
        }

        $product->delete();
    }

    public function filter(array $filters): Builder
    {
        return $this->model()
            ->with([
                'category',
                'specifications',
            ])
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->whereRaw("lower(name) LIKE '%". mb_strtolower($filters['search']). "%'");
            })
            ->when(isset($filters['specs']), function ($query) use ($filters) {
                $specs = explode(',', $filters['specs']);
                $specIds = [];
                $specValueIds = [];

                foreach ($specs as $spec) {
                    $specParts = explode('-', $spec);
                    $specIds[] = $specParts[0];
                    $specValueIds = array_merge($specValueIds, explode(';', $specParts[1]));
                }

                $query->whereHas('specifications', function ($query) use ($specIds, $specValueIds) {
                    $query->whereHas('productValues', function ($query) use ($specValueIds) {
                        $query->whereIn('specification_values.id', $specValueIds)->whereColumn('product_specifications.product_id', 'products.id');
                    })->whereIn('specifications.id', $specIds);
                });
            })
            ->when(isset($filters['product_category_id']), function ($query) use ($filters) {
                $query->whereIn('product_category_id', array_map(fn ($category) => trim($category), explode(',', $filters['product_category_id'])));
            });
    }
}