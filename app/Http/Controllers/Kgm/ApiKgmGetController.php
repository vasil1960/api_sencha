<?php

namespace App\Http\Controllers;
use App\Kgm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiKgmGetController extends Controller
{
//    // api/kgm/search?seria=А&number=3092
//
//    public function all(Request $request)
//    {
//        $kgms = Kgm::join('u_names','u_names.ID','=','u_kgm.NamesID')
//            ->join('u_address','u_address.NamesID','=','u_names.ID')
//            ->join('regions.PopulatedPlaces','regions.PopulatedPlaces.ID','=','u_address.Grad')
////            ->where([
////                ['SeriaKGM', $request->seria],
////                ['strNumberKGM','LIKE', '%'. $request->number . '%']
////            ])
//            ->take(10)->get();
//
////        if( $kgms->count() == 0 )
////        {
////            return Response::json([
////                'status_message'=>'Няма такава марка',
////                'status_code'   => 404
////            ], 200);
////        }
//
//        return Response::json([
//
//            'records'=> $this->transformCollection($kgms),
//
//            'status_message' => 'Извличането на данни завърши успешно',
//            'status_code'    => 200
//        ], 200);
//    }

    public function search(Request $request)
    {
        $names = Kgm::join('u_names','u_names.ID','=','u_kgm.NamesID')
                   ->join('u_address','u_address.NamesID','=','u_names.ID')
                   ->join('regions.PopulatedPlaces','regions.PopulatedPlaces.ID','=','u_address.Grad')
                   ->where([
                       ['SeriaKGM', $request->seria],
                       ['strNumberKGM','LIKE', '%'. $request->number . '%']
                   ])
                   ->take(100)->get();

        if( $names->count() == 0 )
        {
            return Response::json([
                    'status_message'=>'Няма такова име',
                    'status_code'   => 404
            ], 200);
        }

        return Response::json([
            'names' => $this->transformCollection_names($names),
            'count'          => $names->count(),
            'status_message' => 'Извличането на данни завърши успешно',
            'status_code'    => 200
        ], 200);
    }


    // api/kgm/get?names_id=4093
    public function get(Request $request)
    {
        $names = Kgm::join('u_names','u_names.ID','=','u_kgm.NamesID')
                    ->join('u_address','u_address.NamesID','=','u_names.ID')
                    ->join('regions.PopulatedPlaces','regions.PopulatedPlaces.ID','=','u_address.Grad')
                    ->where('u_names.ID', $request->names_id)
                    ->first();

        $kgms = Kgm::join('u_names','u_names.ID','=','u_kgm.NamesID')
                   ->join('u_address','u_address.NamesID','=','u_names.ID')
                   ->join('regions.PopulatedPlaces','regions.PopulatedPlaces.ID','=','u_address.Grad')
                   ->where('u_names.ID', $request->names_id)
                   ->get();

        if( ! $kgms)
        {
            return Response::json([
                'status_message'=>'Няма такова лице',
                'status_code'   => 404
            ], 200);
        }

        return Response::json([
                'ime'            => $names['Ime'],
                'prezime'        => $names['Prezime'],
                'familia'        => $names['Familia'],
                'grad'           => $names['PolpulatedPlace'],
                'obshtina'       => $names['Municipality'],
                'oblast'         => $names['Region'],
                'email'          => $names['Email'],
                'phone'          => $names['Phone'],
                'egn'            => $names['EGN_EIK'],
                'address'        => $names['AddressP'],
                'marks'          => $this->transformCollection($kgms),
                'status_message' => 'Извличането на данни завърши успешно',
                'count'          => $kgms->count(),
                'status_code'    => 200,
        ], 200);
    }

    private function transformCollection($kgms)
    {
        return array_map([$this ,'transform'],$kgms->toArray());
    }

    private function transformCollection_names($names)
    {
        return array_map([$this ,'transform_names'],$names->toArray());
    }


    private function transform($kgms)
    {
        return [
//            'kgm_id'                 => $kgms['ID'],
//            'udo_kgm_id'             => $kgms['UdoKgmID'],
//            'names_id'               => $kgms['NamesID'],
//            'ime'                    => $kgms['Ime'],
//            'prezime'                => $kgms['Prezime'],
//            'familia'                => $kgms['Familia'],
            'seria'                  => $kgms['SeriaKGM'],
            'number'                 => $kgms['strNumberKGM'],
//            'grad'                   => $kgms['PolpulatedPlace'],
//            'obshtina'               => $kgms['Municipality'],
//            'oblast'                 => $kgms['Region'],
//            'email'                  => $kgms['Email'],
//            'phone'                  => $kgms['Phone'],
//            'egn'                    => $kgms['EGN_EIK'],
//            'address'                => $kgms['AddressP'],
            'type'                   => $kgms['TypeKGM'],
            'number_zap_otpisvane'   => $kgms['NumberZapovedOtpisvane'],
            'date_zapoved_otpisvane' => $kgms['DateZapovedOtpisvane'],
            'active'                 => (boolean) $kgms['Active'],
        ];
    }

    private function transform_names($names)
    {
        return [
//            'kgm_id'                 => $kgms['ID'],
//            'udo_kgm_id'             => $kgms['UdoKgmID'],
            'names_id'               => $names['NamesID'],
            'ime'                    => $names['Ime'],
            'prezime'                => $names['Prezime'],
            'familia'                => $names['Familia'],
//            'seria'                  => $names['SeriaKGM'],
//            'number'                 => $names['strNumberKGM'],
            'grad'                   => $names['PolpulatedPlace'],
            'obshtina'               => $names['Municipality'],
            'oblast'                 => $names['Region'],
            'email'                  => $names['Email'],
            'phone'                  => $names['Phone'],
//            'egn'                    => $kgms['EGN_EIK'],
            'address'                => $names['AddressP'],
            'type'                   => $names['TypeKGM'],
//            'number_zap_otpisvane'   => $names['NumberZapovedOtpisvane'],
//            'date_zapoved_otpisvane' => $names['DateZapovedOtpisvane'],
//            'active'                 => (boolean) $names['Active'],
        ];
    }
}
