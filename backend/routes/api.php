<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaptopController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['checkToken']], function () {
    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout',[AuthController::class,'logout']);
        Route::delete('/products/{productId}/images/{imageId}', [ProductController::class, 'deleteImage']);

        // user
        Route::apiResource('/users', UserController::class);
    
        // product
        Route::post('/products', [ProductController::class, 'store']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/products/{id}', [ProductController::class, 'update']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
    
        // Order
        // Route::post('/orders',[OrderController::class, 'store']);
        Route::get('/orders',[OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::put('/orders/{id}/confirm', [OrderController::class, 'confirm']);
        Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
        Route::put('/orders/{id}/reject', [OrderController::class, 'reject']);
        Route::delete('/orders/{id}',[OrderController::class, 'destroy']);
        Route::post('/orders',[OrderController::class, 'store']);

    });
});




//auth
Route::post('/signup',[AuthController::class,'signup']);
Route::post('/login',[AuthController::class,'login']);

// user
// Route::apiResource('/users', UserController::class);

// // product
// Route::post('/products', [ProductController::class, 'store']);
// Route::delete('/products/{id}', [ProductController::class, 'destroy']);
// Route::post('/products/{id}', [ProductController::class, 'update']);
// Route::get('/products', [ProductController::class, 'index']);
// Route::get('/products/{id}', [ProductController::class, 'show']);

// laptop
Route::post('/laptops',[LaptopController::class, 'store']);
Route::get('/laptops',[LaptopController::class, 'index']);
Route::get('/laptops/{id}', [LaptopController::class, 'show']);
Route::post('/laptops/{id}', [LaptopController::class, 'update']);
Route::post('/update-image', [LaptopController::class, 'updateImageOrder']);
Route::delete('/laptops/{id}',[LaptopController::class, 'destroy']);
Route::delete('/laptops/{laptopId}/images/{imageId}', [LaptopController::class, 'deleteLaptopImage']);

// // Order
// Route::post('/orders',[OrderController::class, 'store']);
// Route::get('/orders',[OrderController::class, 'index']);
// Route::get('/orders/{id}', [OrderController::class, 'show']);
// Route::put('/orders/{id}/confirm', [OrderController::class, 'confirm']);
// Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
// Route::put('/orders/{id}/reject', [OrderController::class, 'reject']);
// Route::delete('/orders/{id}',[OrderController::class, 'destroy']);
