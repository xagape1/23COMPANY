<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ModelNotFoundException;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Movie::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'required|mimes:gif,jpeg,jpg,png,mp4',
            'intro' => 'required|mimes:gif,jpeg,jpg,png,mp4',
        ]);
        // Desar fitxer al disc i inserir dades a BD
        $cover = $request->file('cover');
        $fileName = $cover->getClientOriginalName();
        $fileSize = $cover->getSize();

        $intro = $request->file('intro');
        $fileName = $intro->getClientOriginalName();
        $fileSize = $intro->getSize();

        $title = $request->get('title');
        $description = $request->get('description');
        $gender = $request->get('gender');

        $uploadName = time() . '_' . $fileName;
        $filePath = $cover->storeAs(
            'uploads',
            // Path
            $uploadName,
            // Filename
            'public' // Disk
        );

        $filePath = $intro->storeAs(
            'uploads',
            // Path
            $uploadName,
            // Filename
            'public' // Disk
        );

        if (\Storage::disk('public')->exists($filePath)) {
            \Log::debug("Local storage OK");
            $fullPath = \Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");
            // Desar dades a BD
            $cover = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);

            $intro = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);

            $movie = Movie::create([
                'title' => $title,
                'description' => $description,
                'gender' => $gender,
                'cover_id' => $cover->id,
                'intro_id' => $intro->id,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return response()->json([
                'success' => true,
                'data' => $movie
            ], 201);
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return response()->json([
                'success' => false,
                'message' => 'Error creating post'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::find($id);
        Log::debug($id);
        Log::debug($movie);
        if ($movie) {
            return response()->json([
                'success' => true,
                'data' => $movie
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "not found"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        return view("movies.edit", [
            'movie' => $movie,
            'file' => $movie->file,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    use Illuminate\Support\Facades\Log;

    public function update(Request $request, Movie $movie)
    {
        // Valida los datos recibidos
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'nullable|mimes:gif,jpeg,jpg,png',
            'intro' => 'nullable|mimes:gif,jpeg,jpg,png',
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $gender = $request->input('gender');
        $coverFile = $request->file('cover');
        $introFile = $request->file('intro');

        // Actualiza los datos de la película
        $movie->title = $title;
        $movie->description = $description;
        $movie->gender = $gender;

        // Maneja la actualización de la portada (cover) si se proporciona un nuevo archivo
        if ($coverFile) {
            // Agrega lógica para almacenar el nuevo archivo y actualizar la base de datos
            if ($movie->cover->diskSave($coverFile)) {
                // Éxito al almacenar el archivo
            } else {
                // Error al almacenar el archivo
                return redirect()->route('movies.edit', $movie)
                    ->with('error', __('Error uploading cover file'));
            }
        }

        // Maneja la actualización de la introducción (intro) si se proporciona un nuevo archivo
        if ($introFile) {
            // Agrega lógica para almacenar el nuevo archivo y actualizar la base de datos
            if ($movie->intro->diskSave($introFile)) {
                // Éxito al almacenar el archivo
            } else {
                // Error al almacenar el archivo
                return redirect()->route('movies.edit', $movie)
                    ->with('error', __('Error uploading intro file'));
            }
        }

        // Guarda los cambios en la película
        $movie->save();

        // Redirige con un mensaje de éxito
        return redirect()->route('movies.show', $movie)
            ->with('success', __('Movie successfully updated'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::find($id);
        if (empty($movie)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], 404);
        }

        if ($movie) {
            $movie->delete();

            $intro = File::find($movie->intro_id);

            $cover = File::find($movie->cover_id);
            $cover && $intro->diskDelete();

            return response()->json([
                'success' => true,
                'data' => $movie
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file'
            ], 500);
        }
    }

    public function update_workaround(Request $request, $id)
    {
        return $this->update($request, $id);
    }

}