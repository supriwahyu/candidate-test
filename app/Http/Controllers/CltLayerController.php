<?php

namespace App\Http\Controllers;

use App\Models\CltLayer;
use Illuminate\Http\Request;

class CltLayerController extends Controller
{
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'layer_order' => 'required|string|max:255',
            'thickness' => 'required|string|max:255',
            'width' => 'required|string|max:255',
            'angle' => 'required|string|max:255',
        ]);

        $layer = CltLayer::findOrFail($id);

        $layer->update($data);

        return back()->with('success', 'layer updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $layer = CltLayer::findOrFail($id);

        $layer->delete();

        return back()->with('success', 'Layup deleted!');
    }
}
