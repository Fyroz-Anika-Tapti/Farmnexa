<?php

namespace App\Http\Controllers;

use App\Models\MilkProcessing;
use App\Models\Farm;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilkProcessingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $processings = MilkProcessing::with(['farm', 'section'])
            ->latest('processing_date')->paginate(15);
        return view('milk_processings.index', compact('processings'));
    }

    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')
            ->whereIn('section_type', ['DAIRY', 'SHARED'])
            ->get();
        return view('milk_processings.create', compact('farms', 'sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'                => 'required|exists:farms,id',
            'section_id'             => 'nullable|exists:sections,id',
            'processing_date'        => 'required|date',
            'product_name'           => 'required|string|max:100',
            'input_milk_liters'      => 'required|numeric|min:0',
            'output_quantity'        => 'required|numeric|min:0',
            'unit'                   => 'required|string|max:20',
            'processing_cost'        => 'nullable|numeric|min:0',
            'selling_price_per_unit' => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string',
        ]);

        $data['processing_cost'] = $data['processing_cost'] ?? 0;
        $data['recorded_by'] = Auth::id();

        MilkProcessing::create($data);

        return redirect()->route('milk-processings.index')
            ->with('success', 'Milk processing recorded successfully!');
    }

    public function destroy(MilkProcessing $milkProcessing)
    {
        $milkProcessing->delete();
        return redirect()->route('milk-processings.index')
            ->with('success', 'Processing record deleted successfully!');
    }
}