@extends('layouts.admin')
@section('title', 'Calendario Tributario')
@section('library')
    @include('cdn.fullcalendar-head')
@endsection
@section('content')

    @can('CREAR_CALENDARIO_TRIBUTARIO')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.calendario.create') }}">
                    <i class="fas fa-circle-plus"></i> 
                    Cargue Masivo
                </a>
                <a class="btn btn-back  border btn-radius" href="{{ route('admin.calendario.table') }}">
                    <i class="fa-solid fa-table"></i> 
                    Datos Cargados
                </a>
            </div>
            
        </div>
    
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-6 col-md-6">
            <a class="btn btn-back  border btn-radius" href="{{ route('admin.calendario.notificacion') }}">
                <i class="fa-solid fa-table"></i> 
                Notificaciones
            </a>
        </div>
    </div>
    @endcan
     
    @include('admin.calendariotributario.calendario')

@endsection
