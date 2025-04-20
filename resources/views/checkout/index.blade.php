@extends('layouts.app')
@section('title', 'Checkout | G CLOTHING')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-8">Checkout</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-6">Shipping Information</h2>
                        
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            
                            <!-- Hidden fields for selected item IDs -->
                            @if(isset($selectedItems) && count($selectedItems) > 0)
                                @foreach($selectedItems as $itemId)
                                    <input type="hidden" name="item_ids[]" value="{{ $itemId }}">
                                @endforeach
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ auth()->user()->name ?? old('name') }}" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    >
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ auth()->user()->email ?? old('email') }}" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    >
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address" 
                                    value="{{ old('address') }}" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                >
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input 
                                        type="text" 
                                        id="city" 
                                        name="city" 
                                        value="{{ old('city') }}" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    >
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                    <input 
                                        type="text" 
                                        id="postal_code" 
                                        name="postal_code" 
                                        value="{{ old('postal_code') }}" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    >
                                    @error('postal_code')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required
                                    >
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-lg font-medium mb-4">Payment Method</h3>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input 
                                            type="radio" 
                                            id="cod" 
                                            name="payment_method" 
                                            value="cod" 
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                            checked
                                        >
                                        <label for="cod" class="ml-3 block text-sm font-medium text-gray-700">
                                            Cash on Delivery
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input 
                                            type="radio" 
                                            id="gcash" 
                                            name="payment_method" 
                                            value="gcash" 
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                        >
                                        <label for="gcash" class="ml-3 block text-sm font-medium text-gray-700">
                                            GCash
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input 
                                            type="radio" 
                                            id="card" 
                                            name="payment_method" 
                                            value="card" 
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                        >
                                        <label for="card" class="ml-3 block text-sm font-medium text-gray-700">
                                            Credit/Debit Card
                                        </label>
                                    </div>
                                </div>
                                
                                @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex flex-col space-y-4">
                                <button type="submit" class="bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 transition-colors text-center">
                                    Place Order
                                </button>
                                
                                <a href="{{ route('cart.index') }}" class="text-indigo-600 hover:text-indigo-800 text-center">
                                    <i class="fa-solid fa-arrow-left mr-1"></i> Return to Cart
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6 sticky top-20">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                        
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            @foreach($cartItems as $item)
                                <div class="flex items-center justify-between py-2">
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600">{{ $item['quantity'] }} ×</div>
                                        <div class="ml-2 text-gray-800">{{ $item['product']->name }}</div>
                                    </div>
                                    <div class="font-medium text-gray-800">₱{{ number_format($item['subtotal'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-lg font-bold text-gray-900 pt-4 border-t border-gray-200">
                            <span>Total</span>
                            <span>₱{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 