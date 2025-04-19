<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Display the specified order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('items')
                     ->where('id', $id)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();
        
        return view('orders.show', compact('order'));
    }
} 