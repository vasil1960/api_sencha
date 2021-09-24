<?php

namespace App\IagTel;

use Illuminate\Database\Eloquent\Model;

class Empl extends Model
{
    protected $table = 'nugEmpl.empl';

    protected $primaryKey = 'ID';

    public function dlagnost(){
        return $this->hasOne('App\IagTel\Dlagnost', 'ID', 'DlagID');
    }

}
