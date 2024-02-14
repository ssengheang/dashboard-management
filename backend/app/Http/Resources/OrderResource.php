<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// use App\Http\Resources\ProductResource;

class OrderResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this-> id,
            'user_id' => $this-> user_id,
            'status' => $this-> status,
            'total_quantity' => $this-> quantity,
            'note' => $this-> note,
            'total_price' => $this-> total_price,
            'created_at' => $this-> created_at->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->whenLoaded('user')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
