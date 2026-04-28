@extends('layouts.admin')
@section('title', 'Home')
@section('library')
    @include('cdn.fullcalendar-head')
@endsection
@section('content')
    {{-- @if (!empty($permissionsApi['estado']) && $permissionsApi['estado'] == '1')
    @endif --}}
    <div class="row">
        @livewireScripts
        @livewire('filtroDashboard')

    </div>
@endsection

@section('scripts')
    @parent


@endsection
