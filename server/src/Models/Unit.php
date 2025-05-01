<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'square_meters',
        'bedroom_count',
        'condominium_id'
    ];

    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }
}