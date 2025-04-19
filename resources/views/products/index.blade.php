@extends('layouts.app')
@section('title', 'G CLOTHING - Premium Fashion')
@section('content')
        <!-- Hero Section -->
    <div class="relative overflow-hidden bg-cover bg-center bg-no-repeat mb-12 rounded-xl" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'); height: 500px;">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-xl text-white">
                    <h1 class="mb-4 text-4xl font-extrabold leading-tight md:text-5xl">Discover Your Style</h1>
                    <p class="mb-8 text-lg text-gray-300">Explore our latest collections designed for comfort and style.</p>
                    <a href="#featured-products" class="inline-block rounded-full bg-indigo-600 px-8 py-3 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- category --}}
    <div class="mb-10">
        <form action="{{ route('products.index') }}" method="GET" id="category-form" class="flex flex-wrap justify-center gap-2 sm:gap-4">
            <x-nav-link type="submit" name="category" value="" class="{{ request('category') == '' && !request('name') ? 'active' : '' }}">
                <i class="fa-solid fa-border-all mr-2"></i>All
            </x-nav-link>
            <x-nav-link type="submit" name="category" value="men" class="{{ request('category') == 'men' ? 'active' : '' }}">
                <i class="fa-solid fa-person mr-2"></i>Men
            </x-nav-link>
            <x-nav-link type="submit" name="category" value="women" class="{{ request('category') == 'women' ? 'active' : '' }}">
                <i class="fa-solid fa-person-dress mr-2"></i>Women
            </x-nav-link>
            <x-nav-link type="submit" name="category" value="kids" class="{{ request('category') == 'kids' ? 'active' : '' }}">
                <i class="fa-solid fa-children mr-2"></i>Kids
            </x-nav-link>
        </form>
    </div>

    <!-- Featured Products -->
    <div id="featured-products" class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            @if(request('category'))
                {{ ucfirst(request('category')) }}'s Collection
            @elseif(request('name'))
                Search Results for "{{ request('name') }}"
            @else
                Featured Products
            @endif
        </h2>

        @if($products->isEmpty())
            <div class="text-center py-12">
                <div class="text-gray-500 mb-4">
                    <i class="fa-solid fa-search text-4xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">No products found</h3>
                <p class="text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                <a href="/" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Go back to all products</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                        <a href="{{ route('products.find', $product->id) }}" class="block relative overflow-hidden pt-[100%]">
                            <img 
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                src="{{ asset('storage/' . $product->image) }}" 
                                alt="{{ $product->name }}"
                            >
                            @if($product->quantity < 5 && $product->quantity > 0)
                                <span class="absolute top-2 right-2 bg-amber-500 text-white text-xs px-2 py-1 rounded-full">Low Stock</span>
                            @elseif($product->quantity <= 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Sold Out</span>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="mb-1 text-sm font-medium text-gray-500 uppercase">{{ $product->category }}</h3>
                            <a href="{{ route('products.find', $product->id) }}" class="block mb-2 text-lg font-semibold text-gray-800 hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </a>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-800">₱{{ number_format($product->price, 2) }}</span>
                                @auth
                                    @can('not-admin')
                                        
                                        <form action="{{ route('cart.add') }}"   method="POST" class="quick-add-form">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $authUser->id }}">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="text-gray-500 hover:text-indigo-600 transition-colors quick-add-btn" title="Add to Cart">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                            <noscript>
                                                <input type="submit" value="Add" class="ml-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded hover:bg-indigo-700">
                                            </noscript>
                                        </form>
                                    @endcan
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (if you have it) -->
            @if(method_exists($products, 'links'))
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Featured Categories -->
    <div class="my-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Shop by Category</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/?category=men" class="relative overflow-hidden rounded-lg group">
                <img 
                    src="https://images.unsplash.com/photo-1617137968427-85924c800a22?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8bWVucyUyMGZhc2hpb258ZW58MHwwfDB8fHww&auto=format&fit=crop&w=500&q=60" 
                    alt="Men's Collection" 
                    class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-2">Men's Collection</h3>
                        <p class="text-gray-300 mb-4">Discover the latest trends for men</p>
                        <span class="inline-flex items-center text-white font-medium group-hover:underline">
                            Shop Now <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </div>
                </div>
            </a>
            
            <a href="/?category=women" class="relative overflow-hidden rounded-lg group">
                <img 
                    src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHdvbWVucyUyMGZhc2hpb258ZW58MHwwfDB8fHww&auto=format&fit=crop&w=500&q=60" 
                    alt="Women's Collection" 
                    class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-2">Women's Collection</h3>
                        <p class="text-gray-300 mb-4">Elevate your style with our women's collection</p>
                        <span class="inline-flex items-center text-white font-medium group-hover:underline">
                            Shop Now <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </div>
                </div>
            </a>
            
            <a href="/?category=kids" class="relative overflow-hidden rounded-lg group">
                <img 
                    src="https://images.unsplash.com/photo-1543269664-56d93c1b41a6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OXx8a2lkcyUyMGZhc2hpb258ZW58MHwwfDB8fHww&auto=format&fit=crop&w=500&q=60" 
                    alt="Kids Collection" 
                    class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-2">Kids Collection</h3>
                        <p class="text-gray-300 mb-4">Comfortable and stylish clothes for kids</p>
                        <span class="inline-flex items-center text-white font-medium group-hover:underline">
                            Shop Now <i class="fa-solid fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="my-16 bg-gray-100 rounded-xl p-8 md:p-12">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Join Our Newsletter</h2>
            <p class="text-gray-600 mb-6">Stay updated with our latest collections and exclusive offers.</p>
            <form class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto">
                <input 
                    type="email" 
                    placeholder="Your email address" 
                    class="flex-grow px-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors">
                    Subscribe
                </button>
            </form>
            <p class="mt-4 text-xs text-gray-500">By subscribing, you agree to our Privacy Policy and consent to receive updates from our company.</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing cart functionality');
    
            // Get all quick add forms
            const quickAddForms = document.querySelectorAll('.quick-add-form');
            console.log(`Found ${quickAddForms.length} quick add forms`);
    
            // Get the cart icon element
            const cartIcon = document.getElementById('cart-icon');
            const cartCountElement = document.getElementById('cart-count');
    
            // Check if cart icon exists (will not exist for admin users)
            const isCartIconVisible = cartIcon !== null && cartCountElement !== null;
    
            // Function to create flying cart animation
            function createFlyingCartAnimation(startElement) {
                // Skip animation if cart icon doesn't exist
                if (!isCartIconVisible) {
                    console.log('Cart icon not found, skipping animation');
                    return;
                }
    
                // Create flying element
                const flyingElement = document.createElement('div');
                flyingElement.className = 'flying-cart-item';
    
                // Get the starting position (product image or button)
                const startRect = startElement.getBoundingClientRect();
    
                // Get the ending position (cart icon)
                const endRect = cartIcon.getBoundingClientRect();
    
                // Style the flying element
                flyingElement.style.cssText = `
                        position: fixed;
                        z-index: 9999;
                        width: 20px;
                        height: 20px;
                        background-color: #4f46e5;
                        border-radius: 50%;
                        opacity: 0.8;
                        left: ${startRect.left + startRect.width / 2}px;
                        top: ${startRect.top + startRect.height / 2}px;
                        transform: translate(-50%, -50%);
                        transition: all 0.8s cubic-bezier(0.075, 0.82, 0.165, 1);
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    `;
    
                // Append to body
                document.body.appendChild(flyingElement);
    
                // Trigger animation (after a small delay to ensure the element is rendered)
                setTimeout(() => {
                    flyingElement.style.left = `${endRect.left + endRect.width / 2}px`;
                    flyingElement.style.top = `${endRect.top + endRect.height / 2}px`;
                    flyingElement.style.width = '10px';
                    flyingElement.style.height = '10px';
                    flyingElement.style.opacity = '0';
    
                    // Scale animation for cart icon
                    cartIcon.style.transform = 'scale(1)';
                    cartIcon.style.transition = 'transform 0.3s ease';
    
                    setTimeout(() => {
                        cartIcon.style.transform = 'scale(1.4)';
    
                        setTimeout(() => {
                            cartIcon.style.transform = 'scale(1)';
    
                            // Remove flying element after animation completes
                            setTimeout(() => {
                                if (document.body.contains(flyingElement)) {
                                    document.body.removeChild(flyingElement);
                                }
                            }, 100);
                        }, 300);
                    }, 600);
                }, 10);
            }
    
            // Function to update cart count
            function updateCartCount(count) {
                // Skip update if cart count element doesn't exist
                if (!isCartIconVisible) {
                    console.log('Cart count element not found, skipping update');
                    return;
                }
    
                cartCountElement.textContent = count;
    
                // Add pulse animation
                cartCountElement.classList.add('animate-pulse');
                setTimeout(() => {
                    cartCountElement.classList.remove('animate-pulse');
                }, 1000);
            }
            
    
            // Simpler implementation for adding to cart
            quickAddForms.forEach((form, index) => {
                const button = form.querySelector('.quick-add-btn');
                const productId = form.querySelector('input[name="product_id"]').value;
                const productElement = form.closest('.group'); // Find the parent product element
    
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log(`Quick add button clicked for product ID: ${productId}`);
    
                    // Change button appearance to show loading
                    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                    this.disabled = true;
    
                    // Get form data
                    const formData = new FormData(form);
                    const csrfToken = form.querySelector('input[name="_token"]').value;
                    const productData = {
                        product_id: productId,
                        quantity: 1,
                        _token: csrfToken
                    };
    
                    // Log the URL we're posting to
                    console.log(`Posting to: ${form.action}`);
    
                    // Use simple XHR request instead of fetch for maximum compatibility
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
                    // Prepare data for URL-encoded form submission
                    const urlEncodedData = Object.keys(productData)
                        .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(productData[key]))
                        .join('&');
    
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            console.log(`Response status: ${xhr.status}`);
    
                            if (xhr.status >= 200 && xhr.status < 300) {
                                // Success
                                console.log('Success response:', xhr.responseText);
                                let responseData;
                                try {
                                    responseData = JSON.parse(xhr.responseText);
    
                                    // Only start the flying animation if cart icon exists
                                    if (isCartIconVisible) {
                                        createFlyingCartAnimation(button);
    
                                        // Update cart count if available
                                        if (responseData.cart_count) {
                                            updateCartCount(responseData.cart_count);
                                        }
                                    }
    
                                } catch (e) {
                                    console.error('Error parsing JSON:', e);
                                    responseData = {
                                        message: 'Product added to cart!'
                                    };
                                }
    
                                // Show success feedback
                                button.innerHTML = '<i class="fa-solid fa-check"></i>';
                                button.classList.remove('text-gray-500');
                                button.classList.add('text-green-500');
    
                                // Create and show a floating notification
                                const notification = document.createElement('div');
                                notification.className = 'fixed top-20 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 shadow-md transition-opacity duration-500';
                                notification.innerHTML = '<div class="flex items-center"><i class="fa-solid fa-check-circle mr-2"></i> Product added to cart!</div>';
                                document.body.appendChild(notification);
    
                                // Remove notification after 3 seconds
                                setTimeout(() => {
                                    notification.style.opacity = '0';
                                    setTimeout(() => {
                                        document.body.removeChild(notification);
                                    }, 500);
                                }, 3000);
                            } else {
                                // Error
                                console.error('Error response:', xhr.responseText);
    
                                // Show error feedback
                                button.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i>';
                                button.classList.remove('text-gray-500');
                                button.classList.add('text-red-500');
    
                                // Create and show an error notification
                                const notification = document.createElement('div');
                                notification.className = 'fixed top-20 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-md transition-opacity duration-500';
                                notification.innerHTML = `<div class="flex items-center"><i class="fa-solid fa-exclamation-circle mr-2"></i> Failed to add product to cart</div>`;
                                document.body.appendChild(notification);
    
                                // Remove notification after 3 seconds
                                setTimeout(() => {
                                    notification.style.opacity = '0';
                                    setTimeout(() => {
                                        document.body.removeChild(notification);
                                    }, 500);
                                }, 3000);
                            }
    
                            // Reset button after delay
                            setTimeout(() => {
                                button.innerHTML = '<i class="fa-solid fa-cart-plus"></i>';
                                button.classList.remove('text-green-500', 'text-red-500');
                                button.classList.add('text-gray-500');
                                button.disabled = false;
                            }, 2000);
                        }
                    };
    
                    // Send the request
                    xhr.send(urlEncodedData);
                });
            });
        });
    </script>
    
    <style>
        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        
        .animate-pulse {
            animation: pulse 0.5s ease;
        }
    </style>
@endsection