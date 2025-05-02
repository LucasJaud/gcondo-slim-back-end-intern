<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class Venue extends Model{

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'maximum_occupancy',
        'square_meters',
        'condominium_id'
    ];

    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }

}