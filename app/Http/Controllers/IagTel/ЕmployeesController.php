<?php

namespace App\Http\Controllers\IagTel;

use Illuminate\Http\Request;
use App\IagTel\Empl;
use App\IagTel\Podelenia;
use App\IagTel\Dgs;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ЕmployeesController extends Controller
{
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);
       
       
        $menu = [
            [
                'text'  => 'Изпълнителна агенция',
                'total' => $this->totalempl(1),
                'items' => $this->iag(1),
                // 'leaf'  =>true,
             ],
            [
                'text'  => 'Регионални дирекции',
                'items' => $this->rdg(),
             ],
            [
                'text' => 'Горски, ловни и др. стопанства',
                'items' =>  $this->rdg_dgs()
            ], 
            [
                'text' => 'Търсене',
                'items' => 'blala'
            ], 
            [
                'text' => 'За приложението',
                'items' => 'blala'
            ]
        ];
            
        
        // return ['items'=>$menu];
        return response()->json([

                    'items'=> $menu,
                    // 'limit'  => $this->request->limit,
                    // 'start'  => $this->request->start,
                    // // 'total'  => 118

                ])->setCallback($this->request->input('callback'));
    }

///////////////////////////////////////////////////////////////////////


public function iag()
{
   return $this->empl(1);
    
}

//////////////////////////////////////////////////////////////////

public function rdg()
{
    $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);
    
    
    $rdgs = $rdg->map(function($r)
        {
            $data['text']     = $r->Pod_NameBg;
            $data['total']    = $this->totalempl($r->Pod_Id);
            $data['items']    = $this->empl($r->Pod_Id);

            return $data;
        }
    );
    
    return $rdgs;
    
}

///////////////////////////////////////////////////////////////////
    
public function rdg_dgs()
{
    $rdg = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);
    // dd($rdg);

 return  array_map(function($rdg){

        return 
             [
                'text'  => $rdg['Pod_NameBg'], 
                'total'  => $this->totalempl($rdg['Pod_Id']),
                'items'  => $this->dgs($rdg['Pod_Id']), 
             ];
                         
    }, $rdg->toArray());

    // return $rdgs;
    
}

//////////////////////////////////////////////////////////////////////////////
public function dgs($podid){

    $dgs =  Dgs::where('Glav_Pod', $podid)
        ->whereIn( 'Pod_Type', [3, 6])
        ->get();

    return array_map(function($dgs){
        return [
            
            'total'      => $this->totalempl($dgs['Pod_Id']),
            'text'       => '<div>'.$dgs['Pod_NameBg'] . '</div>'.' ' . '<small>Тел: '. $dgs['Pod_Tel'] . '</small>',
            'start'      => $this->request->start,
            'limit'      => $this->request->limit,
            'items'      => $this->empl($dgs['Pod_Id']),
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
    
    
    
 /////////////////////////////////////////////////////////////////////   
    public function empl($podid){
        

        $empls = Empl::where([
                            // ['Pod_Id', '=', $podid],
                            ['nugEmpl.empl.Statut','=', 1],
                            ['nugEmpl.empl.Pod_Id','=', $podid],
                        ])
                        ->orderBy('nugEmpl.empl.DlagID')
                        ->get();

        $empl = $empls->map(
            function($e)
            {
                $data['text']     = $e->Name . ' ' . $e->Familia;
                $data['dlagnost'] = $e->dlagnost->Dlagnost;
                $data['picture']  = $e->Picture ? $e->Picture : 'noimage.png';
                $data['glav_pod'] = $e->Glav_Pod;

                //$data['limit'] =  $this->request->limit;

                $data['leaf']     = true;
                // $data['total']    = $this->totalempl($e->Pod_Id);
                
                return $data;
            }
        );
        return $empl;
        // return response()->json([
        //     'items'  => $empl,
        //     'limit'  => $this->request->limit,
        //     'start'  => $this->request->start,
        //     'total'  => $this->totalempl($podid)

        // ])->setCallback($this->request->input('callback'));
        

        // $empl = Empl::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.directorate.Directorate', 'nugEmpl.empl.Statut')
        //         ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
        //         ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
        //         ->where([
        //             // ['Pod_Id', '=', $podid],
        //             ['nugEmpl.empl.Statut','=', 1],
        //             ['nugEmpl.empl.Pod_Id','=', $podid],
        //         ])
        //         ->orderBy('nugEmpl.empl.DlagID')
        //         // ->limit($request->limit)
        //         // ->offset($request->start)
        //         ->get();

     

        // return array_map(function($empl){
        //     return [
        //         // 'titla' => $empl['Titla'],
        //         'text'      =>  $this->itemConfig($empl),
        //         // 'fam'   => $empl['Familia'],
        //         'gsm'       => $empl['GSM'] ? $empl['GSM'] : 'Нама въведен телефон',
        //         'email'     => $empl['Email'] ? $empl['Email'] : 'Няма въведен майл',
        //         'egn'       => $empl['EGN'],
        //         'dlagnost'  => $empl['Dlagnost'] ? $empl['Dlagnost'] : 'Няма въведена длъжносг',
        //         'directsia' => $empl['Directorate'] ?  $empl['Directorate'] : 'Няма въведена дирекция',
        //         'glav_pod'  => $empl['Glav_Pod'],
        //         'picture'   => $empl['Picture'] ? $empl['Picture'] : 'noimage.png',
        //         'dgs'       => $empl['Podelenie'],
        //         // 'dlagnist' => $empl['Dlagnost'],

        //         'leaf' => true,
        //     ];
        // }, $empl->toArray());
    }

    private function itemConfig($empl){
        return $empl['Titla'] ? $empl['Titla'].' '.$empl['Name'].' '.$empl['Familia'] : $empl['Name'].' '.$empl['Familia'];
       
   }

   private function totalempl($podid)
   {
   return Empl::where([
        // ['Pod_Id', '=', $podid],
        ['nugEmpl.empl.Statut','=', 1],
        ['nugEmpl.empl.Pod_Id','=', $podid],
    ])
    ->count();
   }

//    private function limit($podid)
//    {
//        return $this->request->limit;
//    }
};


