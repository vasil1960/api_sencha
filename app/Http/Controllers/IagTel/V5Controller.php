<?php

namespace App\Http\Controllers\IagTel;

use Illuminate\Http\Request;
use App\IagTel\Empl;
use App\IagTel\Podelenia;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class V5Controller extends Controller
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
    
    ///////////////////////////////////////////////////////
     public function index(Request $request)
    {
       
       $glavpods = Podelenia::whereIn('Pod_Level', [1,2])
            // ->skip($request->start)
            ->get();

        // dd($glavpods);
       
       $glavpod = $glavpods->map(

           function($p)
           {
                $data['text'] = $p->Pod_NameBg;
                $data['items'] = $this->empl($p->Pod_Id);

                return $data;
           }

       );

       if($glavpods)
       {
           return response()->json([

                // 'total' => $glavpods->count(),     
                'items' => $glavpod,
                // 'start' => $request->start,
                // 'limit' => $request->limit, 

            ])->setCallback($request->input('callback'));
       };
      
    }

    /////////////////////////////////////////////////////

    public function empl($podid)
    {
        $empls = Empl::where([['Pod_Id', $podid],['Statut', 1]])
            // ->skip($this->request->start)
            // ->take(15)
            ->get();

        $empl = $empls->map(function($e)
        {
            
            // $data['start'] = $this->request->start;
            // $data['limit'] = $this->request->limit;
            // $data['total'] = $this->total($e->Pod_Id);
            $data['text'] = $e->Name . ' ' . $e->Familia; 

            return $data;
        });

        return $empl;
    }

    //////////////////////////////////////////////////////////

    public function total($podid)
    {
        return  Empl::where([['Pod_Id', $podid],['Statut', 1]])->count();
    }

    /////////////////////////////////////////////////////////////////////
}
