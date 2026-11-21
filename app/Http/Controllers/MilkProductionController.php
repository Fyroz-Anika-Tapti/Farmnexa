<?php

namespace App\Http\Controllers;

use App\Models\MilkProduction;
use App\Models\Farm;
use App\Models\Section;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilkProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productions = MilkProduction::with(['farm', 'section', 'animal'])->latest('production_date')->paginate(15);
        return view('milk_productions.index', compact('productions'));
    }

    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        $animals = Animal::where('status', 'active')->get();
        return view('milk_productions.create', compact('farms', 'sections', 'animals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'         => 'required|exists:farms,id',
            'section_id'      => 'required|exists:sections,id',
            'animal_id'       => 'nullable|exists:animals,id',
            'production_date' => 'required|date',
            'shift'           => 'required|in:morning,evening',
            'produced_liters' => 'required|numeric|min:0',
            'rejected_liters' => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        // Business Rule: Saleable = Produced - Rejected
        $data['saleable_liters'] = $data['produced_liters'] - $data['rejected_liters'];

        if ($data['saleable_liters'] < 0) {
            return back()->withErrors(['rejected_liters' => 'Rejected cannot be more than produced.'])->withInput();
        }

        $data['recorded_by'] = Auth::id();

        MilkProduction::create($data);

        return redirect()->route('milk-productions.index')->with('success', 'Milk production recorded successfully!');
    }

    public function destroy(MilkProduction $milkProduction)
    {
        $milkProduction->delete();
        return redirect()->route('milk-productions.index')->with('success', 'Production record deleted successfully!');
    }
}