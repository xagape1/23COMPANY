@extends('layouts.box-app')

@section('box-title')
    {{ __('File') . " " . $file->id }}
@endsection

@section('box-content')
    <img class="img-fluid" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
    <form method="POST" action="{{ route('files.update', $file) }}" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="form-group">
            <label for="upload">{{ _('File') }}:</label>
            <input type="file" class="form-control" name="upload"/>
        </div>
        <button type="submit" class="btn btn-primary">{{ _('Update') }}</button>
        <button type="reset" class="btn btn-secondary">{{ _('Reset') }}</button>
    </form>
@endsection