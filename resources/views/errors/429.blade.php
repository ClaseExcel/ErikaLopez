@extends('errors::minimal')

@php
    \Debugbar::disable();
@endphp

@section('title', __('Demasiadas solicitudes'))
@section('code', '429')
@section('message', __('Demasiadas solicitudes'))
@section('btn-back')
    <a onclick="window.history.back();" class="btn btn-back">
        <i class="fa-solid fa-reply"></i> &nbsp;
        IR ATRÁS
    </a>
@endsection
