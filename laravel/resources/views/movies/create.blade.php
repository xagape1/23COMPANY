@section('box-title')
{{ __('Add Movie') }}
@endsection

@section('box-content')
<div class="container"> <div class="row justify-content-center"> <div class="col-md-8"> <div class="card"> <div
    class="border posts"> <form method="post" action="{{ route('movies.store') }}" enctype="multipart/form-data"> @csrf
    <div class="form-group"> <label for="title">{{ __('fields.title') }}</label> <textarea id="title" name="title"
        class="form-control"></textarea>
    </div>
    <div class="form-group">
    <label for="description">Descripción</label>
    <textarea id="description" name="description" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="gender">Género</label>
        <textarea id="gender" name="gender" class="form-control"></textarea>
    </div>
    <div class="form-group">
    <label for="cover">Portada</label>
    <input type="file" id="cover" name="cover" class="form-control" />
    </div>
    <div class="form-group">
    <label for="intro">{{ __('fields.intro_id') }}</label>
    <input type="file" id="intro" name="intro" class="form-control" />
    </div>
    <button type="submit" class="btn btn-primary">{{ __('fields.Create') }}</button>
    <button type="reset" class="btn btn-secondary">{{ __('fields.Reset') }}</button>
    </form>
</div>
</div>
</div>
</div> </div>