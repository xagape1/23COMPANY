<head>
    <link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

<div class="container">
    @foreach ($movies as $movie)
    <div class="movie" href="{{ route('movies.show', $movie) }}">
        <div class="header">
            {{ $movie->title }}
        </div>
        @foreach ($files as $file)
        @if($file->id == $movie->cover_id)
        <a href="{{ route('movies.show', $movie) }}"> <img alt="Portada Pelicula" src='{{ asset("storage/{$file->filepath}") }}' /> </a>
        @endif
        @endforeach
    </div>
    @endforeach
</div>