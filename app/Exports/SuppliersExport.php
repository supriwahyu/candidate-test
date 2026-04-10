<?php

namespace App\Exports;

use App\Models\Supplier;
use App\Models\CltLayer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    protected $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rows = [];

        $supplier = Supplier::with('layups.layers')
            ->findOrFail($this->supplierId);

        // 🔹 Supplier Row
        $rows[] = [
            'supplier_name' => $supplier->name,
            'layup_name'    => '',
            'layer_order'    => '',
        ];

        foreach ($supplier->layups as $layup) {

            // 🔹 Layup Row
            $rows[] = [
                'supplier_name' => '',
                'layup_name'    => $layup->name,
                'layer_order'    => '',
            ];

            foreach ($layup->layers as $layer) {

                // 🔹 Layer Row
                $rows[] = [
                    'supplier_name' => '',
                    'layup_name'    => '',
                    'layer_order'    => $layer->layer_order,
                ];
            }
        }

        return collect($rows);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'supplier_name',
            'layup_name',
            'layer_order',
        ];
    }
}
