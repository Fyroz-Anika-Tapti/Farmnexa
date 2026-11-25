<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnimalExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Animal $animal)
    {
        $expenses = $animal->expenses()->latest('expense_date')->paginate(15);
        return view('animal_expenses.index', compact('animal', 'expenses'));
    }

    public function create(Animal $animal)
    {
        return view('animal_expenses.create', compact('animal'));
    }

    public function store(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'expense_date' => 'required|date',
            'expense_type' => 'required|string|max:50',
            'amount'       => 'required|numeric|min:0.01',
            'description'  => 'nullable|string',
        ]);

        $data['animal_id']   = $animal->id;
        $data['recorded_by'] = Auth::id();

        AnimalExpense::create($data);

        return redirect()->route('animals.expenses.index', $animal)
            ->with('success', 'Expense recorded!');
    }

    public function destroy(AnimalExpense $expense)
    {
        $animalId = $expense->animal_id;
        $expense->delete();
        return redirect()->route('animals.expenses.index', $animalId)
            ->with('success', 'Expense deleted!');
    }
    public function page()
{
    $animals = Animal::where('status', 'active')
        ->orderBy('animal_code')
        ->get();

    $expenseTypes = [
        'Labor'        => 'Labor (শ্রমিক)',
        'Transport'    => 'Transport (পরিবহন)',
        'Utility'      => 'Utility (বিদ্যুৎ/পানি)',
        'Rent'         => 'Rent (ভাড়া)',
        'Maintenance'  => 'Maintenance (মেরামত)',
        'Veterinary'   => 'Veterinary (চিকিৎসা)',
        'Other'        => 'Other (অন্যান্য)',
    ];

    return view('animal_expenses.page', compact('animals', 'expenseTypes'));
}

public function getAnimalInfo(Animal $animal)
{
    $animal->load(['farm', 'section', 'sale']);

    return response()->json([
        'id'                => $animal->id,
        'animal_code'       => $animal->animal_code,
        'animal_type'       => $animal->animal_type,
        'breed'             => $animal->breed,
        'sex'               => $animal->sex,
        'farm'              => $animal->farm->farm_name ?? '-',
        'section'           => $animal->section->section_name ?? '-',
        'purchase_value'    => (float) ($animal->purchase_value ?? 0),
        'total_feed_cost'   => (float) $animal->total_feed_cost,
        'total_medicine_cost' => (float) $animal->total_medicine_cost,
        'total_other_expense' => (float) $animal->total_other_expense,
        'total_cost'        => (float) $animal->total_cost,
        'expenses'          => $animal->expenses()->latest('expense_date')->get()->map(function ($e) {
            return [
                'id'            => $e->id,
                'expense_date'  => $e->expense_date->format('d M, Y'),
                'expense_type'  => $e->expense_type,
                'amount'        => (float) $e->amount,
                'description'   => $e->description,
            ];
        }),
    ]);
}

public function storeAjax(Request $request, Animal $animal)
{
    $data = $request->validate([
        'expense_date' => 'required|date',
        'expenses'     => 'required|array|min:1',
        'expenses.*.type'        => 'required|string|max:50',
        'expenses.*.amount'      => 'required|numeric|min:0.01',
        'expenses.*.description' => 'nullable|string',
    ]);

    $savedCount = 0;

    try {
        DB::transaction(function () use ($data, $animal, &$savedCount) {
            foreach ($data['expenses'] as $exp) {
                AnimalExpense::create([
                    'animal_id'    => $animal->id,
                    'expense_date' => $data['expense_date'],
                    'expense_type' => $exp['type'],
                    'amount'       => $exp['amount'],
                    'description'  => $exp['description'] ?? null,
                    'recorded_by'  => Auth::id(),
                ]);
                $savedCount++;
            }
        });
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }

    return response()->json([
        'success' => true,
        'message' => "{$savedCount} expense(s) saved successfully!",
    ]);
}
}