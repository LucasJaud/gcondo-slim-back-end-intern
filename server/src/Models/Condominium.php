<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condominium extends Model
{
    protected $table = 'condominiums';

    protected $fillable = [
        'name',
        'zip_code',
        'url'
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}