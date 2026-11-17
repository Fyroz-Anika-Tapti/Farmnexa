<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalHealthEvent;
use Illuminate\Http\Request;

class AnimalHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Animal $animal)
    {
        $events = $animal->healthEvents()->latest('event_date')->paginate(15);
        return view('animal_health.index', compact('animal', 'events'));
    }

    public function create(Animal $animal)
    {
        return view('animal_health.create', compact('animal'));
    }

    public function store(Request $request, Animal $animal)
    {
        $data = $request->validate([
            'event_type'             => 'required|string|max:50',
            'event_date'             => 'required|date',
            'diagnosis'              => 'nullable|string|max:255',
            'treatment_description'  => 'nullable|string',
            'medicine_cost'          => 'nullable|numeric|min:0',
            'veterinarian_name'      => 'nullable|string|max:150',
            'next_follow_up_date'    => 'nullable|date|after_or_equal:event_date',
        ]);

        $data['animal_id'] = $animal->id;
        $data['medicine_cost'] = $data['medicine_cost'] ?? 0;

        AnimalHealthEvent::create($data);

        return redirect()->route('animals.health.index', $animal)
            ->with('success', 'Health event added successfully!');
    }

    public function destroy(AnimalHealthEvent $event)
    {
        $animalId = $event->animal_id;
        $event->delete();

        return redirect()->route('animals.health.index', $animalId)
            ->with('success', 'Health event deleted successfully!');
    }
}