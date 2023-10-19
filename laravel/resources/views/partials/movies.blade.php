@foreach ($movies as $movie)
        <div class="border posts">
            <div class="texto">
                 {{ $movie->title }} 
            </div>
            <div class="imagen-posts"> 
                    @foreach ($files as $file)
                        @if($file->id == $movie->cover_id)
                            <div class="div-foto-post">
                                    <img alt ="Publicació d'usuari" class="img-posts" src='{{ asset("storage/{$file->filepath}") }}'/>
                            </div>
                        @endif
                    @endforeach
            </div>
        </div>
        <div class="boton-posts"> 
            --------------------------------------------------
        </div>
@endforeach