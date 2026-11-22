<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_code'  => 'required|string|max:50|unique:suppliers,supplier_code',
            'supplier_name'  => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'payment_terms'  => 'nullable|string|max:50',
            'status'         => 'required|string|max:30',
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'supplier_code'  => 'required|string|max:50|unique:suppliers,supplier_code,' . $supplier->id,
            'supplier_name'  => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'payment_terms'  => 'nullable|string|max:50',
            'status'         => 'required|string|max:30',
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }
}