<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('about', [
            'title' => 'About Us - WeSpeak Verify',
            'description' => 'Learn about WeSpeak Verify and our mission to help homeowners find reliable contractors.'
        ]);
    }
}
