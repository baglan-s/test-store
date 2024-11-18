<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => OrderItemResource::collection($this->items),
            'user' => $this->user ? new UserResource($this->user): null,
        ];
    }
}
