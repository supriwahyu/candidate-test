<?php

namespace App\Http\Controllers;

use App\Models\CltLayup;
use Illuminate\Http\Request;

class CltLayupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'name' => 'required|string|max:255',
        ]);

        CltLayup::create($data);

        return back()->with('success', 'Layup added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $layup = CltLayup::with('layers')->findOrFail($id);
        $layups = CltLayup::all();

        return view('suppliers.layer', compact('layup', 'layups'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CltLayup $cltLayup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CltLayup $cltLayup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $layup = CltLayup::findOrFail($id);

        // delete related layers first (if no cascade)
        $layup->layers()->delete();

        $layup->delete();

        return back()->with('success', 'Layup deleted!');
    }
}
