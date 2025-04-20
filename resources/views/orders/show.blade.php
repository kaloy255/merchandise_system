@extends('layouts.app')
@section('title', 'Order Details | G CLOTHING')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                            <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 text-left">
                                    <tr>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-600">Product</th>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-600">Price</th>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-600">Quantity</th>
                                        <th class="px-4 py-2 text-sm font-medium text-gray-600">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 flex-shrink-0 mr-4 bg-gray-100 rounded-md overflow-hidden">
                                                    @if($item->product && $item->product->image)
                                                    <img
                                                        src="{{ asset('storage/' . $item->product->image) }}"
                                                        alt="{{ $item->product ? $item->product->name : 'Product' }}"
                                                        class="w-full h-full object-cover">
                                                    @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                        <i class="fa-solid fa-shirt text-gray-400"></i>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $item->product ? $item->product->name : 'Product removed' }}</p>
                                                    @if($item->product)
                                                    <p class="text-sm text-gray-600">{{ ucfirst($item->product->category) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-gray-800">₱{{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-4 text-gray-800">{{ $item->quantity }}</td>
                                        <td class="px-4 py-4 font-medium text-gray-800">₱{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium">Subtotal:</td>
                                        <td class="px-4 py-3 font-medium">₱{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-medium">Shipping:</td>
                                        <td class="px-4 py-3 font-medium">Free</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">Total:</td>
                                        <td class="px-4 py-3 font-bold text-lg">₱{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping and Payment Info -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Shipping Information</h2>
                    
                    <div class="space-y-3 mb-6">
                        <p class="font-medium text-gray-800">{{ $order->shipping_name }}</p>
                        <p class="text-gray-700">{{ $order->shipping_address }}</p>
                        <p class="text-gray-700">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                        <p class="text-gray-700">Phone: {{ $order->shipping_phone }}</p>
                        <p class="text-gray-700">Email: {{ $order->shipping_email }}</p>
                    </div>
                    
                    <div class="border-t pt-4">
                        <h2 class="text-lg font-semibold mb-4">Payment Method</h2>
                        <p class="font-medium text-gray-800">
                            @if($order->payment_method == 'cod')
                                Cash on Delivery
                            @elseif($order->payment_method == 'gcash')
                                GCash
                            @elseif($order->payment_method == 'card')
                                Credit/Debit Card
                            @else
                                {{ ucfirst($order->payment_method) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 