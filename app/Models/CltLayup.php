<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CltLayup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'name',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function layers()
    {
        return $this->hasMany(CltLayer::class, 'layup_id', 'id');
    }
}
