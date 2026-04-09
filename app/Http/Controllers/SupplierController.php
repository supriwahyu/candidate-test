<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $search = $request->query('search');

            $suppliers = DB::table('suppliers')
                ->when($search, function (Builder $query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->orWhere('suppliers.name', 'like', "%{$search}%");
                    });
                })
                ->orderBy('suppliers.created_at', 'desc')
                ->paginate((int) $perPage);

            return view('suppliers.index', compact('suppliers'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'name'  => 'required|string|max:20',
            ]);

            $item = Supplier::create([
                'name'  => $data['name'],
            ]);

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to create supplier.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::with('layups')->findOrFail($id);
        $suppliers = Supplier::all();

        return view('suppliers.show', compact('supplier', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'name'  => 'required|string|max:20',
            ]);

            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'name'  => $data['name'],
            ]);

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to create supplier.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $supplier = Supplier::findOrFail($id);

            // Get layup IDs
            $layupIds = CltLayup::where('supplier_id', $supplier->id)
                ->pluck('id');

            // Delete layers
            CltLayer::whereIn('layup_id', $layupIds)->delete();

            // Delete layups
            CltLayup::whereIn('id', $layupIds)->delete();

            // Delete supplier
            $supplier->delete();

            DB::commit();

            return redirect()
                ->route('suppliers.index')
                ->with('success', 'Supplier created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to create supplier.')
                ->withInput();
        }
    }
}
