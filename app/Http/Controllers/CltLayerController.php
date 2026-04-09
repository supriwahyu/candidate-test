<?php

namespace App\Http\Controllers;

use App\Models\CltLayer;
use Illuminate\Http\Request;

class CltLayerController extends Controller
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
            'layup_id' => 'required|exists:clt_layups,id',
            'layer_order' => 'required|string|max:255',
            'thickness' => 'required|string|max:255',
            'width' => 'required|string|max:255',
            'angle' => 'required|string|max:255',
        ]);

        CltLayer::create($data);

        return back()->with('success', 'layer added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CltLayer $cltLayer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CltLayer $cltLayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CltLayer $cltLayer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CltLayer $cltLayer)
    {
        //
    }
}
