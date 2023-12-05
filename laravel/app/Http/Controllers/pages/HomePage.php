<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\File;
use Illuminate\Http\Request;

class HomePage extends Controller
{
  public function index()
  {
    return view('content.pages.pages-home',[
      "movies" => Movie::all(),
      "files" => File::all(),
    ]);
  }
}
