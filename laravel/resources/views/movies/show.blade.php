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
        <div class="col-md-8">
            <div class="card">
                <table class="tableshow">
                    @foreach ($files as $file)
                    @if($file->id == $movie->intro_id)
                    <div>
                        <video width="640" height="360" controls>
                            <source class="imgshow" alt="Pelicula" type="video/mp4" src='{{ asset("storage/{$file->filepath}") }}' />
                        </video>
                    </div>
                    @endif
                    @endforeach
                    <tr>
                        <td><strong>{{ __('Tittle') }}</strong></td>
                        <td>{{ $movie->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Description') }}</strong></td>
                        <td>{{ $movie->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Gender') }}</strong></td>
                        <td>{{ $movie->gender }}</td>
                    </tr>
                    </tbody>
                </table>

                <!-- Buttons -->
                <div class="container" style="margin-bottom:20px">
                    <a class="btn btn-warning" href="{{ route('movies.edit', $movie) }}" role="button">📝 {{ __('Edit')
                        }}</a>
                    <form id="form" method="POST" action="{{ route('movies.destroy', $movie) }}"
                        style="display: inline-block;">
                        @csrf
                        @method("DELETE")
                        <button id="destroy" type="submit" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmModal">🗑️ {{ __('Delete') }}</button>
                    </form>
                    <a class="btn" href="{{ route('pages-home') }}" role="button">⬅️ {{ __('Back to list') }}</a>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Are you sure?') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('You are gonna delete movie ') . $movie->id }}</p>
                                <p>{{ __('This action cannot be undone!') }}</p>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button id="confirm" type="button" class="btn btn-primary">{{ __('Confirm')
                                        }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection


@section('box-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <table class="table">
                    @foreach ($files as $file)
                    @if($file->id == $movie->intro_id)
                    <div>
                        <img alt="Pelicula" src='{{ asset("storage/{$file->filepath}") }}' />
                    </div>
                    @endif
                    @endforeach
                    <tr>
                        <td>{{ $movie->title }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Description') }}</strong></td>
                        <td>{{ $movie->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('Gender') }}</strong></td>
                        <td>{{ $movie->gender }}</td>
                    </tr>
                    </tbody>
                </table>

                <!-- Buttons -->
                <div class="container" style="margin-bottom:20px">
                    <a class="btn btn-warning" href="{{ route('movies.edit', $movie) }}" role="button">📝 {{ __('Edit')
                        }}</a>
                    <form id="form" method="POST" action="{{ route('movies.destroy', $movie) }}"
                        style="display: inline-block;">
                        @csrf
                        @method("DELETE")
                        <button id="destroy" type="submit" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmModal">🗑️ {{ __('Delete') }}</button>
                    </form>
                    <a class="btn" href="{{ route('pages-home') }}" role="button">⬅️ {{ __('Back to list') }}</a>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Are you sure?') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('You are gonna delete movie ') . $movie->id }}</p>
                                <p>{{ __('This action cannot be undone!') }}</p>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button id="confirm" type="button" class="btn btn-primary">{{ __('Confirm')
                                        }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection