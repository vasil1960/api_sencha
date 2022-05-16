<?php

namespace App\IagTel;

use Illuminate\Database\Eloquent\Model;

class Rdg extends Model
{
    protected $table = 'nug.podelenia';

    protected $primaryKey = 'ID';

    public function dgs()
    {
        return $this->hasMany(Dgs::class,'Glav_Pod','Pod_Id');
    }
}
