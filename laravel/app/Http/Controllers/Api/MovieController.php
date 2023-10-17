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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Encuentra la película por su ID
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        // Encuentra los archivos de portada e introducción relacionados con la película
        $coverFile = File::find($movie->cover_id);
        $introFile = File::find($movie->intro_id);
        if (!$coverFile || !$introFile) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        // Valida los datos recibidos
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'gender' => 'required',
            'cover' => 'required|mimes:gif,jpeg,jpg,png',
            'intro' => 'required|mimes:gif,jpeg,jpg,png',
        ]);

        // Obtén los archivos del formulario
        $cover = $request->file('cover');
        $intro = $request->file('intro');

        // Sube los archivos al disco
        $coverFileName = time() . '_' . $cover->getClientOriginalName();
        $coverFilePath = $cover->storeAs('uploads', $coverFileName, 'public');

        $introFileName = time() . '_' . $intro->getClientOriginalName();
        $introFilePath = $intro->storeAs('uploads', $introFileName, 'public');

        // Verifica la existencia de los archivos en el almacenamiento local
        if (\Storage::disk('public')->exists($coverFilePath) && \Storage::disk('public')->exists($introFilePath)) {
            // Actualiza los datos de los archivos en la base de datos
            $coverFile->filepath = $coverFileName;
            $coverFile->filesize = $cover->getSize();
            $coverFile->save();

            $introFile->filepath = $introFileName;
            $introFile->filesize = $intro->getSize();
            $introFile->save();

            // Actualiza los datos de la película en la base de datos
            $movie->title = $request->input('title');
            $movie->description = $request->input('description');
            $movie->gender = $request->input('gender');
            $movie->save();

            return response()->json([
                'success' => true,
                'data' => $movie
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error updating file'
            ], 421);
        }
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