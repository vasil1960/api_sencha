<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'u_address';
    protected $primaryKey = 'ID';

    
    public function name()
    {
        return $this->belongsTo('App\Name', 'NamesID', 'ID');
    }
    
    
    public function grad()
    {
        return $this->hasOne('App\Grad', 'ID', 'Grad');
    }
    
}
