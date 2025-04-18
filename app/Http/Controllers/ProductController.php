<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Query builder for products
        $query = Product::query();
        
        // Filter by category if provided
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }
        
        // Search by name if provided
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        // Get the results
        $products = $query->get();
        
        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }


        Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function find(int $id)
    {
        
        $product = Product::find($id);
        return view('products.item', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
