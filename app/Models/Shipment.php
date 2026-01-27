<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function referents()
    {
        return $this->belongsToMany(Referent::class, 'referent_shipment', 'shipment_id')->withPivot('id','scope');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
