<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("files.index", [
            'files' => File::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("files.create");
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
            'upload' => 'required|mimes:gif,jpeg,jpg,png,mp4|max:104857600'
        ]);

        // Desar fitxer al disc i inserir dades a BD
        $upload = $request->file('upload');
        $file = new File();
        $ok = $file->diskSave($upload);

        if ($ok) {
            // Patró PRG amb missatge d'èxit
            return redirect()->route('files.show', $file)
                ->with('success', __('File successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("files.create")
                ->with('error', __('ERROR uploading file'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        return view("files.show", [
            'file' => $file
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        return view("files.edit", [
            'file' => $file
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png,mp4|max:104857600'
        ]);

        // Desar fitxer al disc i actualitzar dades a BD
        $upload = $request->file('upload');
        $ok = $file->diskSave($upload);

        if ($ok) {
            // Patró PRG amb missatge d'èxit
            return redirect()->route('files.show', $file)
                ->with('success', __('File successfully saved'));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("files.edit")
                ->with('error', __('ERROR uploading file'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        // Eliminar fitxer del disc i BD
        $file->diskDelete();
        // Patró PRG amb missatge d'èxit
        return redirect()->route("files.index")
            ->with('success', __("File succesfully deleted."));
    }
   
 
}
