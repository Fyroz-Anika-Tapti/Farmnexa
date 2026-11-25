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
        'animal_id'         => 'required|exists:animals,id',
        'customer_name'     => 'required|string|max:150',
        'customer_phone'    => 'nullable|string|max:30',
        'sale_date'         => 'required|date',
        'profit_percentage' => 'nullable|numeric',
        'selling_price'     => 'required|numeric|min:0',
        'proceeds_received' => 'nullable|numeric|min:0',
        'notes'             => 'nullable|string',
    ]);

    $animal = Animal::findOrFail($data['animal_id']);

    if ($animal->status === 'sold') {
        return back()->withErrors(['animal_id' => 'This animal is already sold.'])->withInput();
    }

    // Carrying Value auto-calculate
    $carryingValue = $animal->total_cost;

    $data['carrying_value']    = $carryingValue;
    $data['proceeds_received'] = $data['proceeds_received'] ?? 0;
    $data['status']            = 'completed';
    $data['profit_amount']     = $data['selling_price'] - $carryingValue;

    \DB::transaction(function () use ($data, $animal) {
        AnimalSale::create($data);
        $animal->update(['status' => 'sold']);
    });

    return redirect()->route('animal-sales.show', AnimalSale::latest('id')->first())
        ->with('success', 'Animal sold successfully!');
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

    public function getCarryingValue(Animal $animal)
{
    $animal->load(['sale']);

    if ($animal->status === 'sold') {
        return response()->json(['error' => 'This animal is already sold.'], 400);
    }

    return response()->json([
        'animal_code'        => $animal->animal_code,
        'animal_type'        => $animal->animal_type,
        'breed'              => $animal->breed,
        'purchase_value'     => (float) ($animal->purchase_value ?? 0),
        'feed_cost'          => (float) $animal->total_feed_cost,
        'medicine_cost'      => (float) $animal->total_medicine_cost,
        'other_expense'      => (float) $animal->total_other_expense,
        'carrying_value'     => (float) $animal->total_cost,
    ]);
}

public function show(AnimalSale $animalSale)
{
    $animalSale->load(['animal.farm', 'animal.section']);
    return view('animal_sales.show', compact('animalSale'));
}

public function invoice(AnimalSale $animalSale)
{
    $animalSale->load(['animal.farm', 'animal.section']);
    return view('animal_sales.invoice', compact('animalSale'));
}

}