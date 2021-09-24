<?php

namespace App\IagTel;

use Illuminate\Database\Eloquent\Model;

class Dlagnost extends Model
{
    protected $table = 'nugEmpl.dlagnosti';

    protected $primaryKey = 'ID';

    // public function empl(){
    //     return $this->hasMany('App\IagTel\Empl', 'ID', 'ID');
    // }
}
