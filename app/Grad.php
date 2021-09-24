<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grad extends Model
{
    protected $table = 'regions.PopulatedPlaces';
    protected $primaryKey = 'ID';

    
    public function address()
    {
        return $this->belongsTo('App\Address', 'ID', 'Grad');
    }
    
}
