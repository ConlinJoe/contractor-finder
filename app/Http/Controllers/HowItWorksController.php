<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HowItWorksController extends Controller
{
    public function index()
    {
        return view('how-it-works', [
            'title' => 'How It Works - WeSpeak Verify',
            'description' => 'Learn how our AI-powered contractor verification platform works to help you find reliable professionals.'
        ]);
    }
}
