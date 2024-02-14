<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use json_encode;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('images')->orderBy('id', 'desc')->paginate(8);
    
        return response()->json(['products' => $products]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'brand' => 'required|string',
            'processor' => 'required|string',
            'ram' => 'required|string',
            'storage' => 'required|string',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Create the product
        $product = Product::create($request->only(['name', 'price', 'brand', 'processor', 'ram', 'storage']));
        if ($request->has('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $imageFile->store('public/product_images');
                
                $imagePath = str_replace('public/', '', $imagePath);
    
                $image = new ProductImage(['image_path' => $imagePath]);
                $product->images()->save($image);
            }
        }
    
        $product->load('images');
        $jsonMessage  = json_encode([
            'create_event' => [
                'product_name' => $product->name,
                'price' => $product->price,
                'brand' => $product->brand,
                'processor' => $product->processor,
                'ram' => $product->ram,
                'storage' => $product->storage,
            ],
        ], JSON_PRETTY_PRINT);
        
        $telegramMessage = "```\n" . $jsonMessage . "\n```";
        $this->sendTelegramAlert($telegramMessage);

        return response()->json(['success' => true, 'message' => 'Product and images created successfully', 'product' => $product]);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('images')->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found', 'product' => $product], 404);
        }

        return response()->json(['success' => true, 'message' => 'success', 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::with('images')->find($id);
    
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
    
        $request->validate([
            'name' => 'string',
            'price' => 'numeric',
            'brand' => 'string',
            'processor' => 'string',
            'ram' => 'string',
            'storage' => 'string',
            'new_image_path' => 'array',
            'new_image_path.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $product->update($request->only(['name', 'price', 'brand', 'processor', 'ram', 'storage']));

        if ($request->has('new_image_path')) {
            foreach ($request->file('new_image_path') as $imageFile) {
                $imagePath = $imageFile->store('public/product_images');
                $imagePath = str_replace('public/', '', $imagePath);
                $image = new ProductImage(['image_path' => $imagePath]);
                $product->images()->save($image);
            }
        }
        
        $product->load('images');

        $jsonMessage  = json_encode([
            'update_event' => [
                'product_id' => $id,
                'product_name' => $product->name,
                'price' => $product->price,
                'brand' => $product->brand,
                'processor' => $product->processor,
                'ram' => $product->ram,
                'storage' => $product->storage,
            ],
        ], JSON_PRETTY_PRINT);
        
        $telegramMessage = "```\n" . $jsonMessage . "\n```";
        $this->sendTelegramAlert($telegramMessage);
    
        return response()->json(['success' => true, 'message' => 'Product and image updated successfully', 'product' => $product]);
    }

    
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        // Delete associated images
        $product->images()->delete();
        
        // Delete the product
        $product->delete();
        $jsonMessage  = json_encode([
            'delete_product_event' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'brand' => $product->brand,
            ],
        ], JSON_PRETTY_PRINT);

        $telegramMessage = "```\n" . $jsonMessage . "\n```";
        $this->sendTelegramAlert($telegramMessage);
        return response()->json(['success' => true, 'message' => 'Product and associated images deleted successfully']);
    }
    
    public function deleteImage($productId, $imageId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        $image = $product->images()->find($imageId);
        
        if (!$image) {
            return response()->json(['success' => false, 'message' => 'Image not found for the product'], 404);
        }
        // Delete the image
        $image->delete();
        $jsonMessage  = json_encode([
            'delete_image_event' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'image' => [
                    'image_id' => $image->id,
                    'image_path' => $image->image_path
                ]
            ],
        ], JSON_PRETTY_PRINT);

        $telegramMessage = "```\n" . $jsonMessage . "\n```";
        $this->sendTelegramAlert($telegramMessage);
        
        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    private function sendTelegramAlert($message)
    {
        $apiToken = '6805332123:AAEdZs67l2iaXRdR1Pftv2B3OUZgTuv5a6U';
        $chatId = '-1002052767931';

        $telegramEndpoint = "https://api.telegram.org/bot{$apiToken}/sendMessage";
        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];
    
        $client = new \GuzzleHttp\Client();
        $client->request('POST', $telegramEndpoint, ['form_params' => $params]);
    }
}
