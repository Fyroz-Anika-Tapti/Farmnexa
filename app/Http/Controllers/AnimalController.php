<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Farm;
use App\Models\Section;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $animals = Animal::with(['farm', 'section'])->latest()->paginate(10);
        return view('animals.index', compact('animals'));
    }

    public function create()
    {
        $farms    = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        return view('animals.create', compact('farms', 'sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'        => 'required|exists:farms,id',
            'section_id'     => 'required|exists:sections,id',
            'cost_centre_id' => 'nullable|integer',
            'animal_code'    => 'required|string|max:60|unique:animals,animal_code',
            'animal_type'    => 'required|string|max:50',
            'breed'          => 'nullable|string|max:100',
            'sex'            => 'nullable|string|max:20',
            'date_of_birth'  => 'nullable|date',
            'purchase_date'  => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'source_type'    => 'nullable|string|max:30',
            'status'         => 'required|string|max:30',
            'notes'          => 'nullable|string',
        ]);

        Animal::create($data);

        return redirect()->route('animals.index')->with('success', 'Animal created successfully!');
    }

    public function edit(Animal $animal)
    {
        $farms    = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        return view('animals.edit', compact('animal', 'farms', 'sections'));
    }

    public function update(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'farm_id'        => 'required|exists:farms,id',
            'section_id'     => 'required|exists:sections,id',
            'cost_centre_id' => 'nullable|integer',
            'animal_code'    => 'required|string|max:60|unique:animals,animal_code,' . $animal->id,
            'animal_type'    => 'required|string|max:50',
            'breed'          => 'nullable|string|max:100',
            'sex'            => 'nullable|string|max:20',
            'date_of_birth'  => 'nullable|date',
            'purchase_date'  => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'source_type'    => 'nullable|string|max:30',
            'status'         => 'required|string|max:30',
            'notes'          => 'nullable|string',
        ]);

        $animal->update($data);

        return redirect()->route('animals.index')->with('success', 'Animal updated successfully!');
    }

    public function destroy(Animal $animal)
    {
        $animal->delete();
        return redirect()->route('animals.index')->with('success', 'Animal deleted successfully!');
    }
}