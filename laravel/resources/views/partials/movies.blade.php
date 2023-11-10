@foreach ($movies as $movie)
        <div class="border posts">
            <div class="texto">
                 {{ $movie->title }} 
            </div>
            <div class="imagen-posts"> 
                @foreach ($files as $file)
                {{ var_dump($file) }}
                    @if($file->id == $movie->cover_id)
                        <div class="div-foto-post">
                                <img alt ="Portada Pelicula" class="img-posts" src="{{ storage_path('app/' . $file->filepath) }}"/>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="boton-posts"> 
            --------------------------------------------------
        </div>
@endforeach