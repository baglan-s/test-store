<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $specs = $this->specifications->map(function ($spec) {
            $spec->productValues = $spec->productValues->filter(function ($value) {
                return $value->pivot->product_id == $this->id;
            })->all();

            return $spec;
        });
        
        return [
            'id' => $this->id,
            'product_category_id' => $this->product_category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'category' => new ProductCategoryResource($this->whenLoaded('category', $this->category)),
            'specifications' => ProductSpecificationResource::collection($specs),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
