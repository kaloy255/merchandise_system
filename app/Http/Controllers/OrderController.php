<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the authenticated user.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Display the order confirmation page.
     */
    public function confirmation($id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('orders.confirmation', compact('order'));
    }
    
    /**
     * Display the specified order details.
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('orders.show', compact('order'));
    }
}
