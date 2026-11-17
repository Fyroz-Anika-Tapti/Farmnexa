<?php

namespace App\Http\Controllers;

use App\Models\PoultryBatch;
use App\Models\PoultryDailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoultryDailyActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // নির্দিষ্ট Batch-এর সব দৈনিক হিসাব
    public function index(PoultryBatch $batch)
    {
        $activities = $batch->dailyActivities()->latest('activity_date')->paginate(15);
        return view('poultry_daily_activities.index', compact('batch', 'activities'));
    }

    public function create(PoultryBatch $batch)
    {
        // আজকের দিনের আগের ডেটা বের করে Opening Birds বের করা
        $latestActivity = $batch->dailyActivities()->latest('activity_date')->first();
        $openingBirds = $latestActivity ? $latestActivity->closing_birds : $batch->opening_birds;
        
        return view('poultry_daily_activities.create', compact('batch', 'openingBirds'));
    }

    public function store(Request $request, PoultryBatch $batch)
    {
        $data = $request->validate([
            'activity_date' => 'required|date',
            'mortality'     => 'required|integer|min:0',
            'culling'       => 'required|integer|min:0',
            'transfer_in'   => 'required|integer|min:0',
            'transfer_out'  => 'required|integer|min:0',
            'notes'         => 'nullable|string',
        ]);

        // Opening Birds বের করা
        $latestActivity = $batch->dailyActivities()->latest('activity_date')->first();
        $openingBirds = $latestActivity ? $latestActivity->closing_birds : $batch->opening_birds;

        // Business Rule: Closing = Opening - Mortality - Culling + Transfer In - Transfer Out
        $closingBirds = $openingBirds - $data['mortality'] - $data['culling'] + $data['transfer_in'] - $data['transfer_out'];

        if ($closingBirds < 0) {
            return back()->withErrors(['mortality' => 'Closing birds cannot be negative. Check your numbers.'])->withInput();
        }

        $data['batch_id'] = $batch->id;
        $data['closing_birds'] = $closingBirds;
        $data['recorded_by'] = Auth::id();

        PoultryDailyActivity::create($data);

        return redirect()->route('poultry-batches.daily.index', $batch)
            ->with('success', 'Daily activity recorded successfully!');
    }

    public function destroy(PoultryDailyActivity $activity)
    {
        $batchId = $activity->batch_id;
        $activity->delete();

        return redirect()->route('poultry-batches.daily.index', $batchId)
            ->with('success', 'Daily activity deleted successfully!');
    }
}