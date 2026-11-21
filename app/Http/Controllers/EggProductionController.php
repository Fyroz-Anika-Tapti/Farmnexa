<?php

namespace App\Http\Controllers;

use App\Models\EggProduction;
use App\Models\Farm;
use App\Models\Section;
use App\Models\PoultryBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EggProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productions = EggProduction::with(['farm', 'section', 'batch'])
            ->latest('production_date')->paginate(15);
        return view('egg_productions.index', compact('productions'));
    }

    public function create()
    {
        $farms   = Farm::where('status', 'active')->get();
        // শুধু Poultry সেকশন দেখাব
        $sections = Section::where('status', 'active')
            ->whereIn('section_type', ['POULTRY', 'SHARED'])
            ->get();
        $batches = PoultryBatch::where('status', 'active')->get();
        return view('egg_productions.create', compact('farms', 'sections', 'batches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'         => 'required|exists:farms,id',
            'section_id'      => 'required|exists:sections,id',
            'batch_id'        => 'nullable|exists:poultry_batches,id',
            'production_date' => 'required|date',
            'shift'           => 'required|in:morning,afternoon,evening',
            'produced_eggs'   => 'required|integer|min:0',
            'rejected_eggs'   => 'required|integer|min:0',
            'total_weight_kg' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        // Business Rule: Saleable = Produced - Rejected
        $data['saleable_eggs'] = $data['produced_eggs'] - $data['rejected_eggs'];

        if ($data['saleable_eggs'] < 0) {
            return back()->withErrors(['rejected_eggs' => 'Rejected eggs cannot be more than produced eggs.'])->withInput();
        }

        $data['recorded_by'] = Auth::id();

        EggProduction::create($data);

        return redirect()->route('egg-productions.index')->with('success', 'Egg production recorded successfully!');
    }

    public function destroy(EggProduction $eggProduction)
    {
        $eggProduction->delete();
        return redirect()->route('egg-productions.index')->with('success', 'Production record deleted successfully!');
    }
}