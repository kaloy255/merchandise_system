<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | G CLOTHING</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="flex h-screen bg-gray-100 overflow-hidden">
    <!-- Sidebar -->
    <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 z-30">
        <div class="flex items-center justify-between px-4">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-bold">G<span class="text-indigo-400">ADMIN</span></span>
            </div>
        </div>
        
        <nav class="mt-10 px-4">
            <a href="/dashboard" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/dashboard')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('products.create') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/products*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-tshirt mr-2"></i>Products
            </a>
            <a href="/admin/orders" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/orders*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-shopping-cart mr-2"></i>Orders
            </a>
            <a href="/admin/users" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/users*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-users mr-2"></i>Users
            </a>
            <a href="/admin/categories" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/categories*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-tags mr-2"></i>Categories
            </a>
            <a href="/admin/reports" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/reports*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-chart-bar mr-2"></i>Reports
            </a>
            <a href="/admin/settings" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/settings*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-cog mr-2"></i>Settings
            </a>
        </nav>

        <div class="px-4 mt-12">
            <hr class="border-gray-700">
            <div class="mt-4">
                <a href="/" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800">
                    <i class="fas fa-store mr-2"></i>View Store
                </a>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 ml-64">
        <!-- Top header -->
        <header class="bg-white shadow-sm z-10 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" class="bg-gray-100 rounded-full py-2 pl-10 pr-4 w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="Search...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                            <i class="fas fa-bell text-gray-600"></i>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">3</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width: 20rem; display: none;">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b">
                                    <p class="text-sm font-medium text-gray-900">Notifications</p>
                                </div>
                                <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto no-scrollbar">
                                    <a href="#" class="flex px-4 py-3 hover:bg-gray-50">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-shopping-bag text-indigo-500 mt-1"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">New order received</p>
                                            <p class="text-xs text-gray-500">Order #12345 - 15 minutes ago</p>
                                        </div>
                                    </a>
                                </div>
                                <a href="#" class="block bg-gray-50 text-center py-2 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Admin User</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Log out
                            </a>
                            

                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main content -->
        <main class="overflow-y-auto p-6 bg-gray-50 h-[calc(100vh-4rem)]">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            @yield('content')

        </main>
    </div>

    @yield('scripts')
</body>
</html> 