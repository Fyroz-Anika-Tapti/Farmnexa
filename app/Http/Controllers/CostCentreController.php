<?php

namespace App\Http\Controllers;

use App\Models\CostCentre;
use App\Models\Farm;
use App\Models\Section;
use Illuminate\Http\Request;

class CostCentreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $costCentres = CostCentre::with(['farm', 'section'])->latest()->paginate(10);
        return view('cost_centres.index', compact('costCentres'));
    }

    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        return view('cost_centres.create', compact('farms', 'sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'     => 'required|exists:farms,id',
            'section_id'  => 'nullable|exists:sections,id',
            'centre_code' => 'required|string|max:50|unique:cost_centres,centre_code',
            'centre_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status'      => 'required|string|max:30',
        ]);

        CostCentre::create($data);

        return redirect()->route('cost-centres.index')->with('success', 'Cost Centre created successfully!');
    }

    public function edit(CostCentre $costCentre)
    {
        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();
        return view('cost_centres.edit', compact('costCentre', 'farms', 'sections'));
    }

    public function update(Request $request, CostCentre $costCentre)
    {
        $data = $request->validate([
            'farm_id'     => 'required|exists:farms,id',
            'section_id'  => 'nullable|exists:sections,id',
            'centre_code' => 'required|string|max:50|unique:cost_centres,centre_code,' . $costCentre->id,
            'centre_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'status'      => 'required|string|max:30',
        ]);

        $costCentre->update($data);

        return redirect()->route('cost-centres.index')->with('success', 'Cost Centre updated successfully!');
    }

    public function destroy(CostCentre $costCentre)
    {
        $costCentre->delete();
        return redirect()->route('cost-centres.index')->with('success', 'Cost Centre deleted successfully!');
    }
}