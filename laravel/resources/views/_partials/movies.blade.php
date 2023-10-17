@foreach ($movies as $movie)
        <div class="border posts">
            <div class="boton-black"> 
                <div>
                    <a href="{{ route('movies.show', $movie) }}" class="boton-black" style="font-size:25px;"type="submit"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                </div>
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

            <div class="boton-posts"> 
                <div>
                <a class="btn btn-primary my-2 my-sm-0" style="font-size:25px;"type="submit"><i class="fa-solid fa-square-share-nodes"></i> </a>
                </div>
            </div>
            <div class="texto">
                 {{ $movie->title }} 
            </div>
        </div>
@endforeach