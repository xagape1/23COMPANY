<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $busqueda = $request->busqueda;

        $moviesQuery = Movie::query();
        if ($busqueda) {
            $moviesQuery->where('title', 'LIKE', '%' . $busqueda . '%')
            ->paginate(2);
        }

        $movies = $moviesQuery->get();

        $files = File::all();

        $data = [
            'movies' => $movies,
            'files' => $files,
        ];

        return view('movies.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("movies.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los archivos
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'required|mimes:gif,jpeg,jpg,png,mp4',
            'intro' => 'required|mimes:gif,jpeg,jpg,png,mp4',
        ]);

        $title = $request->get('title');
        $description = $request->get('description');
        $gender = $request->get('gender');
        $cover = $request->file('cover');
        $intro = $request->file('intro');

        $filec = new File();
        $filecOk = $filec->diskSave($cover);

        $filei = new File();
        $fileiOk = $filei->diskSave($intro);

        if ($filecOk && $fileiOk) {
            // Guardar los datos en la BD
            Log::debug("Saving post at DB...");
            $movie = Movie::create([
                'title' => $title,
                'description' => $description,
                'gender' => $gender,
                'cover_id' => $filec->id,
                'intro_id' => $filei->id,
            ]);
            Log::debug("DB storage OK");
            // Redirigir con mensaje de éxito
            return redirect()->route('movies.show', $movie)
                ->with('success', __('Movie successfully saved'));
        } else {
            // Redirigir con mensaje de error
            return redirect()->route("movies.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    private function saveFileAndGetId($file)
    {
        $fileModel = new File();
        if ($fileModel->diskSave($file)) {
            return $fileModel->id;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return view('movies.show', [
            'movie' => $movie,
            "files" => File::all(),
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        return view("movies.edit", [
            'movie' => $movie,
            "files" => File::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        // Validar los archivos solo si se están actualizando
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'sometimes|required|mimes:gif,jpeg,jpg,png,mp4',
            'intro' => 'sometimes|required|mimes:gif,jpeg,jpg,png,mp4',
        ]);

        $title = $request->get('title');
        $description = $request->get('description');
        $gender = $request->get('gender');
        $cover = $request->file('cover');
        $intro = $request->file('intro');

        // Actualizar los datos de la película en la BD
        Log::debug("Updating post at DB...");
        $movie->update([
            'title' => $title,
            'description' => $description,
            'gender' => $gender,
        ]);
        Log::debug("DB update OK");

        // Actualizar archivos solo si se proporcionan nuevos archivos
        if ($cover) {
            $filec = new File();
            $filecOk = $filec->diskSave($cover);

            if ($filecOk) {
                // Actualizar el ID del archivo de portada
                $movie->update(['cover_id' => $filec->id]);
            }
        }

        if ($intro) {
            $filei = new File();
            $fileiOk = $filei->diskSave($intro);

            if ($fileiOk) {
                // Actualizar el ID del archivo de introducción
                $movie->update(['intro_id' => $filei->id]);
            }
        }
        // Redirigir con mensaje de éxito
        return redirect()->route('movies.show', $movie)
            ->with('success', __('Movie successfully updated'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        // Verificar si la relación 'file' está presente antes de eliminar
        if ($movie->file) {
            // Eliminar fitxer associat del disc i BD
            $movie->file->diskDelete();
        }

        // Eliminar post de BD
        $movie->delete();

        // Patró PRG amb missatge d'èxit
        return redirect()->route("pages-home")
            ->with('success', __('Movie successfully deleted'));
    }


    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    public function update_post(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    public function showHomePage()
    {
        $movies = Movie::all();
        $files = File::all();
        return view('pages-home', compact('movies', 'files'));
    }
}