<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_code'  => 'required|string|max:50|unique:products,product_code',
            'product_name'  => 'required|string|max:150',
            'category'      => 'required|string|max:50',
            'unit'          => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
            'cost_price'    => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0', 
            'reorder_level' => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
            'status'        => 'required|string|max:30',
        ]);

        $data['cost_price']    = $data['cost_price'] ?? 0;
         $data['current_stock'] = $data['current_stock'] ?? 0; 
        $data['reorder_level'] = $data['reorder_level'] ?? 0;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'product_code'  => 'required|string|max:50|unique:products,product_code,' . $product->id,
            'product_name'  => 'required|string|max:150',
            'category'      => 'required|string|max:50',
            'unit'          => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
            'cost_price'    => 'nullable|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0', 
            'reorder_level' => 'nullable|integer|min:0',
            'description'   => 'nullable|string',
            'status'        => 'required|string|max:30',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}