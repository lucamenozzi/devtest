<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'team_id',
        'name',
        'last_name',
        'email',
        'phone',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
