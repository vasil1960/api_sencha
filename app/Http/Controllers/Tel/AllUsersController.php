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
    public function allusers(Request $request, $filterBy){
        // = $request->filterBy;
        //dd($filterBy);
        if($filterBy == 'imeAndFam') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut', '=', 1],
                    ['nugEmpl.empl.Name', 'LIKE', '%' . $request->strIme . '%'],
                    ['nugEmpl.empl.Familia', 'LIKE', '%' . $request->strFam . '%']
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'imeOrFam') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj','nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut', '=', 1],
                    ['nugEmpl.empl.Name', 'LIKE',  $request->strImeOrFam . '%'],
                    ['nugEmpl.empl.Familia', 'LIKE', $request->strImeOrFam . '%']
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'byNumber') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut', '=', 1],
                    ['nugEmpl.empl.GSM', 'LIKE', '%' . $request->number . '%']
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'podelenie') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut','=', 1],
                    ['nugEmpl.empl.Pod_Id', 'LIKE', $request->pod . '%']
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'all') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where('nugEmpl.empl.Statut','=', 1)
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'users_by_podelenia') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut','=', 1],
                    ['nugEmpl.empl.Pod_Id','=', $request->podid],
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }

        if($filterBy == 'iag') {

            $names = User::select('nugEmpl.empl.*', 'nugEmpl.dlagnosti.Dlagnost', 'nugEmpl.directorate.DirectorateBadj', 'nugEmpl.empl.Statut')
                ->join('nugEmpl.dlagnosti', 'nugEmpl.dlagnosti.ID', '=', 'nugEmpl.empl.DlagID')
                ->leftJoin('nugEmpl.directorate', 'nugEmpl.directorate.ID', '=', 'nugEmpl.empl.DirID')
                ->where([
                    ['nugEmpl.empl.Statut','=', 1],
                    ['nugEmpl.empl.Pod_Id','=', 1],
                ])
                ->orderBy('nugEmpl.empl.DlagID')
                ->limit($request->limit)
                ->offset($request->start)
                ->get();
        }


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
            'id'                 => $names['ID'],
            'ime'                => $names['Name'],
            'titla'              => $names['Titla'],
            'prez'               => $names['Prezime'],
            'fam'                => $names['Familia'],
            'pod'                => $names['Podelenie'],
            'tel'                => $names['GSM'] ? $names['GSM'] : "Не е посочен телефон",
            'glav_pod'           => $names['Glav_Pod'],
            'pod_id'             => $names['Pod_Id'],
            'email'              => $names['Email'] ? $names['Email'] : "Не е посочен email",
            'picture'            => $names['Picture'] ? $names['Picture'] : 'noimage.png',
            'egn'                => $names['EGN'],
            'dlagnost'           => $names['Dlagnost'],
            'directorate_badj'   => $names['DirectorateBadj'] ? $names['DirectorateBadj'] : "Не е посочена",
            'statut'             => $names['Statut'],
        ];
    }

}
