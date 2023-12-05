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
    public function index()
    {

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
            'cover' => $movie->cover,
            'intro' => $movie->intro,
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
        // Validar dades del formulari
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'required|mimes:gif,jpeg,jpg,png,mp4',
            'intro' => 'required|mimes:gif,jpeg,jpg,png,mp4',
        ]);

        // Obtenir dades del formulari
        $title = $request->get('title');
        $description = $request->get('description');
        $gender = $request->get('gender');
        $cover = $request->file('cover');
        $intro = $request->file('intro');

        // Desar fitxer (opcional)
        if (is_null($cover && $intro) || $movie->file->diskSave($cover) && $movie->file->diskSave($intro)) {
            Log::debug("Updating DB...");
            $movie->title = $title;
            $movie->description = $description;
            $movie->gender = $gender;
            $movie->save();
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('movies.show', $movie)
                ->with('success', __('Post successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("movies.edit")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        // Eliminar post de BD
        $movie->delete();
        // Eliminar fitxer associat del disc i BD
        $movie->file->diskDelete();
        // Patró PRG amb missatge d'èxit
        return redirect()->route("/")
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