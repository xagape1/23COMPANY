@extends('layouts.app')

@section('content')
<div class="container">
    <h1>List of Movies</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movies as $movie)
                <tr>
                    <td>
                        @foreach ($files as $file)
                            @if ($file->id === $movie->cover_id)
                                <img src="{{ asset('storage/' . $file->filepath) }}" alt="Movie Image" style="max-width: 100px;">
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>{{ $movie->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
