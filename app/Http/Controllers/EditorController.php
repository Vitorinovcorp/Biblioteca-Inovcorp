<?php

namespace App\Http\Controllers;

use App\Models\Editor;

class EditorController extends Controller
{
    public function index()
    {
        return view('editoras');
    }
}