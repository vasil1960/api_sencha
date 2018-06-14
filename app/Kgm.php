<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kgm extends Model
{
    public $table = 'u_kgm';
    public $primaryKey = 'ID';

    public function name()
    {
        return $this->hasOne(Name::class,'ID','NamesID');
    }
}
