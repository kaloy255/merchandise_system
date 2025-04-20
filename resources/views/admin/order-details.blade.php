@extends('layouts.admin')

@section('title', 'Order Details')

@section('header', 'Order Details')

@section('content')
<div class="fade-in">
    <div class="mb-6">
        <a href="{{ route('admin.orders') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-600">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="mr-2 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="mt-4">
            @php
                $statusClass = 'bg-yellow-100 text-yellow-800';
                
                if ($order->status == 'processing') {
                    $statusClass = 'bg-blue-100 text-blue-800';
                } elseif ($order->status == 'completed') {
                    $statusClass = 'bg-green-100 text-green-800';
                } elseif ($order->status == 'cancelled') {
                    $statusClass = 'bg-red-100 text-red-800';
                }
            @endphp
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Information and Shipping Details -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Customer Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-sm font-medium">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm font-medium">{{ $order->user->email }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Shipping Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-sm font-medium">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm font-medium">{{ $order->shipping_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-sm font-medium">{{ $order->shipping_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-sm font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">City</p>
                        <p class="text-sm font-medium">{{ $order->shipping_city }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Postal Code</p>
                        <p class="text-sm font-medium">{{ $order->shipping_postal_code }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Items and Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold">Order Items</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product)
                                        <img class="h-10 w-10 flex-shrink-0 rounded mr-3" 
                                            src="{{ asset('storage/' . $item->product->image) }}" 
                                            alt="{{ $item->product->name }}">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? 'SKU-' . $item->product->id }}</div>
                                        </div>
                                        @else
                                        <div class="text-sm text-gray-500">Product no longer available</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱{{ number_format($item->price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱{{ number_format($item->subtotal, 2) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Payment Method</span>
                        <span class="text-sm font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Subtotal</span>
                        <span class="text-sm font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Shipping</span>
                        <span class="text-sm font-medium">₱0.00</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-gray-200">
                        <span class="text-base font-semibold">Total</span>
                        <span class="text-base font-semibold">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 