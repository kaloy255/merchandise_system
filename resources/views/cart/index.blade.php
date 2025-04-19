@extends('layouts.app')
@section('title', 'Shopping Cart | BOJ CLOTHING')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Shopping Cart</h1>


    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($cartItems)
    <form id="checkout-form" {{-- action="{{ route('checkout.index') }} --}}" method="GET">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b">
                <div class="flex items-center">
                    <input type="checkbox" id="select-all" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="select-all" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                </div>
                <button type="button" id="delete-selected" class="text-red-600 hover:text-red-800 text-sm flex items-center" disabled>
                    <i class="fa-solid fa-trash-can mr-1"></i> Remove Selected
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                            <th class="py-4 px-6 w-10"></th>
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Price</th>
                            <th class="py-4 px-6">Quantity</th>
                            <th class="py-4 px-6">Subtotal</th>
                            <th class="py-4 px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                      
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <input type="checkbox" name="selected_items[]" value="{{ $item['id'] }}" class="item-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-16 h-16 flex-shrink-0 mr-4 bg-gray-100 rounded-md overflow-hidden">
                                        @if($item['product']->image)
                                        <img
                                            src="{{ asset('storage/' . $item['product']->image) }}"
                                            alt="{{ $item['product']->name }}"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <i class="fa-solid fa-shirt text-gray-400 text-3xl"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $item['product']->name }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($item['product']->category) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-800">₱{{ number_format($item['product']->price, 2) }}</td>
                            <td class="py-4 px-6">
                                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center quantity-form" data-item-id="{{ $item['id'] }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center">
                                        <button type="button" class="quantity-down w-8 h-8 bg-gray-100 rounded-l-md flex items-center justify-center border border-gray-300" id="quantity-down-{{ $item['id'] }}">
                                            <i class="fa-solid fa-minus text-xs text-gray-600"></i>
                                        </button>
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="{{ $item['product']->quantity }}"
                                            class="w-14 h-8 text-center border-y border-gray-300 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            id="quantity-input-{{ $item['id'] }}">
                                        <button type="button" class="quantity-up w-8 h-8 bg-gray-100 rounded-r-md flex items-center justify-center border border-gray-300" id="quantity-up-{{ $item['id'] }}">
                                            <i class="fa-solid fa-plus text-xs text-gray-600"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-800">₱{{ number_format($item['subtotal'], 2) }}</td>
                            <td class="py-4 px-6">
                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to remove this item?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t bg-gray-50">
                <div class="flex justify-between items-center flex-wrap">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-medium text-gray-800 mb-1">Total Amount</h3>
                        <p class="text-2xl font-bold text-gray-900" id="selected-total">₱0.00</p>
                        <p class="text-sm text-gray-500" id="selected-items-count">No items selected</p>
                    </div>
                    <div class="flex flex-col gap-3 md:flex-row">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Continue Shopping
                        </a>
                        <button type="submit" id="checkout-btn" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors opacity-50 cursor-not-allowed" disabled>
                            Checkout <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white p-8 rounded-lg shadow-sm text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-cart-shopping text-indigo-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Cart is Empty</h2>
            <p class="text-gray-600 mb-6">It looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                Start Shopping <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityForms = document.querySelectorAll('.quantity-form');
   

        quantityForms.forEach(form => {
        const itemId = form.dataset.itemId; 
        const quantityInput = form.querySelector(`#quantity-input-${itemId}`);
        const quantityDown = form.querySelector(`#quantity-down-${itemId}`);
        const quantityUp = form.querySelector(`#quantity-up-${itemId}`);
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));

        console.log(`Item ${itemId} elements:`, { quantityInput, quantityDown, quantityUp });

        quantityDown.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) quantityInput.value = currentValue - 1;
            updateCart(form);
        });

        quantityUp.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < maxQuantity) quantityInput.value = currentValue + 1;
            updateCart(form);
        });

        quantityInput.addEventListener('change', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 1) quantityInput.value = 1;
            if (currentValue > maxQuantity) quantityInput.value = maxQuantity;
            updateCart(form);
        });
        });
    
    // Function to update cart via AJAX
    async function updateCart(form) {
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        
        // Laravel requires _method field for PATCH/PUT/DELETE requests
        formData.append('_method', 'PATCH');
        
        fetch(url, {
            method: 'POST', // Always POST with _method for Laravel
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the subtotal in the UI
                const row = form.closest('tr');
                const subtotalCell = row.querySelector('td:nth-child(5)');
                subtotalCell.textContent = data.new_subtotal_formatted;
                
                // Update any selected total if item is checked
                const checkbox = row.querySelector('.item-checkbox');
                if (checkbox && checkbox.checked) {
                    updateSelectedTotal();
                }
            } else {
                alert(data.message);
                // Reset to original value if there was an error
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            alert('Failed to update cart. Please try again.');
        });
    }

        // Checkbox functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkoutButton = document.getElementById('checkout-btn');
        const deleteSelectedButton = document.getElementById('delete-selected');
        const selectedTotalElement = document.getElementById('selected-total');
        const selectedItemsCountElement = document.getElementById('selected-items-count');

        if (selectAllCheckbox) {
            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;

                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });

                updateSelectedTotal();
            });

            // Individual checkbox functionality
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllCheckbox();
                    updateSelectedTotal();
                });
            });

            // Update the "Select All" checkbox state
            function updateSelectAllCheckbox() {
                const totalCheckboxes = itemCheckboxes.length;
                const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked').length;

                selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
                selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
            }

            // Calculate and update the total for selected items
            function updateSelectedTotal() {
                const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
                let total = 0;

                checkedCheckboxes.forEach(checkbox => {
                    const row = checkbox.closest('tr');
                    const subtotalCell = row.querySelector('td:nth-child(5)');
                    const subtotalText = subtotalCell.textContent;
                    const subtotal = parseFloat(subtotalText.replace('₱', '').replace(',', ''));



                    if (!isNaN(subtotal)) {
                        total += subtotal;
                    }
                });

                // Update the total display
                selectedTotalElement.textContent = `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                // Update the selected items count
                const count = checkedCheckboxes.length;
                if (count === 0) {
                    selectedItemsCountElement.textContent = 'No items selected';
                    checkoutButton.disabled = true;
                    checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
                    deleteSelectedButton.disabled = true;
                } else {
                    selectedItemsCountElement.textContent = `${count} item(s) selected`;
                    checkoutButton.disabled = false;
                    checkoutButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    deleteSelectedButton.disabled = false;
                }
            }

            // Delete selected items
            deleteSelectedButton.addEventListener('click', function() {
                const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');

                if (checkedCheckboxes.length === 0) {
                    return;
                }

                if (confirm('Are you sure you want to remove the selected items?')) {
                    // Create form element for multiple deletion
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('cart.removeMultiple') }}";
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    // Add method spoofing for DELETE
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);

                    // Add selected item IDs
                    checkedCheckboxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'item_ids[]';
                        input.value = checkbox.value;
                        form.appendChild(input);
                    });

                    // Append form to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });

            // Initial update
            updateSelectedTotal();
        }
    });
</script>

@endsection