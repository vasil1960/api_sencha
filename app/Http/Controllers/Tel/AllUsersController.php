<?php

namespace App\Http\Controllers\Tel;

use App\Tel\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AllUsersController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function allusers(Request $request){
        $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost')
            ->join('nugEmpl.dlagnosti','nugEmpl.dlagnosti.ID','=','nugEmpl.empl.DlagID')
            ->where(['nugEmpl.empl.Statut' => 1,
//                     'nugEmpl.empl.Pod_Id' => 1
            ])
            ->orderBy('nugEmpl.empl.DlagID')
            ->limit($request->limit)
            ->offset($request->start)
            ->get();


        if( $names->count() == 0 )
        {
            return response()->json([
                'status_message'=>'Няма такова име',
                'status_code'   => 404
            ], 200)
            ->setCallback($request->input('callback'));
        }

        return response()
            ->json([
                'records'        => $this->transformCollection($names),
                'records_count'  => $names->count(),
                'status_message' => 'Извличането на данни завърши успешно',
                'status_code'    => 200
            ])
            ->setCallback($request->input('callback'));
    }


        private function transformCollection($names)
        {
            return array_map([$this ,'transform'],$names->toArray());
        }


        private function transform($names)
        {
            return [
                'id'         => $names['ID'],
                'ime'        => $names['Name'],
                'titla'      => $names['Titla'],
                'prez'       => $names['Prezime'],
                'fam'        => $names['Familia'],
                'pod'        => $names['Podelenie'],
                'tel'        => $names['GSM'],
                'glav_pod'   => $names['Glav_Pod'],
                'email'      => $names['Email'],
                'picture'    => $names['Picture'],
                'egn'        => $names['EGN'],
                'dlagnost'   => $names['Dlagnost'],
            ];
        }

}
