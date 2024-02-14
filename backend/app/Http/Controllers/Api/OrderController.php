<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
class OrderController extends Controller
{
    public function index()
    {
        // $order = Order::all();
        // return response()->json(['success' => true, 'order' => $order]);
        return OrderResource::collection(
            Order::with(['products', 'user'])->orderBy('id', 'desc')->paginate(8)
        );
    }

    public function store(OrderRequest $request)
    {
        $productIds = $request->input('product_ids');

        if (is_array($productIds) && count($productIds) > 0) {
            $productCounts = array_count_values($productIds);

            $validProductIds = Product::whereIn('id', array_keys($productCounts))->pluck('id')->toArray();

            if (count($validProductIds) == count(array_unique($productIds))) {
                $input = $request->except('product_ids');
                $userId = Auth::id();
                $input['quantity'] = count($productIds);
                $input['user_id'] = $userId;
                $order = Order::create($input);

                foreach ($productCounts as $productId => $quantity) {
                    if (in_array($productId, $validProductIds)) {
                        $order->products()->attach($productId, ['quantity' => $quantity]);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Order added successfully', 'order' => $order]);
            } else {
                return response()->json(['success' => false, 'message' => 'One or more products not found'], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid product IDs'], 400);
        }
    }

    public function show(string $id)
    {
        // $order = Order::find($id);
        $order = Order::with(['products', 'user'])->find($id);
        if ($order) {
            return response()->json(['success' => true, 'message' => 'success', 'order' => $order]);
        }else {
            return response()->json(['success' => false, 'message' => 'Order info not found', 'order' => null]);
        }
    }

    public function confirm(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order->status != 'pending') {
            return response()->json(['success' => false, 'message' => "This order already $order->status", 'order' => $order]);
        }
        if ($order) {
            $order->update(['status' => 'confirmed']);
            return response()->json(['success' => true, 'message' => 'Order has been confirmed', 'order' => $order]);
        }else{
            return response()->json(['success' => false, 'message' => 'Order info not found', 'order' => null]);
        }
    }

    public function cancel(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order->status == 'rejected') {
            return response()->json(['success' => false, 'message' => "This order already $order->status", 'order' => $order]);
        }
        if ($order) {
            $order->update(['status' => 'cancelled']);
            return response()->json(['success' => true, 'message' => 'Order cancelled', 'order' => $order]);
        }else{
            return response()->json(['success' => false, 'message' => 'Order info not found', 'order' => null]);
        }
    }

    public function reject(Request $request, string $id)
    {
        $order = Order::find($id);
        if ($order->status != 'pending') {
            return response()->json(['success' => false, 'message' => "This order already $order->status", 'order' => $order]);
        }
        if ($order) {
            $order->update(['status' => 'rejected']);
            return response()->json(['success' => true, 'message' => 'This order is rejected', 'order' => $order]);
        }else{
            return response()->json(['success' => false, 'message' => 'Order info not found', 'order' => null]);
        }
    }
   
    // No Use
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if ($order) {
            Order::destroy($id);
            return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
        }else {
            return response()->json(['success' => false, 'message' => 'Order info not found', 'order' => null]);
        }
        
    }
}

