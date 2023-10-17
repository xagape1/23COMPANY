<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Movie;

use App\Http\Controllers\Controller;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Review::all(),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Movie $movie)
    {
        return view("review.create", [
            "movie" => $movie,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movie $movie)
    {
        // Verificar si la película existe
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found',
            ], 404);
        }
    
        $validatedData = $request->validate([
            'description' => 'required',
        ]);
    
        $description = $request->input('description');
        $user_id = auth()->user()->id;
    
        $review = new Review([
            'description' => $description,
            'user_id' => $user_id,
            'movie_id' => $movie->id,
        ]);
    
        $review->save();
    
        if ($review) {
            return response()->json([
                'success' => true,
                'data' => $review,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error creating review',
            ], 500);
        }
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie, Review $review)
    {
        $estrellas = $resena->stars;
        $id = auth()->id();
        return view("review.show", [
            'resena' => $resena,
            "place" => $place,
            'file' => $resena->file,
            'author' => $resena->user,
            "estrellas" => $estrellas,
            "id" => $id,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place, Resenas $resena)
    {
        if ($resena->author_id == auth()->id()) {
            // Eliminar place de BD
            $resena->delete();
            // Eliminar fitxer associat del disc i BD
            $resena->file->diskDelete();
            // Patró PRG amb missatge d'èxit
            return redirect()->route("places.review.index", $place)
                ->with('success', 'Resena successfully deleted');
        } else {
            return redirect()->route("places.review.show", [$place, $resena])
                ->with('error', __('No ets el propietari de la reseña'));
        }

    }
}