<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id'];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    public function dispatchers()
    {
        return $this->belongsToMany(Dispatcher::class, 'dispatcher_manager');
    }
}
