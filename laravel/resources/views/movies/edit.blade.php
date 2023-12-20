<head>
    <link rel="stylesheet" href="{{ asset(mix('assets/css/demo.css')) }}" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('box-title')
{{ __('movies') . " " . $movie->id }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            @foreach ($files as $file)
            @if($file->id == $movie->cover_id)
            <div>
                <img class="img-posts-show" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview" />
            </div>
            @endif
            @endforeach

            @foreach ($files as $file)
            @if($file->id == $movie->intro_id)
            <div>
                <video class="showvideo" controls>
                    <source alt="Pelicula" type="video/mp4" src='{{ asset("storage/{$file->filepath}") }}' />
                </video>
            </div>
            @endif
            @endforeach
        </div>
        <div class="col-md-8">

            <div class="card">
                <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="title">TITLE</label>
                        <textarea id="title" name="title" class="form-control">{{ $movie->title }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">DESCRIPTION</label>
                        <textarea id="description" name="description"
                            class="form-control">{{ $movie->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="gender">GENDER</label>
                        <textarea id="gender" name="gender" class="form-control">{{ $movie->gender }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="cover">COVER</label>
                        <input type="file" id="cover" name="cover" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="intro">INTRO</label>
                        <input type="file" id="intro" name="intro" class="form-control" />
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>

                    <button type="reset" class="btn btn-secondary">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection