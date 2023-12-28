<head>
    <link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')

<form action="{{ route('movies.index') }}" method="GET">
    <label for="busqueda">Buscar por título:</label>
    <div class="input-group">
        <input type="text" name="busqueda" class="form-control" value="{{ request('busqueda') }}"
            placeholder="Buscar...">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </div>
</form>

<div class="containerr">
    @if(count($movies)<=0) <tr>
        <td colspan="8"> No se encontraron películas </td>
        </tr>
        @else

        @foreach ($movies as $movie)
        <div class="movie" href="{{ route('movies.show', $movie) }}">
            <div class="header">
                {{ $movie->title }}
            </div>
            <div class="portada">
                @foreach ($files as $file)
                @if($file->id == $movie->cover_id)
            </div>
            <a href="{{ route('movies.show', $movie) }}"> <img alt="Portada Pelicula" 
                    src='{{ asset("storage/{$file->filepath}") }}' /> </a>
            @endif
            @endforeach
        </div>
        @endforeach
        @endif
</div>

@endsection