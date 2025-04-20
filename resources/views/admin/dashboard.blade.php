@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    Dashboard Overview
@endsection

@section('content')
    <div class="p-1 md:p-4">
        <!-- Welcome Message -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 md:p-6 mb-4 md:mb-6">
            <div class="text-gray-900">
                Welcome to admin dashboard!
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 md:gap-4 mb-4 md:mb-6">
            <!-- Total Sales Card -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                        <!-- SVG remains same -->
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Sales</p>
                        <p class="text-lg md:text-2xl font-bold">₱{{ number_format($totalSales, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                        <!-- SVG remains same -->
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Orders</p>
                        <p class="text-lg md:text-2xl font-bold">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                        <!-- SVG remains same -->
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Products</p>
                        <p class="text-lg md:text-2xl font-bold">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                        <!-- SVG remains same -->
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Customers</p>
                        <p class="text-lg md:text-2xl font-bold">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
            <!-- Sales Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-base md:text-lg font-semibold mb-4">Monthly Sales</h2>
                <div class="h-48 md:h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Category Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-base md:text-lg font-semibold mb-4">Revenue by Category</h2>
                <div class="h-48 md:h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders and Low Stock Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-6">
            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-base md:text-lg font-semibold mb-4">Recent Orders</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Customer</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden sm:table-cell">{{ $order->user->name }}</td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-2 md:px-6 py-2 md:py-4 text-center text-xs md:text-sm text-gray-500">
                                    No recent orders
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
                <h2 class="text-base md:text-lg font-semibold mb-4">Low Stock Products</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Category</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-2 md:px-6 py-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden sm:table-cell">{{ $product->category }}</td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td class="px-2 md:px-6 py-2 md:py-4 whitespace-nowrap text-xs md:text-sm text-gray-500 hidden sm:table-cell">₱{{ number_format($product->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-2 md:px-6 py-2 md:py-4 text-center text-xs md:text-sm text-gray-500">
                                    No low stock products
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
            <h2 class="text-base md:text-lg font-semibold mb-4">Recent Activity</h2>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($recentOrders as $order)
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-green-500">
                                        <i class="fas fa-shopping-cart text-white"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-2 md:space-x-4">
                                    <div>
                                        <p class="text-xs md:text-sm text-gray-500">
                                            <span class="font-medium text-gray-900">New Order</span>
                                            <span class="hidden sm:inline"> - {{ $order->order_number }} by {{ $order->user->name }}</span>
                                            <span class="inline sm:hidden"> - {{ $order->order_number }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right text-xs md:text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $order->created_at->format('Y-m-d') }}">{{ $order->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-gray-400">
                                        <i class="fas fa-info text-white"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-xs md:text-sm text-gray-500">
                                            No recent activity
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Responsive font sizes
        Chart.defaults.font.size = window.innerWidth < 768 ? 10 : 12;
        
        // Monthly Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Monthly Sales',
                    data: [12000, 19000, 3000, 5000, 2000, 30000, 45000, 35000, 28000, 42000, 38000, 49000],
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: window.innerWidth >= 768
                    }
                }
            }
        });

        // Category Revenue Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['T-Shirts', 'Hoodies', 'Pants', 'Shoes', 'Accessories'],
                datasets: [{
                    data: [45000, 35000, 28000, 15000, 8000],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(107, 114, 128, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: window.innerWidth < 768 ? 10 : 20
                        }
                    }
                }
            }
        });

        // Handle window resize for responsive charts
        window.addEventListener('resize', function() {
            Chart.defaults.font.size = window.innerWidth < 768 ? 10 : 12;
        });
    });
</script>
@endsection