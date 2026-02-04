<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoworkController extends Controller
{
    public function index()
    {
        return view('coworks.index');
    }
}
