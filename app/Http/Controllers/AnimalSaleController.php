<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalSale;
use Illuminate\Http\Request;

class AnimalSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = AnimalSale::with('animal')->latest('sale_date')->paginate(15);
        return view('animal_sales.index', compact('sales'));
    }

    public function create()
    {
        $animals = Animal::where('status', 'active')->get();
        return view('animal_sales.create', compact('animals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'animal_id'        => 'required|exists:animals,id',
            'customer_name'    => 'required|string|max:150',
            'customer_phone'   => 'nullable|string|max:30',
            'sale_date'        => 'required|date',
            'carrying_value'   => 'nullable|numeric|min:0',
            'selling_price'    => 'required|numeric|min:0',
            'proceeds_received'=> 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $data['carrying_value']    = $data['carrying_value'] ?? 0;
        $data['proceeds_received'] = $data['proceeds_received'] ?? 0;
        $data['status']            = 'completed';

        $sale = AnimalSale::create($data);

        // Animal-এর status "sold" এ আপডেট
        Animal::where('id', $data['animal_id'])->update(['status' => 'sold']);

        return redirect()->route('animal-sales.index')->with('success', 'Animal sale recorded successfully!');
    }

    public function edit(AnimalSale $animalSale)
{
    $animals = Animal::where('status', 'active')
        ->orWhere('id', $animalSale->animal_id)
        ->get();
    return view('animal_sales.edit', compact('animalSale', 'animals'));
}

public function update(Request $request, AnimalSale $animalSale)
{
    $data = $request->validate([
        'customer_name'     => 'required|string|max:150',
        'customer_phone'    => 'nullable|string|max:30',
        'sale_date'         => 'required|date',
        'carrying_value'    => 'nullable|numeric|min:0',
        'selling_price'     => 'required|numeric|min:0',
        'proceeds_received' => 'nullable|numeric|min:0',
        'notes'             => 'nullable|string',
    ]);

    $data['carrying_value']    = $data['carrying_value'] ?? 0;
    $data['proceeds_received'] = $data['proceeds_received'] ?? 0;

    $animalSale->update($data);

    return redirect()->route('animal-sales.index')->with('success', 'Sale updated successfully!');
}

    public function destroy(AnimalSale $animalSale)
    {
        $animalSale->delete();
        return redirect()->route('animal-sales.index')->with('success', 'Sale record deleted successfully!');
    }
}