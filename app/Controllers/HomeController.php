<?php

namespace App\Controllers;

use App\Core\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
}
