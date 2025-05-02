<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'name',
        'guest_count',
        'date',
        'venue_id'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}