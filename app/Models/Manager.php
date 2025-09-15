<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id'];

    // Veza s lokacijom (1:n)
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Veza s dispatcherima (m:n preko pivot tablice)
    public function dispatchers()
    {
        return $this->belongsToMany(Dispatcher::class, 'dispatcher_manager');
    }
}
