<?php

namespace App\Http\Controllers\Tel;

use Illuminate\Http\Request;
use App\Tel\Podelenia;
use App\Tel\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PodeleniaController extends Controller
{
    public function podelenia(Request $request, $filterBy = null){
        if($filterBy == 'vasil') {
            $podelenie = Podelenia::select('podelenia.*')
                ->where([
                    //                ['Pod_NameBg', 'LIKE', '%' . $request->pod . '%'],
                    ['Pod_Id', '>', 100],
                    ['Pod_Id', '<', 116],
                    ['Activ', '=', 1]
                ])
                //            ->limit($request->limit)
                //            ->offset($request->start)
                ->get();

            foreach ($podelenie as $pod) {
//                echo $pod->Pod_NameBg ;
                echo response()->json(['RDG'=>$pod->Pod_NameBg])
                    ->setCallback($request->input('callback'));

                $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                    ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                    ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                    ->where([
                        ['nugEmpl.empl.Statut', '=', 1],
//                        ['nugEmpl.empl.Name', 'LIKE', '%' . $request->strIme . '%'],
//                        ['nugEmpl.empl.Familia', 'LIKE', '%' . $request->strFam . '%']
                        ['nugEmpl.empl.Pod_Id', '=', $pod->Pod_Id],
                    ])
                    ->orderBy('nugEmpl.empl.DlagID')
//                    ->limit($request->limit)
//                    ->offset($request->start)
                    ->get();
                    foreach ($names as $name){
//                         echo json_encode( $fullname[] = $name->Name . ' ' . $name->Familia ) ;
                         echo response()->json(['names' => $name->Name . ' ' . $name->Familia])
                             ->setCallback($request->input('callback'));;
                    }


            }
//            return response()->json([
//                                     'RDG' => $pod->Pod_NameBg,
//                                     'fullname' => $fullname
//                                    ]);
        }

        $podelenie = Podelenia::select('podelenia.*')
            ->where([
                ['Pod_NameBg', 'LIKE', '%' . $request->pod . '%'],
                ['Pod_Id', '>', 100],
                ['Pod_Id', '<', 117],
                ['Activ', '=', 1]
                ])
            ->limit($request->limit)
            ->offset($request->start)
            ->get();

        if($filterBy == 'rdg_i_pod'){
            $podelenie = Podelenia::select('podelenia.*')
                ->where([
                    ['Pod_Id', '>', 100],
                    ['Pod_Id', '<', 207],
                    ['Activ', '=', 1],
                ])
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }


        if($filterBy == 'all'){
            $podelenie = Podelenia::select('podelenia.*')
                ->where([
                    ['Glav_Pod', '>', 100],
                    ['Glav_Pod', '<', 116],
                    ['Activ', '=', 1],
                ])
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'rdg'){
            $podelenie = Podelenia::select('podelenia.*')
                ->where([
                    ['Pod_Id', '>', 100],
                    ['Pod_Id', '<', 116],
                    ['Glav_Pod', '=', 1],
                    ['Activ', '=', 1],
                ])
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'dgslist'){
            $podelenie = Podelenia::select('podelenia.*')
                ->where([
                    ['Glav_Pod', '=', $request->glavpod],
                    ['Pod_Type', '>', 2],
                    ['Pod_Type', '<', 7],
                    ['Activ', '=', 1],
                ])
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }


        if( $podelenie->count() == 0 )
        {
            return response()->json([
                'status_message'=>'Няма такова поделение',
                'status_code'   => 404
            ], 200)
                ->setCallback($request->input('callback'));
        }

        return response()
            ->json([
                'records'        => $this->transformCollection($podelenie),
                'records_count'  => $podelenie->count(),
                'status_message' => 'Извличането на данни завърши успешно',
                'status_code'    => 200
            ])
            ->setCallback($request->input('callback'));
    }

    private function transformCollection($podelenie)
    {
        return array_map([$this ,'transform'],$podelenie->toArray());
    }


    private function transform($podelenie)
    {
        return [
            'pod_id'            => $podelenie['Pod_Id'],
            'podelenie'         => $podelenie['Pod_NameBg'],
        ];
    }
}
