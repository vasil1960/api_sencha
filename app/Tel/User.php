<?php

namespace App\Tel;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'nugEmpl.empl';

    protected $primaryKey = 'ID';

    public function dlagnost(){
        return $this->hasOne(Dlagnost::class, 'DlagId', 'ID');
    }

}
