@extends('App.layoutAdmin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Tableau de bord Administrateur</h1>
@auth
    @if(Auth::user()->hasRole('admin'))
        Bonjour admin {{ Auth::user()->name }}
    @else
        Bonjour client {{ Auth::user()->name }}
    @endif
@endauth
