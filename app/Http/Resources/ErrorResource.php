<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }

    public function withResponse($request, $response)
    {
        $code = $this->getCode();
        $response->setStatusCode(  $code != 0 && strlen((string)$code) <= 3 ? $code : 500, '');
    }
}
