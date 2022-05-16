<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Podelenia;

use App\Http\Requests;

class PodeleniaController extends Controller
{
    //
    public function index() 
    {
        // return 'podelenia';
        $pods = Podelenia::where('Pod_Type', '222')->orderBy('ID','DESC')->paginate(50);
        return view('pod', [
            'pods' => $pods
        ]);
    }
}
