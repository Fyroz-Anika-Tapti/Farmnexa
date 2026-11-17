<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalWeight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnimalWeightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // নির্দিষ্ট Animal-এর সব ওজন দেখাবে
    public function index(Animal $animal)
    {
        $weights = $animal->weights()->latest('record_date')->paginate(15);
        return view('animal_weights.index', compact('animal', 'weights'));
    }

    // নতুন ওজন যোগ করার ফর্ম
    public function create(Animal $animal)
    {
        return view('animal_weights.create', compact('animal'));
    }

    public function store(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'record_date' => 'required|date',
            'weight_kg'   => 'required|numeric|min:0',
            'notes'       => 'nullable|string|max:255',
        ]);

        $data['animal_id'] = $animal->id;
        $data['recorded_by'] = Auth::id(); // যে ইউজার যোগ করছে তার ID

        AnimalWeight::create($data);

        return redirect()->route('animals.weights.index', $animal)
            ->with('success', 'Weight record added successfully!');
    }

    public function destroy(AnimalWeight $weight)
    {
        $animalId = $weight->animal_id;
        $weight->delete();

        return redirect()->route('animals.weights.index', $animalId)
            ->with('success', 'Weight record deleted successfully!');
    }
}