@extends('errors::minimal')

@section('title', __('No tienes permisos para acceder a esta página'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'No tienes permisos para acceder a esta página'))
