<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialties = Specialty::latest()->paginate(10);
        return view('specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specialties.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:specialties,slug',
            'description' => 'nullable|array',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        Specialty::create($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'Specialty created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('specialties.form', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:100', Rule::unique('specialties')->ignore($specialty->id)],
            'description' => 'nullable|array',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $specialty->update($validated);

        return redirect()->route('specialties.index')
            ->with('success', 'Specialty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        $specialty->delete();

        return redirect()->route('specialties.index')
            ->with('success', 'Specialty deleted successfully.');
    }
}
