@extends('layouts.app')
@section('content')
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="text-gray-700 hover:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-home mr-2"></i>Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <a href=""  class="text-gray-700 hover:text-indigo-600 transition-colors">
                            {{ ucfirst($product->category) }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                        <span class="text-gray-500">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
    </nav>

    <!-- Product Details -->
    <div class="bg-white rounded-xl overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <!-- Product Images -->
            <div>
                <div class="relative overflow-hidden rounded-lg bg-gray-100 mb-4">
                    <img 
                        src="{{ asset('storage/' . $product->image) }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-auto object-cover aspect-square"
                    >
                </div>
                
                <!-- Additional images would go here in thumbnails -->
                <div class="grid grid-cols-4 gap-2">
                    <button class="border-2 border-indigo-600 rounded-md overflow-hidden">
                        <img 
                            src="{{ asset('storage/' . $product->image) }}" 
                            alt="Thumbnail" 
                            class="w-full h-auto aspect-square object-cover"
                        >
                    </button>
                    <!-- Placeholder thumbnails -->
                    @for ($i = 0; $i < 3; $i++)
                        <button class="border border-gray-200 rounded-md overflow-hidden opacity-50">
                            <div class="w-full h-auto aspect-square bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        </button>
                    @endfor
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="flex flex-col">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <div class="text-lg font-medium text-indigo-600 mb-4">₱{{ number_format($product->price, 2) }}</div>
                
                <!-- Add to Cart and Buy Now Buttons -->
                <div class="flex flex-col space-y-3 mb-6">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="flex rounded-md">
                            <button type="button" class="decrement-button flex items-center justify-center h-10 w-10 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200">
                                <i class="fa-solid fa-minus text-sm"></i>
                            </button>
                            <input type="number" min="1" max="{{ $product->quantity }}" value="1" id="product-quantity" class="h-10 w-16 border-gray-300 text-center [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                            <button type="button" class="increment-button flex items-center justify-center h-10 w-10 rounded-r-md border border-l-0 border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200">
                                <i class="fa-solid fa-plus text-sm"></i>
                            </button>
                        </div>
                        <span class="text-sm text-gray-500">{{ $product->quantity }} available</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @auth
                            @if(!Auth::user()->is_admin)
                                <form  action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-input">
                                    <button type="submit" class="flex w-full items-center justify-center rounded-md border border-indigo-600 bg-white px-5 py-3 text-base font-medium text-indigo-600 shadow-sm hover:bg-indigo-50 transition-colors">
                                        <i class="fa-solid fa-cart-plus mr-2"></i>Add to Cart
                                    </button>
                                </form>
                                
                                <button type="button" id="buy-now-button" class="flex w-full items-center justify-center rounded-md bg-indigo-600 px-5 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                    <i class="fa-solid fa-bolt mr-2"></i>Buy Now
                                </button>
                                
                                <!-- Buy Now Modal -->
                                <div id="buy-now-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
                                    <div class="bg-white rounded-lg p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto">
                                        <div class="flex justify-between items-center mb-4">
                                            <h2 class="text-xl font-bold text-gray-800">Quick Checkout</h2>
                                            <button type="button" id="close-modal" class="text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Error and success alert placeholders -->
                                        <div id="quick-checkout-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"></div>
                                        <div id="quick-checkout-success" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>
                                        
                                        <div class="mb-4 p-3 bg-gray-50 rounded-md">
                                            <h3 class="font-semibold mb-2">{{ $product->name }}</h3>
                                            <div class="flex justify-between">
                                                <span>Price: ₱{{ number_format($product->price, 2) }}</span>
                                                <span>Quantity: <span id="modal-quantity">1</span></span>
                                            </div>
                                            <div class="mt-1 text-right text-indigo-600 font-semibold">
                                                Total: ₱<span id="modal-total">{{ number_format($product->price, 2) }}</span>
                                            </div>
                                        </div>
                                        {{-- action="{{ route('checkout.quick-purchase') }}" --}}
                                        <form  method="POST" id="buy-now-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1" class="quantity-input">
                                            
                                            <div class="mb-4">
                                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                                                <input type="text" id="shipping_address" name="shipping_address" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                                    <input type="text" id="shipping_city" name="shipping_city" required
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div>
                                                    <label for="shipping_zipcode" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                                                    <input type="text" id="shipping_zipcode" name="shipping_zip" required
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                                <input type="text" id="contact_number" name="contact_number" required
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                                                <div class="grid grid-cols-3 gap-3">
                                                    <div>
                                                        <input type="radio" id="payment_cod" name="payment_method" value="cash_on_delivery" checked class="hidden peer">
                                                        <label for="payment_cod" class="flex flex-col items-center justify-center p-2 border border-gray-300 rounded-md cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50">
                                                            <i class="fa-solid fa-money-bill-wave text-xl mb-1"></i>
                                                            <span class="text-xs">Cash on Delivery</span>
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="payment_card" name="payment_method" value="credit_card" class="hidden peer">
                                                        <label for="payment_card" class="flex flex-col items-center justify-center p-2 border border-gray-300 rounded-md cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50">
                                                            <i class="fa-solid fa-credit-card text-xl mb-1"></i>
                                                            <span class="text-xs">Credit Card</span>
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="payment_bank" name="payment_method" value="bank_transfer" class="hidden peer">
                                                        <label for="payment_bank" class="flex flex-col items-center justify-center p-2 border border-gray-300 rounded-md cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50">
                                                            <i class="fa-solid fa-building-columns text-xl mb-1"></i>
                                                            <span class="text-xs">Bank Transfer</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" id="complete-purchase-btn" class="w-full flex items-center justify-center rounded-md bg-indigo-600 px-5 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
                                                <span id="btn-text"><i class="fa-solid fa-bolt mr-2"></i>Complete Purchase</span>
                                                <span id="btn-loading" class="hidden">
                                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="col-span-2 text-center py-2 text-gray-500">
                                    <i class="fa-solid fa-info-circle mr-1"></i> Admin cannot make purchases
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-md border border-indigo-600 bg-white px-5 py-3 text-base font-medium text-indigo-600 shadow-sm hover:bg-indigo-50 transition-colors">
                                <i class="fa-solid fa-user mr-2"></i>Login to Shop
                            </a>
                        @endauth
                    </div>
                </div>
                
                <!-- Product Description -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Description</h2>
                    <p class="text-gray-700">{{ $product->description }}</p>
                </div>
                
                <div class="space-y-4 mb-6">
                    <!-- Availability -->
                    <div class="flex items-center">
                        @if($product->quantity > 10)
                            <span class="text-green-600 flex items-center">
                                <i class="fa-solid fa-check mr-2"></i> In Stock
                            </span>
                        @elseif($product->quantity > 0)
                            <span class="text-amber-600 flex items-center">
                                <i class="fa-solid fa-clock mr-2"></i> Low Stock ({{ $product->quantity }} left)
                            </span>
                        @else
                            <span class="text-red-600 flex items-center">
                                <i class="fa-solid fa-xmark mr-2"></i> Out of Stock
                            </span>
                        @endif
                    </div>
                    
                    <!-- Category -->
                    <div class="flex items-center">
                        <span class="text-gray-500 mr-2">Category:</span>
                        <a href="/?category={{ $product->category }}" class="text-indigo-600 hover:underline">
                            {{ ucfirst($product->category) }}
                        </a>
                    </div>
                </div>
                
                <!-- Admin Actions -->
                @auth
                    @if(Auth::user()->is_admin)
                        <div class="flex flex-col space-y-3 mt-auto pt-6 border-t border-gray-200">
                            <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-indigo-500 text-indigo-600 bg-white hover:bg-indigo-50 rounded-md transition-colors">
                                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Product
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-500 text-red-600 bg-white hover:bg-red-50 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash mr-2"></i> Delete Product
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
        
        <!-- Product Description -->
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Product Description</h2>
            <div class="prose max-w-none text-gray-600">
                <p>{{ $product->description }}</p>
            </div>
        </div>
    </div>
    
    <!-- Related Products (placeholder) -->
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for ($i = 0; $i < 4; $i++)
                <div class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md opacity-50">
                    <div class="relative overflow-hidden pt-[100%]">
                        <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                            <i class="fa-solid fa-shirt text-4xl text-gray-300"></i>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="mb-1 text-sm font-medium text-gray-500 uppercase">Category</h3>
                        <p class="block mb-2 text-lg font-semibold text-gray-800">Product Name</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">₱0.00</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <script>
        // Custom number input
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('product-quantity');
            const hiddenQuantityInputs = document.querySelectorAll('.quantity-input');
            const decrementButton = document.querySelector('.decrement-button');
            const incrementButton = document.querySelector('.increment-button');
            const buyNowForm = document.getElementById('buy-now-form');
            const buyNowButton = document.getElementById('buy-now-button');
            const buyNowModal = document.getElementById('buy-now-modal');
            const closeModalButton = document.getElementById('close-modal');
            const modalQuantityEl = document.getElementById('modal-quantity');
            const modalTotalEl = document.getElementById('modal-total');
            const completePurchaseBtn = document.getElementById('complete-purchase-btn');
            const errorDiv = document.getElementById('quick-checkout-error');
            const successDiv = document.getElementById('quick-checkout-success');
            
            // Set initial values
            updateHiddenInputs(quantityInput.value);
            
            // Add event listeners for quantity controls
            decrementButton.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                value = isNaN(value) ? 1 : value;
                value = value > 1 ? value - 1 : 1;
                quantityInput.value = value;
                updateHiddenInputs(value);
                updateModalDisplay(value);
            });
            
            incrementButton.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.getAttribute('max'));
                value = isNaN(value) ? 1 : value;
                value = value < max ? value + 1 : max;
                quantityInput.value = value;
                updateHiddenInputs(value);
                updateModalDisplay(value);
            });
            
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                const min = parseInt(this.getAttribute('min'));
                const max = parseInt(this.getAttribute('max'));
                
                value = isNaN(value) ? 1 : value;
                value = value < min ? min : value;
                value = value > max ? max : value;
                
                this.value = value;
                updateHiddenInputs(value);
                updateModalDisplay(value);
            });
            
            function updateHiddenInputs(value) {
                hiddenQuantityInputs.forEach(input => {
                    input.value = value;
                });
                console.log('Updated quantity inputs to: ' + value);
            }
            
            function updateModalDisplay(value) {
                if (modalQuantityEl && modalTotalEl) {
                    modalQuantityEl.textContent = value;
                    
                    // Update total price in modal
                    const price = {{ $product->price }};
                    const total = (price * value).toFixed(2);
                    modalTotalEl.textContent = new Intl.NumberFormat('en-PH', { 
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(total);
                }
            }
            
            // Buy Now Modal Functionality
            if (buyNowButton && buyNowModal && closeModalButton) {
                buyNowButton.addEventListener('click', function() {
                    // Update modal with current quantity and total
                    const currentQuantity = parseInt(quantityInput.value);
                    updateModalDisplay(currentQuantity);
                    
                    // Reset form and alerts
                    buyNowForm.reset();
                    errorDiv.classList.add('hidden');
                    successDiv.classList.add('hidden');
                    
                    // Set product and quantity values
                    buyNowForm.querySelector('input[name="product_id"]').value = {{ $product->id }};
                    buyNowForm.querySelector('input[name="quantity"]').value = currentQuantity;
                    
                    // Show modal
                    buyNowModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                });
                
                closeModalButton.addEventListener('click', function() {
                    buyNowModal.classList.add('hidden');
                    document.body.style.overflow = 'auto'; // Re-enable scrolling
                });
                
                // Close modal when clicking outside
                buyNowModal.addEventListener('click', function(e) {
                    if (e.target === buyNowModal) {
                        buyNowModal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
            
            // AJAX Quick Checkout Form Handling
            if (buyNowForm && completePurchaseBtn) {
                buyNowForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Hide any previous alerts
                    errorDiv.classList.add('hidden');
                    successDiv.classList.add('hidden');
                    
                    // Show loading state
                    document.getElementById('btn-text').classList.add('hidden');
                    document.getElementById('btn-loading').classList.remove('hidden');
                    completePurchaseBtn.disabled = true;
                    
                    // Collect form data
                    const formData = new FormData(this);
                    
                    // Make AJAX request
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', this.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    
                    xhr.onload = function() {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            let response;
                            try {
                                response = JSON.parse(xhr.responseText);
                            } catch (e) {
                                // If response is not valid JSON
                                showError('Invalid response from server');
                                resetButton();
                                return;
                            }
                            
                            if (response.success) {
                                // Show success message
                                successDiv.textContent = response.message;
                                successDiv.classList.remove('hidden');
                                
                                // Disable form fields
                                Array.from(buyNowForm.elements).forEach(element => {
                                    element.disabled = true;
                                });
                                
                                // Redirect after delay
                                setTimeout(function() {
                                    window.location.href = response.redirect_url;
                                }, 1500);
                            } else {
                                showError(response.message);
                                resetButton();
                            }
                        } else {
                            showError('Error processing your request. Please try again.');
                            resetButton();
                        }
                    };
                    
                    xhr.onerror = function() {
                        showError('Network error. Please check your connection and try again.');
                        resetButton();
                    };
                    
                    xhr.send(formData);
                });
                
                function showError(message) {
                    errorDiv.textContent = message;
                    errorDiv.classList.remove('hidden');
                    errorDiv.scrollIntoView({ behavior: 'smooth' });
                }
                
                function resetButton() {
                    document.getElementById('btn-loading').classList.add('hidden');
                    document.getElementById('btn-text').classList.remove('hidden');
                    completePurchaseBtn.disabled = false;
                }
            }
            
            // Extra check for Add to Cart form submission
            const addToCartForm = document.getElementById('add-to-cart-form');
            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function(e) {
                    // Get the current quantity value from the main quantity input
                    const currentQuantity = parseInt(quantityInput.value);
                    
                    // Update the hidden quantity input in the Add to Cart form
                    const addToCartQuantityInput = this.querySelector('.quantity-input');
                    addToCartQuantityInput.value = currentQuantity;
                    
                    console.log('Add to Cart form submitted with quantity: ' + currentQuantity);
                });
            }
        });
    </script>
@endsection