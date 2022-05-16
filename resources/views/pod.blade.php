@extends('layout.app')

@section('content')


    <div class="row">
        <div class="col-md-8 offset-2">

            @foreach ($pods as $pod )

                <div class="card mb-4 shadow">
                    <div class="card-header p-4">
                        <h5> {{ $pod->Pod_Name  }}</h5>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 p-4">
                               
                                {{--  <div>
                                    Обект: {{ $pod->Pod_Name }}
                                </div>  --}}
            
                                <div>
                                    Моб. обект: {{ $pod->Pod_NameBg }},
                                </div>
            
                                <div>
                                    Град/с: {{ $pod->Pod_Grad }}
                                </div> 
            
                                <div>
                                    Адрес: {{ $pod->Pod_Adres }},    
                                </div> 
            
                                <div>
                                    ЕИК: <span>{{ $pod->Eik ? $pod->Eik : 'Не е подаден' }}</span>
                                </div>                        
                            </div>

                            <div class="col-md-4 p-4">

                                КГ Марка: {{ $pod->pgm }}

                                {{--  @foreach ($name->kgm as $marks )
                                    <p><span class="">{{ $marks->SeriaKGM }} {{ $marks->strNumberKGM}}</span> ({{ $marks->TypeKGM == 1 ? 'Метално чукче' : 'Пластина' }})</p>
                                @endforeach  --}}

                                {{--  <p class="{{ $marks->Active == 0  ? 'text-danger' : 'text-success' }}">{{ $marks->Active == 1 ? 'Активна' : 'Неактивна' }}</p>  --}}

                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <div>
                {{ $pods->links() }}
            </div>

        </div>

    </div>

@endsection