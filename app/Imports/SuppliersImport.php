<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SuppliersImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    protected $currentSupplier = null;
    protected $currentLayup = null;
    protected $conflicts = [];
    protected $strategy;

    public function __construct($strategy = 'skip')
    {
        $this->strategy = $strategy; // skip | overwrite | merge
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
        ];
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $supplierName = trim($row['supplier_name'] ?? '');
            $layupName    = trim($row['layup_name'] ?? '');
            $layerName    = trim($row['layer_order'] ?? '');

            // 🟦 SUPPLIER
            if ($supplierName) {

                $this->currentSupplier = Supplier::firstOrCreate([
                    'name' => $supplierName,
                ]);

                $this->currentLayup = null;

                continue;
            }

            // 🟨 LAYUP
            if ($layupName) {

                if (!$this->currentSupplier) {
                    $this->conflicts[] = [
                        'row' => $index + 2,
                        'error' => 'Layup without supplier'
                    ];
                    continue;
                }

                $this->currentLayup = CltLayup::firstOrCreate([
                    'supplier_id' => $this->currentSupplier->id,
                    'name' => $layupName,
                ]);

                continue;
            }

            // 🟩 LAYER
            if ($layerName) {

                if (!$this->currentLayup) {
                    $this->conflicts[] = [
                        'row' => $index + 2,
                        'error' => 'Layer without layup'
                    ];
                    continue;
                }

                $exists = CltLayer::where('layup_id', $this->currentLayup->id)
                    ->where('layer_order', $layerName)
                    ->exists();

                if ($exists) {
                    $this->conflicts[] = [
                        'row' => $index + 2,
                        'error' => 'Duplicate layer: ' . $layerName
                    ];
                    continue;
                }

                CltLayer::create([
                    'layup_id' => $this->currentLayup->id,
                    'layer_order' => $layerName,
                    'thickness' => floatval(0),
                    'width' => floatval(0),
                    'angle' => floatval(0),
                ]);
            }
        }
    }

    // 🔥 Get conflicts after import
    public function getConflicts()
    {
        return $this->conflicts;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function rules(): array
    {
        return [
            '*.supplier_name' => 'required',
            '*.layup_name' => 'required',
            '*.layer_order' => 'required',
        ];
    }
}
