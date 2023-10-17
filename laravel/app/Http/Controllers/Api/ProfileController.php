<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;

use App\Models\Profile;
use App\Models\File;

use Illuminate\Http\Request;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $profiles = $user->profiles;

        return response()->json([
            'success' => true,
            'data' => $profiles
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'upload' => 'required|mimes:gif,jpeg,jpg,png,mp4',
        ]);


        $upload = $request->file('upload');
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            $profile = Profile::create([
                'name' => $request->get('name'),
                'author_id' => auth()->user()->id,
                'file_id' => $file->id,
            ]);
            return response()->json([
                'success' => true,
                'data' => $profile
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file'
            ], 421);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::find($id);
        Log::debug($id);
        Log::debug($profile);
        if ($profile) {
            return response()->json([
                'success' => true,
                'data' => $profile
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
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        $profile = Profile::find($id);
        if (empty($profile)) {
            return response()->json([
                'success' => false,
                'message' => 'Error not found'
            ], 404);
        }
        
        // Verificar si el perfil pertenece al usuario autenticado
        if ($profile->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $validatedData = $request->validate([
            'name' => 'required',
            'upload' => 'required|mimes:gif,jpeg,jpg,png,mp4',
        ]);
    
        // Obtener datos del formulario
        $name = $request->get('name');
        $upload = $request->file('upload');
    
        // Guardar en la base de datos
        $profile->name = $name;
        $profile->save();
    
        // Actualizar archivo
        $file = File::find($profile->file_id);
        $ok = $file->diskSave($upload);
    
        if ($ok) {
            return response()->json([
                'success' => true,
                'data' => $profile
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file'
            ], 421);
        }
    }
    
    /**
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $profile = Profile::find($id);
        if (empty($profile)) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], 404);
        }
        if ($profile) {
            $profile->delete();
            $file=File::find($profile->file_id);
            $file->diskDelete();
            return response()->json([
                'success' => true,
                'data' => $profile
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file'
            ], 500);
        }

    }
}