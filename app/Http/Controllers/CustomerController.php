<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_code'  => 'required|string|max:50|unique:customers,customer_code',
            'customer_name'  => 'required|string|max:150',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'customer_type'  => 'required|string|max:30',
            'opening_balance'=> 'nullable|numeric|min:0',
            'status'         => 'required|string|max:30',
        ]);

        $data['opening_balance'] = $data['opening_balance'] ?? 0;
        $data['current_balance'] = $data['opening_balance'];

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'customer_code'  => 'required|string|max:50|unique:customers,customer_code,' . $customer->id,
            'customer_name'  => 'required|string|max:150',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'customer_type'  => 'required|string|max:30',
            'status'         => 'required|string|max:30',
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->current_balance > 0) {
            return back()->withErrors(['error' => 'Cannot delete customer with outstanding balance.']);
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}