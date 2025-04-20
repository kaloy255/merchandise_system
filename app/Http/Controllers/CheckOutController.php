<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckOutController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Get selected items from query params
        $selectedItems = $request->input('selected_items', []);
        
        // Get cart items (filter by selected_items if provided)
        $cartItemsQuery = CartItem::where('user_id', $userId)
            ->with('product');
            
        if (!empty($selectedItems)) {
            $cartItemsQuery->whereIn('id', $selectedItems);
        }
        
        $cartItemsRow = $cartItemsQuery->get();
        
        // If no items are found, redirect back to cart
        if ($cartItemsRow->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'No items selected for checkout');
        }
        
        // Process items for display
        $cartItems = [];
        $total = 0;
        
        foreach ($cartItemsRow as $item) {
            $product = $item->product;
            
            if ($product) {
                $subtotal = $product->price * $item->quantity;
                
                $cartItems[] = [
                    'id' => $item->id,
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal
                ];
                
                $total += $subtotal;
            }
        }
        
        return view('checkout.index', compact('cartItems', 'total', 'selectedItems'));
    }
    
    public function process(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cod,gcash,card',
            'item_ids' => 'sometimes|array',
            'item_ids.*' => 'integer'
        ]);
        
        $userId = Auth::id();
        $selectedItemIds = $request->input('item_ids', []);
        
        // Get cart items (filter by selected_items if provided)
        $cartItemsQuery = CartItem::where('user_id', $userId)
            ->with('product');
            
        if (!empty($selectedItemIds)) {
            $cartItemsQuery->whereIn('id', $selectedItemIds);
        }
        
        $cartItems = $cartItemsQuery->get();
        
        // If no items found, redirect back
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'No items found for checkout');
        }
        
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }
        
        // Begin database transaction
        DB::beginTransaction();
        
        try {
            // Create order
            $order = new Order();
            $order->user_id = $userId;
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->shipping_name = $request->name;
            $order->shipping_email = $request->email;
            $order->shipping_address = $request->address;
            $order->shipping_city = $request->city;
            $order->shipping_postal_code = $request->postal_code;
            $order->shipping_phone = $request->phone;
            $order->save();
            
            // Create order items and update product stock
            foreach ($cartItems as $item) {
                $product = $item->product;
                
                // Check stock availability again
                if ($product->quantity < $item->quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                // Create order item
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $product->price;
                $orderItem->subtotal = $product->price * $item->quantity;
                $orderItem->save();
                
                // Reduce product stock
                $product->quantity -= $item->quantity;
                $product->save();
                
                // Remove from cart
                $item->delete();
            }
            
            // Commit transaction
            DB::commit();
            
            // Create notification for admin
            NotificationController::createOrderNotification($order);
            
            // Redirect to thank you page or order confirmation page
            return redirect()->route('orders.confirmation', $order->id)
                ->with('success', 'Order placed successfully!');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error processing your order: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Process a quick checkout (buy now) request for a single product
     */
    public function quickCheckout(Request $request)
    {
        // Validate request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'contact_number' => 'required|string|max:20',
            'payment_method' => 'required|string'
        ]);
        
        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->quantity;
        
        // Get the product
        $product = Product::findOrFail($productId);
        
        // Check stock availability again
        if ($product->quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available for this product.'
            ], 422);
        }
        
        // Calculate total
        $total = $product->price * $quantity;
        
        // Begin database transaction
        DB::beginTransaction();
        
        try {
            // Create order
            $order = new Order();
            $order->user_id = $userId;
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->shipping_name = Auth::user()->name;
            $order->shipping_email = Auth::user()->email;
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city;
            $order->shipping_postal_code = $request->shipping_zip;
            $order->shipping_phone = $request->contact_number;
            $order->save();
            
            // Create order item
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $quantity;
            $orderItem->price = $product->price;
            $orderItem->subtotal = $product->price * $quantity;
            $orderItem->save();
            
            // Reduce product stock
            $product->quantity -= $quantity;
            $product->save();
            
            // Commit transaction
            DB::commit();
            
            // Create notification for admin
            NotificationController::createOrderNotification($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect_url' => route('orders.confirmation', $order->id)
            ]);
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing your order: ' . $e->getMessage()
            ], 500);
        }
    }
}
