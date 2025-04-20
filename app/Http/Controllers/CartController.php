<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    
    public function index()
    {
        $userId = Auth::id();
    
        $cartItemsRow = CartItem::where('user_id', $userId)->get();
    
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
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);
    
            $userId = Auth::id();
            $productId = $request->product_id;
            $quantity = $request->quantity;
    
            // Get the product
            $product = Product::findOrFail($productId);
    
            // Check if there's enough stock
            if ($product->quantity < $quantity) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Not enough stock available!'], 422)
                    : redirect()->back()->with('error', 'Not enough stock available!');
            }
    
            // Check if product is already in user's cart
            $cartItem = CartItem::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();
    
            if ($cartItem) {
                // Update quantity (but not beyond available stock)
                $newQuantity = $cartItem->quantity + $quantity;
                if ($newQuantity > $product->quantity) {
                    return $request->expectsJson()
                        ? response()->json(['success' => false, 'message' => 'Quantity exceeds stock!'], 422)
                        : redirect()->back()->with('error', 'Quantity exceeds available stock!');
                }
    
                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                // Create new cart item
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
    
            // Get total items in cart
            $totalItems = CartItem::where('user_id', $userId)->count();
    
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart!',
                    'cart_count' => $totalItems,
                    'product_name' => $product->name,
                ]);
            }
    
            return redirect()->back()->with('success', 'Product added to cart!');
        } catch (\Exception $e) {
            $errorMessage = 'Error adding product to cart: ' . $e->getMessage();
    
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $errorMessage], 500)
                : redirect()->back()->with('error', $errorMessage);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
    
            $userId = Auth::id();
            $quantity = $request->quantity;
    
            // Find the cart item
            $cartItem = CartItem::where('user_id', $userId)
                                ->where('id', $id)
                                ->with('product')
                                ->first();
    
            if (!$cartItem) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Cart item not found!'], 404)
                    : redirect()->route('cart.index')->with('error', 'Cart item not found!');
            }
    
            // Check if there's enough stock
            if ($cartItem->product->quantity < $quantity) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'message' => 'Not enough stock available!'], 422)
                    : redirect()->route('cart.index')->with('error', 'Not enough stock available!');
            }
    
            // Update quantity
            $cartItem->quantity = $quantity;
            $cartItem->save();
    
            // Calculate new subtotal for response
            $newSubtotal = $cartItem->product->price * $quantity;
    
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'new_quantity' => $quantity,
                    'new_subtotal' => $newSubtotal,
                    'new_subtotal_formatted' => '₱' . number_format($newSubtotal, 2)
                ]);
            }
    
            return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
        } catch (\Exception $e) {
            $errorMessage = 'Error updating cart: ' . $e->getMessage();
    
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $errorMessage], 500)
                : redirect()->route('cart.index')->with('error', $errorMessage);
        }
    }
    public function remove($id)
    {
        $userId = Auth::id();

        $cartItem = CartItem::where('user_id', $userId)
                            ->where('id', $id)
                            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }


    public function removeMultiple(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'required|integer'
        ]);

        $userId = Auth::id();

        if (!$userId) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        // Use cart item IDs instead of product IDs
        CartItem::where('user_id', $userId)
                ->whereIn('id', $request->item_ids)
                ->delete();

        return redirect()->route('cart.index')->with('success', 'Selected items removed from cart!');
    }

    



}
