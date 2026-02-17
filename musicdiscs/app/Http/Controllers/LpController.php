<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lp;

class LpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lps = Lp::all();
        return view('lps.index', compact('lps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'album' => 'required',
            'artist' => 'required',
            'release_year' => 'required|integer',
            'price' => 'required|integer',
            'genre' => 'required',
            'status' => 'required',
            'in_stock' => 'required',
            'cover_image' => 'nullable|image',
            'number_of_tracks' => 'required|integer',
        ]);

        Lp::create($validated);

        return redirect()->route('lps.index')
            ->with('success', 'LP created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lp = Lp::findOrFail($id);
        return view('lps.show', compact('lp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lp = Lp::findOrFail($id);
        return view('lps.edit', compact('lp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'album' => 'required',
            'artist' => 'required',
            'release_year' => 'required|integer',
            'price' => 'required|integer',
            'genre' => 'required',
            'status' => 'required',
            'in_stock' => 'required',
            'cover_image' => 'nullable|image',
            'number_of_tracks' => 'required|integer',
        ]);


        $lp = Lp::findOrFail($id);
        $lp->update($validated);

        return redirect()->route('lps.index')
            ->with('success', 'LP updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lp = Lp::findOrFail($id);
        $lp->delete();

        return redirect()->route('lps.index')->with('success', 'LP deleted successfully.');
    }
}
