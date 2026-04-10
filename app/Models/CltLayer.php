<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CltLayer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'layup_id',
        'layer_order',
        'thickness',
        'width',
        'angle',
    ];

    public function layup()
    {
        return $this->belongsTo(CltLayup::class);
    }
}
