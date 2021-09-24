<?php

namespace App\Http\Controllers\Tel;

use App\Name;
use App\Tel\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChasnaPracticaController extends Controller
{
    public  function phone(Request $request)
    {

//        dd($request->start);
        $names = Name::select('iagRegister.u_names.ID','iagRegister.u_names.Ime','iagRegister.u_names.Prezime','iagRegister.u_names.Familia','regions.PopulatedPlaces.PolpulatedPlace','regions.PopulatedPlaces.Municipality','regions.PopulatedPlaces.Region','iagRegister.u_address.PhoneMob','iagRegister.u_address.Email')
                ->join('iagRegister.u_address','iagRegister.u_names.ID','=','iagRegister.u_address.NamesID')
                ->join('regions.PopulatedPlaces','regions.PopulatedPlaces.ID','=','regions.PopulatedPlaces.ID')
                ->where([
                    ['iagRegister.u_address.PhoneMob', 'LIKE', '%' . 08881 .'%']
                ])
//                ->orderBy('')
//                ->limit($request->limit)
//                ->offset($request->start)
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
//    }

    private function transformCollection($names)
    {
        return array_map([$this ,'transform'],$names->toArray());
    }


    private function transform($names)
    {
        return [
            'id'            => $names['ID'],
            'ime'           => $names['Ime'],
            'prez'          => $names['Prezime'],
            'fam'           => $names['Familia'],
            'tel'           => $names['PhoneMob'] ? $names['PhoneMob'] : "Не е посочен телефон",
            'email'         => $names['Email'] ? $names['Email'] : "Не е посочен email",
        ];
    }
}
