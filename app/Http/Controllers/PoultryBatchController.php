<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\Farm;
use App\Models\Section;
use App\Models\CostCentre;
use Illuminate\Http\Request;

class PoultryBatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $batches = PoultryBatch::with(['farm', 'section'])->latest()->paginate(10);
        return view('poultry_batches.index', compact('batches'));
    }

    public function create()
    {
        $farms       = Farm::where('status', 'active')->get();
        $sections    = Section::where('status', 'active')->get();
        $costCentres = CostCentre::where('status', 'active')->get();
        return view('poultry_batches.create', compact('farms', 'sections', 'costCentres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'         => 'required|exists:farms,id',
            'section_id'      => 'required|exists:sections,id',
            'cost_centre_id'  => 'nullable|exists:cost_centres,id',
            'batch_code'      => 'required|string|max:60|unique:poultry_batches,batch_code',
            'bird_type'       => 'required|string|max:60',
            'breed'           => 'nullable|string|max:100',
            'opening_birds'   => 'required|integer|min:1',
            'placement_date'  => 'required|date',
            'source_supplier' => 'nullable|string|max:150',
            'status'          => 'required|string|max:30',
            'notes'           => 'nullable|string',
        ]);

        PoultryBatch::create($data);

        return redirect()->route('poultry-batches.index')->with('success', 'Poultry batch created successfully!');
    }

    public function edit(PoultryBatch $poultryBatch)
    {
        $farms       = Farm::where('status', 'active')->get();
        $sections    = Section::where('status', 'active')->get();
        $costCentres = CostCentre::where('status', 'active')->get();
        return view('poultry_batches.edit', compact('poultryBatch', 'farms', 'sections', 'costCentres'));
    }

    public function update(Request $request, PoultryBatch $poultryBatch)
    {
        $data = $request->validate([
            'farm_id'         => 'required|exists:farms,id',
            'section_id'      => 'required|exists:sections,id',
            'cost_centre_id'  => 'nullable|exists:cost_centres,id',
            'batch_code'      => 'required|string|max:60|unique:poultry_batches,batch_code,' . $poultryBatch->id,
            'bird_type'       => 'required|string|max:60',
            'breed'           => 'nullable|string|max:100',
            'opening_birds'   => 'required|integer|min:1',
            'placement_date'  => 'required|date',
            'source_supplier' => 'nullable|string|max:150',
            'status'          => 'required|string|max:30',
            'notes'           => 'nullable|string',
        ]);

        $poultryBatch->update($data);

        return redirect()->route('poultry-batches.index')->with('success', 'Poultry batch updated successfully!');
    }

    public function destroy(PoultryBatch $poultryBatch)
    {
        $poultryBatch->delete();
        return redirect()->route('poultry-batches.index')->with('success', 'Poultry batch deleted successfully!');
    }
}