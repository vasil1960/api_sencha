<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    protected $table = 'u_names';

    protected $primaryKey = 'ID';

    public function kgm()
    {
        return $this->belongsTo(Kgm::class,'NamesID','ID');
    }

}
