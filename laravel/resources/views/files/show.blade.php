@extends('layouts.box-app')

@section('box-title')
    {{ __('File') . " " . $file->id }}
@endsection

@section('box-content')
    <img class="img-fluid" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
    <table class="table">
            <tr>
                <td><strong>ID<strong></td>
                <td>{{ $file->id }}</td>
            </tr>
            <tr>
                <td><strong>Filepath</strong></td>
                <td>{{ $file->filepath }}</td>
            </tr>
            <tr>
                <td><strong>Filesize</strong></td>
                <td>{{ $file->filesize }}</td>
            </tr>
            <tr>
                <td><strong>Created</strong></td>
                <td>{{ $file->created_at }}</td>
            </tr>
            <tr>
                <td><strong>Updated</strong></td>
                <td>{{ $file->updated_at }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Buttons -->
    <div class="container" style="margin-bottom:20px">
        <a class="btn btn-warning" href="{{ route('files.edit', $file) }}" role="button">📝 {{ _('Edit') }}</a>
        <form id="form" method="POST" action="{{ route('files.destroy', $file) }}" style="display: inline-block;">
            @csrf
            @method("DELETE")
            <button id="destroy" type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">🗑️ {{ _('Delete') }}</button>
        </form>
        <a class="btn" href="{{ route('files.index') }}" role="button">⬅️ {{ _('Back to list') }}</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ _('Are you sure?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ _('You are gonna delete file ') . $file->id }}</p>
                    <p>{{ _('This action cannot be undone!') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="confirm" type="button" class="btn btn-primary">{{ _('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    @env(['local','development'])
        @vite('resources/js/delete-modal.js')
    @endenv

@endsection
