<?php

namespace App\Http\Controllers\IagTel;

use Illuminate\Http\Request;
use App\IagTel\Empl;
use App\IagTel\Podelenia;
use App\IagTel\Dgs;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ParamEmplController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $param)
    {
        if ($param == 'iag')
        {
            return response()->json([
                    'items'=> $this->iag(),
                    'offset' => $request->start,
                    'limit' => $request->limit,
                    'page'  => $request->page,
                    'total' => $this->count($param=1),
                ])->setCallback($request->input('callback'));
        }

        if ($param == 'rdg')
        {
            return response()->json([
                    'items'=> $this->rdg(),
                    'start' => $request->start,
                    'limit' => $request->limit,
                    'page'  => $request->page,
                ])->setCallback($request->input('callback'));
        }

        if ($param == 'dgs')
        {
            return response()->json([
                'items'=> $this->rdg_dgs()
                ])->setCallback($request->input('callback'));
        }
    }

    public function iag($podid = 1){

        $iag =  Podelenia::where('Pod_Id', $podid)->get();

        return array_map(function($iag){
            return [
                'text'      => $iag['Pod_NameBg'],
                'items'     => $this->empl($iag['Pod_Id']),
                'total'     => $this->count($iag['Pod_Id']),
            ];
        }, $iag->toArray());

    }


    public function rdg()
    {
        $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);

        $rdgs =  array_map(function($rdg){
            
                return [
                    'text'   => $rdg['Pod_NameBg'], 
                    'items'  => $this->empl($rdg['Pod_Id']), 
                    'total'  => $this->count($rdg['Pod_Id']),
                ];
                            
        }, $rdg->toArray());

        return $rdgs;
        
    }

    public function dgs($podid){

        $dgs =  Dgs::where('Glav_Pod', $podid)
            ->whereIn( 'Pod_Type', [3, 6])
            ->get();

        return array_map(function($dgs){
            return [
                'text'      => '<div>'.$dgs['Pod_NameBg'] . '</div>'.' ' . '<small>Тел: '. $dgs['Pod_Tel'] . '</small>',
                'items'     => $this->empl($dgs['Pod_Id']),
                'total'     => $this->count($dgs['Pod_Id']),
            ];
        }, $dgs->toArray());

    }

    public function rdg_dgs()
    {
        $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);
        // dd($rdg);

        $rdgs =  array_map(function($rdg){

            return 
                [
                    'text'   => $rdg['Pod_NameBg'], 
                    'items'  => $this->dgs($rdg['Pod_Id']), 
                    'total'  => $this->count($rdg['Pod_Id']),
                ];
                            
        }, $rdg->toArray());

        return $rdgs;
        
    }



    public function empl($podid){

        $empl = Empl::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.directorate.Directorate', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut','=', 1],
                    ['nugEmpl.empl.Pod_Id','=', $podid],
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->get();
                
        return array_map(function($empl){
            return [
                // 'titla' => $empl['Titla'],
                'text'      => $this->fullName($empl),
                // 'fam'   => $empl['Familia'],
                'gsm'       => $empl['GSM'] ? $empl['GSM'] : 'Нама въведен телефон',
                'email'     => $empl['Email'] ? $empl['Email'] : 'Няма въведен майл',
                'egn'       => $empl['EGN'],
                'dlagnost'  => $empl['Dlagnost'] ? $empl['Dlagnost'] : 'Няма въведена длъжносг',
                'directsia' => $empl['Directorate'] ?  $empl['Directorate'] : 'Няма въведена дирекция',
                'glav_pod'  => $empl['Glav_Pod'],
                'picture'   => $empl['Picture'] ? $empl['Picture'] : 'noimage.png',
                'dgs'       => $empl['Podelenie'],
                'total'     => $this->count($empl['Pod_Id']),

                'leaf' => true,
            ];
        }, $empl->toArray());
    }

    private function fullName($empl)
    {
        return $empl['Titla'] ? $empl['Titla'].' '.$empl['Name'].' '.$empl['Familia'] : $empl['Name'].' '.$empl['Familia'];
    }  
    
    private function count($podid)
    {
        return Empl::where([
            ['nugEmpl.empl.Statut','=', 1],
            ['nugEmpl.empl.Pod_Id','=', $podid],
        ])->count();
    }
}
