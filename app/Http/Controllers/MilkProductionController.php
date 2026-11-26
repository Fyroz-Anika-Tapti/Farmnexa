<?php

namespace App\Http\Controllers;

use App\Models\MilkProduction;
use App\Models\Farm;
use App\Models\Section;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MilkProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = MilkProduction::with(['farm', 'section', 'animal']);

        // Filters
        if ($request->filled('farm_id')) {
            $query->where('farm_id', $request->farm_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->filled('from_date')) {
            $query->where('production_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('production_date', '<=', $request->to_date);
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        $productions = $query->latest('production_date')->latest('id')->paginate(20);

        // Summary
        $summary = MilkProduction::query();
        if ($request->filled('farm_id')) $summary->where('farm_id', $request->farm_id);
        if ($request->filled('section_id')) $summary->where('section_id', $request->section_id);
        if ($request->filled('from_date')) $summary->where('production_date', '>=', $request->from_date);
        if ($request->filled('to_date')) $summary->where('production_date', '<=', $request->to_date);
        if ($request->filled('shift')) $summary->where('shift', $request->shift);

        $totals = [
            'produced' => $summary->sum('produced_liters'),
            'rejected' => $summary->sum('rejected_liters'),
            'saleable' => $summary->sum('saleable_liters'),
        ];

        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')
            ->whereIn('section_type', ['DAIRY', 'SHARED'])
            ->get();

        return view('milk_productions.index', compact('productions', 'totals', 'farms', 'sections'));
    }

    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        $sections = Section::where('status', 'active')
            ->whereIn('section_type', ['DAIRY', 'SHARED'])
            ->get();

        // Active dairy cows
        $animals = Animal::where('status', 'active')
            ->where('animal_type', 'cattle')
            ->where('sex', 'female')
            ->with(['section', 'weights' => function ($q) {
                $q->latest('record_date')->limit(1);
            }])
            ->orderBy('animal_code')
            ->get();

        // Auto shift based on current time
        $currentHour = now()->hour;
        $autoShift = $currentHour < 12 ? 'morning' : 'evening';

        // Today & yesterday stats
        $todayMilk = MilkProduction::where('production_date', today())
            ->selectRaw('SUM(produced_liters) as total, SUM(saleable_liters) as saleable')
            ->first();

        $yesterdayMilk = MilkProduction::where('production_date', today()->subDay())
            ->selectRaw('SUM(produced_liters) as total, SUM(saleable_liters) as saleable')
            ->first();

        $weekMilk = MilkProduction::whereBetween('production_date', [
            today()->subDays(6), today()
        ])->sum('produced_liters');

        // Latest milk record for each animal (last 7 days)
        $lastMilkMap = MilkProduction::whereNotNull('animal_id')
            ->where('production_date', '>=', today()->subDays(7))
            ->orderBy('production_date', 'desc')
            ->get()
            ->groupBy('animal_id')
            ->map(function ($items) {
                return (float) $items->first()->produced_liters;
            })
            ->toArray();

        // Animals that already have entry today
        $alreadyEntered = MilkProduction::where('production_date', today())
            ->whereNotNull('animal_id')
            ->pluck('animal_id')
            ->toArray();

        return view('milk_productions.create', compact(
            'farms', 'sections', 'animals', 'autoShift',
            'todayMilk', 'yesterdayMilk', 'weekMilk',
            'lastMilkMap', 'alreadyEntered'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'         => 'required|exists:farms,id',
            'section_id'      => 'required|exists:sections,id',
            'production_date' => 'required|date',
            'shift'           => 'required|in:morning,evening',
            'entries'         => 'required|array|min:1',
            'entries.*.animal_id'       => 'nullable|exists:animals,id',
            'entries.*.produced_liters' => 'required|numeric|min:0',
            'entries.*.rejected_liters' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        $totalEntries = 0;

        try {
            DB::transaction(function () use ($data, &$totalEntries) {
                foreach ($data['entries'] as $entry) {
                    $produced = (float) $entry['produced_liters'];
                    $rejected = (float) ($entry['rejected_liters'] ?? 0);

                    if ($produced == 0 && $rejected == 0) continue;

                    if ($rejected > $produced) {
                        throw new \Exception("Rejected ({$rejected} L) cannot exceed produced ({$produced} L).");
                    }

                    MilkProduction::create([
                        'farm_id'         => $data['farm_id'],
                        'section_id'      => $data['section_id'],
                        'animal_id'       => $entry['animal_id'] ?? null,
                        'production_date' => $data['production_date'],
                        'shift'           => $data['shift'],
                        'produced_liters' => $produced,
                        'rejected_liters' => $rejected,
                        'saleable_liters' => $produced - $rejected,
                        'notes'           => $data['notes'] ?? null,
                        'recorded_by'     => Auth::id(),
                    ]);

                    $totalEntries++;
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        if ($totalEntries == 0) {
            return back()->withErrors(['entries' => 'No valid entries provided.'])->withInput();
        }

        return redirect()->route('milk-productions.create')
            ->with('success', "{$totalEntries} milk entries saved successfully!");
    }

    public function destroy(MilkProduction $milkProduction)
    {
        $milkProduction->delete();
        return redirect()->route('milk-productions.index')
            ->with('success', 'Production record deleted!');
    }
}