<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function referents()
    {
        return $this->belongsToMany(
            Referent::class,
            'referent_team',
            'team_id',
            'referent_id'
        )->withTimestamps();
    }
}
