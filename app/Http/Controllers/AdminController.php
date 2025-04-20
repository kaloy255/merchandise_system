<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $lowStockProducts = Product::where('quantity', '<=', 50)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalSales',
            'recentOrders',
            'lowStockProducts'
        ));
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.products', compact('products'));
    }
    
    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders', compact('orders'));
    }
    
    public function orderShow($id)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($id);
        return view('admin.order-details', compact('order'));
    }
    
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()->with('success', 'Order status updated successfully');
    }
    
    public function users()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }
}
