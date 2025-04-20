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
        @media (max-width: 768px) {
            .sidebar-open {
                transform: translateX(0);
            }
            .sidebar-closed {
                transform: translateX(-100%);
            }
        }
    </style>
</head>
<body class="flex flex-col md:flex-row h-screen bg-gray-100 overflow-hidden">
    <!-- Mobile Menu Toggle -->
    <div x-data="{ sidebarOpen: false }" class="md:hidden">
        <button @click="sidebarOpen = !sidebarOpen" class="fixed top-4 left-4 z-40 p-2 rounded-md bg-gray-900 text-white focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-20" style="display: none;"></div>
        
        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full"
             class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white z-30 overflow-y-auto no-scrollbar"
             style="display: none;">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl font-bold">G<span class="text-indigo-400">ADMIN</span></span>
                </div>
                <button @click="sidebarOpen = false" class="text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mt-10 px-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/dashboard')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.products') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/products*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                    <i class="fas fa-tshirt mr-2"></i>Products
                </a>
                <a href="{{ route('admin.orders') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/orders*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                    <i class="fas fa-shopping-cart mr-2"></i>Orders
                </a>
                <a href="{{ route('admin.users') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/users*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
            
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/settings*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
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
    </div>

    <!-- Desktop Sidebar -->
    <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 fixed inset-y-0 left-0 z-30 hidden md:block">
        <div class="flex items-center justify-between px-4">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-bold">G<span class="text-indigo-400">ADMIN</span></span>
            </div>
        </div>
        
        <nav class="mt-10 px-4">
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/dashboard')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.products') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/products*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-tshirt mr-2"></i>Products
            </a>
            <a href="{{ route('admin.orders') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/orders*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-shopping-cart mr-2"></i>Orders
            </a>
            <a href="{{ route('admin.users') }}" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/users*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
                <i class="fas fa-users mr-2"></i>Users
            </a>
         
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 @if(request()->is('admin/settings*')) bg-indigo-800 text-white @else hover:bg-gray-800 @endif">
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
    <div class="flex-1 md:ml-64">
        <!-- Top header -->
        <header class="bg-white shadow-sm z-10 p-4">
            <div class="flex items-center justify-between">
                <div class="md:ml-0 ml-8">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-2 md:space-x-4">
                    
                    <!-- Notifications -->
                    <div x-data="notificationSystem()" x-init="fetchNotifications()" class="relative">
                        <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                            <i class="fas fa-bell text-gray-600"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width: 20rem; display: none;">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b flex justify-between items-center">
                                    <p class="text-sm font-medium text-gray-900">Notifications</p>
                                    <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-indigo-600 hover:text-indigo-800 focus:outline-none">
                                        Mark all as read
                                    </button>
                                </div>
                                <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto no-scrollbar">
                                    <template x-if="notifications.length > 0">
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div :class="{ 'bg-indigo-50': !notification.read_at }" class="flex px-4 py-3 hover:bg-gray-50">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-shopping-bag text-indigo-500 mt-1"></i>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="flex justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="notification.content"></p>
                                                        <button x-show="!notification.read_at" @click="markAsRead(notification.id)" class="text-xs text-indigo-600 hover:text-indigo-800 ml-2">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-gray-500" x-text="formatTime(notification.created_at)"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-3 text-center text-sm text-gray-500">
                                            No notifications
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="h-8 w-8 rounded-full" alt="{{ Auth::user()->name }}">
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 hidden md:inline"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log out
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main content -->
        <main class="overflow-y-auto p-4 md:p-6 bg-gray-50 h-[calc(100vh-4rem)]">
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
    
    <script>
        function notificationSystem() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                
                fetchNotifications() {
                    fetch('{{ route("admin.notifications") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data.notifications;
                            this.unreadCount = data.unreadCount;
                            
                            // Set up auto-refresh every 30 seconds
                            setTimeout(() => this.fetchNotifications(), 30000);
                        })
                        .catch(error => console.error('Error fetching notifications:', error));
                },
                
                markAsRead(id) {
                    fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the local notification
                            this.notifications = this.notifications.map(notification => {
                                if (notification.id === id) {
                                    notification.read_at = new Date().toISOString();
                                }
                                return notification;
                            });
                            
                            // Update the unread count
                            this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                        }
                    })
                    .catch(error => console.error('Error marking notification as read:', error));
                },
                
                markAllAsRead() {
                    fetch('{{ route("admin.notifications.read-all") }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update all local notifications
                            const now = new Date().toISOString();
                            this.notifications = this.notifications.map(notification => {
                                notification.read_at = now;
                                return notification;
                            });
                            
                            // Reset unread count
                            this.unreadCount = 0;
                        }
                    })
                    .catch(error => console.error('Error marking all notifications as read:', error));
                },
                
                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
                    
                    if (diffInMinutes < 1) {
                        return 'Just now';
                    } else if (diffInMinutes < 60) {
                        return `${diffInMinutes} minute${diffInMinutes !== 1 ? 's' : ''} ago`;
                    } else if (diffInMinutes < 60 * 24) {
                        const hours = Math.floor(diffInMinutes / 60);
                        return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
                    } else {
                        const days = Math.floor(diffInMinutes / (60 * 24));
                        if (days < 7) {
                            return `${days} day${days !== 1 ? 's' : ''} ago`;
                        } else {
                            return date.toLocaleDateString('en-US', { 
                                month: 'short', 
                                day: 'numeric',
                                year: now.getFullYear() !== date.getFullYear() ? 'numeric' : undefined
                            });
                        }
                    }
                }
            };
        }
    </script>
</body>
</html> 