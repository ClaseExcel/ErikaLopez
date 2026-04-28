@extends('errors::minimal')

@php
    \Debugbar::disable();
@endphp

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('La sesión ha expirado'))
@section('btn-back')
    <a onclick="window.history.back();" class="btn btn-back">
        <i class="fa-solid fa-reply"></i> &nbsp;
        IR ATRÁS
    </a>
@endsection
