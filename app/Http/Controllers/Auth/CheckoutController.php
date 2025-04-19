<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is now applied at the route level
        // No longer using middleware in constructor for Laravel 11 compatibility
    }

    /**
     * Display the checkout page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cart = Session::get('cart', []);
        
        // Check for selected items
        $selectedItems = $request->has('item_ids') ? $request->item_ids : array_keys($cart);
        
        // If no items selected or cart is empty, redirect
        if (empty($selectedItems) || empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty or no items selected!');
        }
        
        $cartItems = [];
        $total = 0;
        
        // Get product details for each selected cart item
        foreach ($selectedItems as $id) {
            if (isset($cart[$id])) {
                $product = Product::find($id);
                if ($product) {
                    $cartItems[] = [
                        'id' => $id,
                        'product' => $product,
                        'quantity' => $cart[$id]['quantity'],
                        'subtotal' => $product->price * $cart[$id]['quantity']
                    ];
                    $total += $product->price * $cart[$id]['quantity'];
                }
            }
        }
        
        // If no valid items found, redirect
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'No valid items selected!');
        }
        
        return view('checkout.index', compact('cartItems', 'total', 'selectedItems'));
    }
    
    /**
     * Process the checkout form
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'contact_number' => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,bank_transfer',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:products,id'
        ]);

        $cart = Session::get('cart', []);
        $selectedIds = $validatedData['selected_items'];
        
        // Filter cart items based on selected items
        $cartItems = [];
        $totalAmount = 0;
        
        foreach ($selectedIds as $id) {
            if (isset($cart[$id])) {
                $product = Product::findOrFail($id);
                $quantity = $cart[$id]['quantity'];
                
                // Check stock availability
                if ($product->quantity < $quantity) {
                    return redirect()->back()->with('error', "Not enough stock for {$product->name}. Available: {$product->quantity}");
                }
                
                $cartItems[] = [
                    'id' => $id,
                    'quantity' => $quantity,
                    'name' => $product->name,
                    'price' => $product->price,
                    'subtotal' => $quantity * $product->price
                ];
                
                $totalAmount += $quantity * $product->price;
            }
        }
        
        // Check if any items were found
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'No valid items selected for checkout!');
        }
        
        // Prepare order data
        $orderData = [
            'shipping_address' => $validatedData['shipping_address'],
            'shipping_city' => $validatedData['shipping_city'],
            'shipping_zip' => $validatedData['shipping_zip'],
            'contact_number' => $validatedData['contact_number'],
            'payment_method' => $validatedData['payment_method'],
            'total_amount' => $totalAmount
        ];
        
        // Create order using the placeOrder function
        $order = $this->placeOrder($cartItems, $orderData);
        
        // Remove purchased items from cart
        foreach ($selectedIds as $id) {
            unset($cart[$id]);
        }
        Session::put('cart', $cart);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Your order has been placed successfully! View your order details below.');
    }
    
    /**
     * Process direct checkout ("Buy Now" functionality)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function direct(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'contact_number' => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,bank_transfer'
        ]);

        $productId = $validatedData['product_id'];
        $quantity = $validatedData['quantity'];
        
        // Get product details
        $product = Product::findOrFail($productId);
        
        // Check stock availability
        if ($product->quantity < $quantity) {
            return redirect()->back()->with('error', "Not enough stock for {$product->name}. Available: {$product->quantity}");
        }
        
        // Prepare cart item
        $cartItems = [
            [
                'id' => $productId,
                'quantity' => $quantity,
                'name' => $product->name,
                'price' => $product->price,
                'subtotal' => $quantity * $product->price
            ]
        ];
        
        // Calculate total amount
        $totalAmount = $quantity * $product->price;
        
        // Prepare order data
        $orderData = [
            'shipping_address' => $validatedData['shipping_address'],
            'shipping_city' => $validatedData['shipping_city'],
            'shipping_zip' => $validatedData['shipping_zip'],
            'contact_number' => $validatedData['contact_number'],
            'payment_method' => $validatedData['payment_method'],
            'total_amount' => $totalAmount
        ];
        
        // Create order using the placeOrder function
        $order = $this->placeOrder($cartItems, $orderData);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Your order has been placed successfully! View your order details below.');
    }

    /**
     * Place an order in the database
     * 
     * @param array $items Cart items to be ordered
     * @param array $data Order data including shipping info
     * @return \App\Models\Order
     */
    public function placeOrder(array $items, array $data)
    {
        // Debug: Log the data received by placeOrder
        Log::info('Data received by placeOrder:', [
            'items' => $items,
            'data' => $data
        ]);
        
        try {
            // Create the order
            $order = new Order();
            $order->user_id = Auth::id();
            $order->total_amount = $data['total_amount'];
            $order->status = 'pending';
            
            // Map form fields to database columns
            $order->address = $data['shipping_address'];
            $order->shipping_city = $data['shipping_city'];
            $order->shipping_zip = $data['shipping_zip'];
            $order->contact_number = $data['contact_number'];
            
            // These columns might also be needed
            $order->city = $data['shipping_city'];
            $order->postal_code = $data['shipping_zip'];
            $order->phone = $data['contact_number'];
            
            // Get authenticated user data
            $user = Auth::user();
            $order->name = $user->fullname; // Directly access the name property
            $order->email = $user->email; // Directly access the email property
            
            $order->payment_method = $data['payment_method'];
            $order->save();
            
            // Create order items
            foreach ($items as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Create order item
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->product_name = $product->name;
                $orderItem->save();
                
                // Reduce product stock
                $product->quantity -= $item['quantity'];
                $product->save();
            }
            
            // Return the created order
            return $order;
        } catch (\Exception $e) {
            // Log the error
            Log::error('Order placement error: ' . $e->getMessage());
            
            // Re-throw the exception to be handled by the calling method
            throw $e;
        }
    }

    /**
     * Complete a quick purchase (Buy Now) with minimal steps
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function completeQuickPurchase(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must be logged in to complete a purchase'
                    ]);
                }
                return redirect()->route('login')->with('error', 'You must be logged in to complete a purchase');
            }

            // Get the current user - using direct Auth::user() access
            $user = Auth::user();

            // Debug: Log user information
            Log::info('User info in completeQuickPurchase:', [
                'user_id' => $user->id,
                'name' => $user->fullname,
                'email' => $user->email,
                'is_authenticated' => Auth::check()
            ]);
            
            
            // Validate request data
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'shipping_address' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:100',
                'shipping_zip' => 'required|string|max:20',
                'contact_number' => 'required|string|max:20',
                'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal'
            ]);

            // Get product details
            $product = Product::findOrFail($validatedData['product_id']);
            $quantity = $validatedData['quantity'];

            // Check stock availability
            if ($product->quantity < $quantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Not enough stock for {$product->name}. Available: {$product->quantity}"
                    ]);
                }
                return redirect()->back()->with('error', "Not enough stock for {$product->name}. Available: {$product->quantity}");
            }

            // Prepare order data
            $orderData = [
                'name' => $user->fullname,
                'email' => $user->email,
                'shipping_address' => $validatedData['shipping_address'],
                'shipping_city' => $validatedData['shipping_city'],
                'shipping_zip' => $validatedData['shipping_zip'],
                'contact_number' => $validatedData['contact_number'],
                'payment_method' => $validatedData['payment_method'],
                'total_amount' => $quantity * $product->price
            ];

            // Create cart items array
            $cartItems = [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity
                ]
            ];

            // Place the order
            $order = $this->placeOrder($cartItems, $orderData);

            // Return success response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your order has been placed successfully!',
                    'redirect_url' => route('orders.show', $order->id)
                ]);
            }

            // For non-AJAX requests, redirect to order details page
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Your order has been placed successfully! View your order details below.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Order placement error: ' . $e->getMessage());
            
            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ]);
            }
            
            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
} 