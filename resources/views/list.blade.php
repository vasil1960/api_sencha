@extends('layout.app')

@section('content')


    <div class="row">
        <div class="col-md-6 offset-3">

            @foreach ($names as $name )

                <div class="card mb-4 shadow">
                    <div class="card-header p-4">
                        <h5> {{ $name->Ime  }} {{ $name->Prezime }} {{ $name->Familia }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7 p-4">
                               
                                <div>
                                    ЕГН: {{ $name->EGN_EIK }}
                                </div>
            
                                <div>
                                    Град: {{ $name->address->grad->Municipality }},
                                </div>
            
                                <div>
                                    ул: {{ $name->address->AddressP }}
                                </div> 
            
                                <div>
                                    Телефон: {{ $name->address->PhoneMob }},    
                                </div> 
            
                                <div>
                                    Ел. поща: <span>{{ $name->address->Email ? $name->address->Email : 'Не е подаден' }}</span>
                                </div>                        
                            </div>

                            <div class="col-md-5 p-2">

                                <p>Марки:</p>

                                @foreach ($name->kgm as $marks )
                                    <p><span class="">{{ $marks->SeriaKGM }} {{ $marks->strNumberKGM}}</span> ({{ $marks->TypeKGM == 1 ? 'Метално чукче' : 'Пластина' }})</p>
                                @endforeach

                                <p class="{{ $marks->Active == 0  ? 'text-danger' : 'text-success' }}">{{ $marks->Active == 1 ? 'Активна' : 'Неактивна' }}</p>

                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <div>
                {{ $names->links() }}
            </div>

        </div>

    </div>

@endsection