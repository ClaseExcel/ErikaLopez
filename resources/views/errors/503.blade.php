@extends('errors::minimal')

@php
    \Debugbar::disable();
@endphp

@section('title', __('Servicio no disponible'))
@section('code', '503')
@section('message', __('Servicio no disponible'))
