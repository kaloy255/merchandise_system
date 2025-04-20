@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form container -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-6 py-5">
                <h1 class="text-2xl font-bold text-white">Edit Product</h1>
                <p class="text-indigo-100 mt-1">Update the product details below</p>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left column: Main product info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <x-input  
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Enter product name" 
                                value="{{ old('name', $product->name) }}"
                            ></x-input>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₱)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <x-input 
                                        type="number" 
                                        id="price" 
                                        name="price" 
                                        placeholder="0.00" 
                                        value="{{ old('price', $product->price) }}"
                                        required
                                        class="pl-7"
                                    ></x-input>
                                </div>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity in Stock</label>
                                <x-input 
                                    type="number" 
                                    id="quantity" 
                                    name="quantity" 
                                    placeholder="Enter quantity available" 
                                    value="{{ old('quantity', $product->quantity) }}"
                                    min="0" 
                                    required
                                ></x-input>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select 
                                id="category" 
                                name="category" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Select a category</option>
                                <option value="men" {{ $product->category === 'men' ? 'selected' : '' }}>Men</option>
                                <option value="women" {{ $product->category === 'women' ? 'selected' : '' }}>Women</option>
                                <option value="kids" {{ $product->category === 'kids' ? 'selected' : '' }}>Kids</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Product Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="5" 
                                placeholder="Enter product description and details"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Right column: Image upload and submit -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Image upload with preview -->
                        <div class="bg-gray-50 rounded-lg p-6 border-2 border-dashed border-gray-300">
                            <div class="text-center">
                                <div class="mt-1 flex justify-center">
                                    @if($product->image)
                                        <img id="preview-image" src="{{ asset('storage/' . $product->image) }}" class="max-h-40 mb-4 rounded" alt="Product preview">
                                    @else
                                        <img id="preview-image" class="hidden max-h-40 mb-4 rounded" alt="Product preview">
                                    @endif
                                </div>
                                <div class="space-y-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="image" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none hover:text-indigo-500">
                                            <span>{{ $product->image ? 'Change image' : 'Upload a photo' }}</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    @if($product->image)
                                        <p class="text-xs text-gray-700 mt-2">Current image will be kept if no new image is uploaded</p>
                                    @endif
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Product status info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-800 mb-2">Product Status</h3>
                            <div class="mt-1">
                                <span class="inline-flex items-center rounded-md {{ $product->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2.5 py-0.5 text-sm font-medium">
                                    {{ $product->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Last updated: {{ $product->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="mt-8 flex justify-end gap-4">
                    <a 
                        href="{{ route('admin.products') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(event) {
        const previewImage = document.getElementById('preview-image');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection