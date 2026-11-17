<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of farms.
     */
    public function index()
    {
        $farms = Farm::latest()->paginate(10);
        return view('farms.index', compact('farms'));
    }

    /**
     * Show the form for creating a new farm.
     */
    public function create()
    {
        return view('farms.create');
    }

    /**
     * Store a newly created farm in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_name'     => 'required|string|max:180',
            'farm_code'     => 'required|string|max:50|unique:farms,farm_code',
            'location'      => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:30',
            'currency_code' => 'required|string|size:3',
            'status'        => 'required|string|max:30',
        ]);

        Farm::create($data);

        return redirect()->route('farms.index')->with('success', 'Farm created successfully!');
    }

    /**
     * Display the specified farm (not used in this simple CRUD).
     */
    public function show(Farm $farm)
    {
        return view('farms.show', compact('farm'));
    }

    /**
     * Show the form for editing the specified farm.
     */
    public function edit(Farm $farm)
    {
        return view('farms.edit', compact('farm'));
    }

    /**
     * Update the specified farm in storage.
     */
    public function update(Request $request, Farm $farm)
    {
        $data = $request->validate([
            'farm_name'     => 'required|string|max:180',
            'farm_code'     => 'required|string|max:50|unique:farms,farm_code,' . $farm->id,
            'location'      => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:30',
            'currency_code' => 'required|string|size:3',
            'status'        => 'required|string|max:30',
        ]);

        $farm->update($data);

        return redirect()->route('farms.index')->with('success', 'Farm updated successfully!');
    }

    /**
     * Remove the specified farm from storage.
     */
    public function destroy(Farm $farm)
    {
        $farm->delete();
        return redirect()->route('farms.index')->with('success', 'Farm deleted successfully!');
    }
}