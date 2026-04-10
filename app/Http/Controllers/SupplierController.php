<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\SupplierRequest;
use App\Imports\SuppliersImport;
use Illuminate\Support\Facades\Log;
use App\Exports\SuppliersExport;

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

        return view('suppliers.show', compact('supplier'));
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
                ->back()
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

    public function import(Request $request, $id)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:xlsx,csv'
        ]);

        // =========================
        // 🔍 PREVIEW MODE
        // =========================
        if ($request->has('preview')) {

            $rows = Excel::toCollection(null, $request->file('file'))->first();

            $tree = [];
            $currentSupplier = null;
            $currentLayup = null;

            foreach ($rows as $index => $row) {

                if ($index === 0) continue;

                $supplier = trim($row[0] ?? '');
                $layup    = trim($row[1] ?? '');
                $layer    = trim($row[2] ?? '');

                if ($supplier) {
                    $currentSupplier = $supplier;
                    $tree[$supplier] = [];
                    continue;
                }

                if ($layup) {
                    $currentLayup = $layup;
                    $tree[$currentSupplier][$layup] = [];
                    continue;
                }

                if ($layer) {
                    $tree[$currentSupplier][$currentLayup][] = $layer;
                }
            }

            return back()
                ->with('preview_tree', $tree)
                ->with('file_temp', $request->file('file')->store('temp'));
        }

        // =========================
        // 🚀 REAL IMPORT
        // =========================
        $path = storage_path('app/private/' . $request->file_temp);
        // dd($path);

        $import = new SuppliersImport();
        Excel::import($import, $path);

        return back()->with('success', 'Import success');
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function export(string $id) 
    {
        return Excel::download(
            new SuppliersExport($id),
            'supplier_'.$id.'_layers.xlsx'
        );
    }
}
