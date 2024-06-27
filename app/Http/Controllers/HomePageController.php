<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    /**
     * Show the application's homepage.
     */
    public function index()
    {
        return
            redirect()->away('http://localhost:3000/homepage');
    }
}
