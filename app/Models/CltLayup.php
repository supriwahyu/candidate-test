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
}
