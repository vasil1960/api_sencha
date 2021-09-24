<?php

namespace App\IagTel;

use Illuminate\Database\Eloquent\Model;

class Dgs extends Model
{
    protected $table = 'nug.podelenia';

    protected $primaryKey = 'ID';

    public function rdg()
    {
        return $this->belongsTo('App\IagTel\Rdg','Pod_Id', 'Glav_Pod');
    }
}
