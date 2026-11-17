<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Farm;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of sections.
     */
    public function index()
    {
        $sections = Section::with('farm')->latest()->paginate(10);
        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        $farms = Farm::where('status', 'active')->get();
        return view('sections.create', compact('farms'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'farm_id'      => 'required|exists:farms,id',
            'section_name' => 'required|string|max:120',
            'section_type' => 'required|string|max:50',
            'description'  => 'nullable|string',
            'status'       => 'required|string|max:30',
        ]);

        Section::create($data);

        return redirect()->route('sections.index')->with('success', 'Section created successfully!');
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        $farms = Farm::where('status', 'active')->get();
        return view('sections.edit', compact('section', 'farms'));
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'farm_id'      => 'required|exists:farms,id',
            'section_name' => 'required|string|max:120',
            'section_type' => 'required|string|max:50',
            'description'  => 'nullable|string',
            'status'       => 'required|string|max:30',
        ]);

        $section->update($data);

        return redirect()->route('sections.index')->with('success', 'Section updated successfully!');
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully!');
    }
}