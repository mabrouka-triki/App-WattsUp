@extends('App.layout')

@section('head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('title', 'Bienvenue sur WattsUp')

@section('content')
<div class="containerhome">
    <div class="welcome-box">
        <h1 class="text-center mb-4">
            Bienvenue sur <span class="highlight">WattsUp</span> 
        </h1>

        <div class="text">
            <p>
                <strong>WattsUp</strong> est une plateforme web intuitive qui permet aux particuliers de 
                <strong>suivre leur consommation d’énergie en temps réel</strong> (électricité, gaz, eau),
                <strong>d’analyser leurs habitudes</strong> et de 
                <strong>recevoir des conseils personnalisés</strong> pour réduire leur consommation.
            </p>

            <p>
                Ce site vise à accompagner les utilisateurs vers une 
                <strong>meilleure organisation quotidienne</strong> en leur offrant une 
                <strong>vue d’ensemble claire, interactive et compréhensible</strong> de leur consommation.
            </p>

            <p>
                Il s’agit d’un outil à la fois <strong>informatif</strong> et <strong>éducatif</strong>, 
                adapté aux enjeux <strong>économiques</strong> et <strong>écologiques</strong> actuels.
            </p>
        </div>
    </div>
</div>
@endsection
