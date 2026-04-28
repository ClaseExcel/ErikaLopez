@extends('errors::minimal')

@php
    \Debugbar::disable();
@endphp

@section('title', __('El pago es requerido'))
@section('code', '402')
@section('message', __('El pago es requerido'))

@section('btn-back')
<a onclick="window.history.back();" class="btn btn-back">
    <i class="fa-solid fa-reply"></i> &nbsp;
    IR ATRÁS
</a>
@endsection