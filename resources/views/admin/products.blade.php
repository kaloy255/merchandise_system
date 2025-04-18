@extends('layouts.admin')

@section('title', 'Manage Products')

@section('header', 'Product Management')

@section('content')
<div class="fade-in">
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Products</h1>
            <p class="text-gray-500 mt-1">Manage your store products and inventory</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center justify-center shadow-sm">
                <i class="fas fa-plus mr-2"></i>
                <span>Add Product</span>
            </a>
            <button class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 border border-gray-300 rounded-lg flex items-center justify-center shadow-sm">
                <i class="fas fa-filter mr-2"></i>
                <span>Filter</span>
            </button>
            <button class="bg-white hover:bg-gray-100 text-gray-800 px-4 py-2 border border-gray-300 rounded-lg flex items-center justify-center shadow-sm">
                <i class="fas fa-download mr-2"></i>
                <span>Export</span>
            </button>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-5">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search products...">
                </div>
                <div class="sm:w-48">
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Categories</option>
                        <option value="t-shirts">T-Shirts</option>
                        <option value="jeans">Jeans</option>
                        <option value="hoodies">Hoodies</option>
                        <option value="shoes">Shoes</option>
                    </select>
                </div>
                <div class="sm:w-48">
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="in-stock">In Stock</option>
                        <option value="low-stock">Low Stock</option>
                        <option value="out-of-stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            SKU
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($products) && count($products) > 0)
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img 
                                    class="h-10 w-10 flex-shrink-0 rounded mr-3" 
                                    src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}"
                                    >
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->sku ?? 'SKU-' . $product->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->category ?? 'Uncategorized' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₱{{ number_format($product->price, 2) }}</div>
                                @if(isset($product->original_price) && $product->original_price > $product->price)
                                <div class="text-xs text-gray-500 line-through">₱{{ number_format($product->original_price, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"> {{ $product->quantity ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = $product->quantity ?? 10; // Default for demo
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'In Stock';
                                    
                                    if ($status <= 0) {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Out of Stock';
                                    } elseif ($status <= 5) {
                                        $statusClass = 'bg-amber-100 text-amber-800';
                                        $statusText = 'Low Stock';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('products.find', $product->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-box text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No products found</p>
                                    <p class="text-sm">Start by adding a new product to your store</p>
                                    <a href="/admin/products/create" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            @if(isset($products) && count($products) > 0)
                {{ $products->links() }}
            @else
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">0</span> to <span class="font-medium">0</span> of <span class="font-medium">0</span> results
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 