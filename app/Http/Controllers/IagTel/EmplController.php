<?php

namespace App\Http\Controllers\IagTel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\IagTel\Empl;
use App\IagTel\Podelenia;
// use App\IagTel\Rdg;
use App\IagTel\Dgs;
// use DB;

class EmplController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

////////////////////////////////////////////////////////////////////////////////////////////////

    public function index(Request $request)
    {
        $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);

        $rdgs =  array_map(function($rdg){

            return 
                 [
                    'text'  => $rdg['Pod_NameBg'], 
                    // 'pod_id' => $rdg['Pod_Id'],
                    'items'  => $this->dgs($rdg['Pod_Id']), 
                 ];
                             
        }, $rdg->toArray());
        
        return response()->json(['items' => $rdgs])->setCallback($request->input('callback'));
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////

    public function dgs($podid){

        $dgs =  Dgs::where('Glav_Pod', $podid)
            ->whereIn( 'Pod_Type', [3, 6])
            ->get();

        return array_map(function($dgs){
            return [
                'text'       => '<div>'.$dgs['Pod_NameBg'] . '</div>'.' ' . '<small>Тел: '. $dgs['Pod_Tel'] . '</small>',
                // 'dgs_pod_id' => $dgs['Pod_Id'],
                'items'      => $this->empl($dgs['Pod_Id'])
            ];
        }, $dgs->toArray());

        // foreach ($dgs_ta as $dgs) {
        //     return array (
        //         'text'       => $dgs->Pod_NameBg,
        //         'dgs_pod_id' => $dgs->Pod_Id,
        //         'items'      => $this->empl($dgs->Pod_Id),
        //     );
        // }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    public function empl($podid){
        
        // $empls = Empl::where('Pod_Id', $podid)->get();

        // foreach ($empls as $empl) {
        //     return [
        //         // 'titla'       => $empl->Titla,
        //         'dlagnost'  => $empl->dlagnost->Dlagnost,
        //         'text'      => $empl->Titla ? $empl->Titla . ' ' .$empl->Name .' ' . $empl->Familia : $empl->Name .' ' . $empl->Familia,
        //         'gsm'       => $empl->GSM ? $empl->GSM : 'Нама въведен телефон',
        //         'email'     => $empl->Email ? $empl->Email : 'Няма въведен майл',
        //         'egn'       => $empl->EGN,
        //         'directsia' => $empl->Directorate ? $empl->Directorate : 'Няма въведена дирекция',
        //     ];
        // }

        $empl = Empl::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.directorate.Directorate', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    // ['Pod_Id', '=', $podid],
                    ['nugEmpl.empl.Statut','=', 1],
                    ['nugEmpl.empl.Pod_Id','=', $podid],
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                // ->limit($request->limit)
                // ->offset($request->start)
                ->get();

     

        return array_map(function($empl){
            return [
                // 'titla' => $empl['Titla'],
                'text'      =>  $this->itemConfig($empl),
                // 'fam'   => $empl['Familia'],
                'gsm'       => $empl['GSM'] ? $empl['GSM'] : 'Нама въведен телефон',
                'email'     => $empl['Email'] ? $empl['Email'] : 'Няма въведен майл',
                'egn'       => $empl['EGN'],
                'dlagnost'  => $empl['Dlagnost'] ? $empl['Dlagnost'] : 'Няма въведена длъжносг',
                'directsia' => $empl['Directorate'] ?  $empl['Directorate'] : 'Няма въведена дирекция',
                'glav_pod'  => $empl['Glav_Pod'],
                'picture'   => $empl['Picture'] ? $empl['Picture'] : 'noimage.png',
                'dgs'       => $empl['Podelenie'],
                // 'dlagnist' => $empl['Dlagnost'],

                'leaf' => true,
            ];
        }, $empl->toArray());
    }

    // public function transform($rdg, $dgs)
    // {
    //     return array_map (function($rdg, $dgs)
    //     {
    //         return [
    //             'id'     => $rdg->ID,  
    //             'pod_id' => $rdg['Pod_Id'],
    //             'rdg'    => $rdg['Pod_NameBg'],
    //             'records'    =>[ 
    //                 [
    //                 'dgs' => $dgs['Pod_NameBg'],
    //                 'id'  => $dgs['ID'],
    //                 'records' => [
    //                         [
    //                             'hello' => 'fgfg'
    //                         ]
    //                     ]
    //                 ]
    //             ]
                    
    //         ];

    //     }, $rdg->toArray(), $dgs->toArray());
    // }
    

    // public function vasil()
    // {
    //     $dgs = Podelenia::where('Glav_Pod', 101)->take(5)->get();
    //     return $dgs;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

/////////////////////////////////////////////////////////////////////////////////////////////
private function itemConfig($empl){
     return $empl['Titla'] ? $empl['Titla'].' '.$empl['Name'].' '.$empl['Familia'] : $empl['Name'].' '.$empl['Familia'];
    
}

/////////////////////////////////////////////////////////////////////////////////////////////

    public function create()
    {
        // $empl = Empl::find(10)->get();

        // // return $empl->dlagnost;
        
        // return response()->json([
        //     $dats = $empl->toArray(),
        // ], 200);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
