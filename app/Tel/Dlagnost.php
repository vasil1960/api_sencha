<?php

namespace App\Tel;

use Illuminate\Database\Eloquent\Model;

class Dlagnost extends Model
{
    protected $table = 'nugEmpl.dlagnosti';

    protected $primaryKey = 'ID';

    public function user(){
        return $this->belongsTo('App\Tel\User');
    }

}
