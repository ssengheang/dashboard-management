<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['note', 'quantity', 'status', 'user_id'];
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order') ->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Calculate and set total_price before creating a new order
        static::creating(function ($order) {
            $totalPrice = 0;
            $order->load('products');
            foreach ($order->products as $product) {
                $totalPrice += $product->price;
            }
            $order->total_price = $totalPrice;
        });
    }
}
