@extends('layouts.app')
@section('title', 'Order Confirmation | G CLOTHING')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow-sm text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-check text-green-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Order Confirmed!</h1>
                <p class="text-gray-600 mb-6">Your order has been placed successfully.</p>
                
                <div class="bg-gray-50 p-6 rounded-lg w-full mb-6">
                    <div class="flex flex-col space-y-4">
                        <div class="flex justify-between border-b pb-4">
                            <span class="font-medium text-gray-700">Order Number:</span>
                            <span class="text-gray-800">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-4">
                            <span class="font-medium text-gray-700">Date:</span>
                            <span class="text-gray-800">{{ $order->created_at->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-4">
                            <span class="font-medium text-gray-700">Total Amount:</span>
                            <span class="text-gray-800">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-4">
                            <span class="font-medium text-gray-700">Payment Method:</span>
                            <span class="text-gray-800">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="w-full">
                    <h2 class="text-xl font-semibold mb-4 text-left">Order Items</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                    <th class="px-4 py-2 text-sm font-medium text-gray-600">Product</th>
                                    <th class="px-4 py-2 text-sm font-medium text-gray-600">Quantity</th>
                                    <th class="px-4 py-2 text-sm font-medium text-gray-600">Price</th>
                                    <th class="px-4 py-2 text-sm font-medium text-gray-600">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 flex-shrink-0 mr-3 bg-gray-100 rounded-md overflow-hidden">
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
                                            <span class="text-gray-800">{{ $item->product ? $item->product->name : 'Product removed' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-800">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-gray-800">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="mt-8 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Continue Shopping
                    </a>
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                        View All Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 