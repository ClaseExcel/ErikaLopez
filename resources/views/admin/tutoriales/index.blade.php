@extends('layouts.admin')
@section('title', 'Tutoriales Help! Contable')
@section('content')
<style>
    .accordion-button {font-size:16px;color: #343a40 !important;}
    .accordion-button:not(.collapsed) {
        background-color: #00aeef;
        color: white !important;
    }
    .fa-play-circle{opacity:0.8}
</style>


@php
    /*
    *  Rol diferente a cliente
    *  "titulo" => "código"
    */
    $listOne = [
        "Gestionar usuarios"  => "1X627dSSzct1D4NEOgS7UnNiBO_raHqTF",
        "Manejo de roles"   => "1x2LDaRo1zt-30K-6gsaSCxcG8tGRlFqb",
        "Crear y editar una empresa"  => "1ODvGTXlblIFv1aklzdbAYxAWnUIXoaVb",
        "Empleados de mis clientes" => "1mddSBR0JUR2nFlLs8xq9ahNbD3N2aqNe",
        "Gestión de clientes" => "1-dn0juC2jx9d2gwOicn5FW1iP2yxe23B" ,
        "Agendar una cita" => "1WzrrvkW90EGCJo_vvRsCmNoh5cU8bjKF",
        "Aceptar o rechazar un requerimiento" => "1Dv25qnlZZYspI_RgSgwXfuu-Op5cBw9c",
        "Tramitar un requerimiento" => "165-QeI0vxVnFnERA_gOg7wXblFJwCVvs",
        "Crear una actividad" => "1bjXYfqvKKo6CIuP0BxgPJM6ZlhR6bQsH",
        "Carga masiva de actividades" => "1-Tte0nS0K0Z9-yTgCCEL4UnErAE7mx9n",
        "Busca una actividad" => "16_SwsNqIxPNgdz-5pJ-W6Co0psKRELCj",
        "Edita una actividad" => "1O0Jf52XoliOfPb7dbyvtQl3R08bTAtQp",
        "Cancela una actividad" => "1EQ1nC-XP--LyWZRbaAdl60PmqsTN67mP",
        "Reporta el avance de una actividad" => "1rPJui9qsh2ULaV4CsafrbfhiegikyBSO",
        "Cambia el responsable de una actividad" => "1WFKef-p79v37NJwwHPkFlhcehj3RVTEl",
        "Cambia la fecha de una actividad" => "1l3kqJxbn_Ymrh-bS_hxtzQmVM_i5MQUx",
       

    ];
    /*
    * Rol cliente o generico
    *  "titulo" => "código"
    */
    $listTwo = [
        "Citas cliente" => "1z6rjxreu7wpEVVjfPoJp5sHS955eBtqS",
        "Solicita un requerimiento" => "1xfwiv2woTYe4JECh2nf1wG-RF1VkQ7Mm",
        "Cancelar un requerimiento" => "1oxLd7Qpn2lJV1b5xhZqNtY7U8WK6U_rW",
    ]
@endphp

<div class="row">
    
    <div class="col-12 col-md-10 col-xl-6">
        <div class="card">
                <h4 class="card-header"><i class="fas fa-th-list"></i> Guía de usuario</h4>
            
                <div class="accordion accordion-flush">
                    @if (Auth::user()->role->title != 'Cliente')
                        @foreach ($listOne as $title => $code)
                            @php $key = array_search($title, array_keys($listOne)); @endphp
                            @include('admin.tutoriales.accordion',['key' =>'l1-'. $key, 'title' => $title, 'code' => $code])     
                        @endforeach
                    @endif

                    @if (Auth::user()->role->title == 'Cliente' || Auth::user()->role->title == 'Génerico')
                        @foreach ($listTwo as $title => $code)
                            @php $key = array_search($title, array_keys($listTwo)); @endphp
                            @include('admin.tutoriales.accordion',['key' =>'l2-'. $key, 'title' => $title, 'code' => $code]) 
                        @endforeach
                    @endif

                </div>

                <div class="card-footer bg-transparent py-3"></div>
        </div>
    </div>
</div>
@endsection
