@extends('layouts.app')
@section('title', 'My Orders | G CLOTHING')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">My Orders</h1>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    @if(count($orders) > 0)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="py-4 px-6">Order #</th>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6">Total</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium">{{ $order->order_number }}</td>
                        <td class="py-4 px-6">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="py-4 px-6">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-4 px-6">
                            @if($order->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            @elseif($order->status == 'processing')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Processing
                            </span>
                            @elseif($order->status == 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                            @elseif($order->status == 'cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelled
                            </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4">
            {{ $orders->links() }}
        </div>
    </div>
    @else
    <div class="bg-white p-8 rounded-lg shadow-sm text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-box text-indigo-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">No Orders Yet</h2>
            <p class="text-gray-600 mb-6">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                Start Shopping <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection 