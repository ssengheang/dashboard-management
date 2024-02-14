<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { $array = [
        'id' => $this->id,
        'name' => $this->name,
        'price' => $this->price,
        'brand' => $this->brand,
        'processor' => $this->processor,
        'ram' => $this->ram,
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        'storage' => $this->storage,
    ];

    // Include 'quantity' if this product is loaded within an order context and thus has pivot data
    if (isset($this->pivot)) {
        $array['quantity'] = $this->pivot->quantity;
    }

    return $array;
    }
}