<?php

namespace App\Http\Controllers\IagTel;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\IagTel\Empl;
use App\IagTel\Podelenia;
use App\IagTel\Dgs;

class V4Controller extends Controller
{
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->podid = $podid;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return $this->iag();
    }

/////////////////////////////////////////////////////////////////////////

    public function iag()
    {
        $iags = Podelenia::where('Pod_id', 1)->get();

        $iag = $iags->map(
            function ($iags)
                {
                    $data['text']   = $iags->Pod_NameBg;
                    $data['items']  = $this->rdg();
                    // $data['total']  = $iags->count();


                    return $data;
                }
        );
        // return $iag;       
        return response()->json([
            'items'  => $iag,
            // 'limit'  => $this->request->limit,
            // 'start'  => $this->request->start,
            // 'page'   => $this->request->page,
            'total'  => $this->emplcount(1),
        ])
        ->setCallback($this->request->input('callback'));
    }

////////////////////////////////////////////////////////////////////

    public function rdg()
    {
        $rdgs = Podelenia::where([['ID', '>=', 2], ['ID', '<=', 17]])->get(['Pod_NameBg','Glav_Pod', 'Pod_Id', 'ID']);

        $rdg = $rdgs->map(
            function($r)
            {
                $data['text']  = $r->Pod_NameBg;
                $data['items'] = $this->dgs($r->Pod_Id);
                $data['total'] = $this->emplcount($r->Pod_Id);
                $data['limit'] = $this->request->limit;
                $data['start'] = $this->request->start;
                $data['page'] = $this->request->page;
                return $data;
            }
           
        );

        return $rdg;
        // return response()->json([
        //     'items'  => $rdg,
        //     // 'limit'  => $this->request->limit,
        //     // 'start'  => $this->request->start,
        //     // 'page'   => $this->request->page,
        //     // 'total'  => $rdgs->count(),
        // ])
        // ->setCallback($this->request->input('callback'));
    }   

//////////////////////////////////////////////////////////////////////////

    public function dgs($podid)
    {
        $dgsta =  Dgs::where('Glav_Pod', $podid)
        ->whereIn( 'Pod_Type', [3, 6])
        ->get();

        $dgs = $dgsta->map(
            function($d)
            {
                $data['text']  = $d->Pod_NameBg;
                $data['items'] = $this->empl($d->Pod_Id);
                $data['total'] = $this->emplcount($d->Pod_Id);
                $data['limit'] = $this->request->limit;
                $data['start'] = $this->request->start;
                $data['page'] = $this->request->page;

                return $data;
            }
            
        ); 

        return $dgs;
        // return response()->json([
        //     'items' => $dgs,
        //     // 'limit'  => $this->request->limit,
        //     // 'start'  => $this->request->start,
        //     // 'page'   => $this->request->page,
        //     // 'total'  => $dgs->count(),
        // ])
        // ->setCallback($this->request->input('callback'));

    }
    
    
    
//////////////////////////////////////////////////////////////////////////////////

    public function empl($podid)
    {
        $empls = Empl::where( [
            ['nugEmpl.empl.Pod_Id','=', $podid],
            ['nugEmpl.empl.Statut','=', 1]
        ])
        ->get();

    $empl = $empls->map( 
                function ($empls)
                {
                    $data['text']     = $empls->Name . ' ' . $empls->Familia;
                    // $data['total'] = $this->emplcount($empls->Pod_Id);
                    // $data['egn']      = $empls->EGN;
                    // $data['pod']      = $empls->Podelenie;
                    // $data['dlagnost'] = $empls->dlagnost->Dlagnost;
                    // $data['limit']    = $this->request->limit;
                    // $data['start']    = $this->request->start;
                    // $data['page']     = $this->request->page;

                    return $data;
                }
            );

            return $empl;
    
    // return response()->json([
    //         'items'  => $empl,
    //         'limit'  => $this->request->limit,
    //         'start'  => $this->request->start,
    //         'page'   => $this->request->page,
    //         'total'  => $this->emplcount($podid),
    // ])
    //     ->setCallback($this->request->input('callback'));
    }

    private function emplcount($podid)
    {
        $empls = Empl::where( [
            ['nugEmpl.empl.Pod_Id','=', $podid],
            ['nugEmpl.empl.Statut','=', 1]
        ])
        ->count();

        return $empls;
    }

}
