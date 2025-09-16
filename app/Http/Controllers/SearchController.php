<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        return view('search', [
            'title' => 'Search Contractors - WeSpeak Verify',
            'description' => 'Search and verify contractors with our AI-powered platform. Get instant results with reviews, ratings, and license verification.',
            'companyName' => $request->get('companyName'),
            'city' => $request->get('city'),
            'state' => $request->get('state')
        ]);
    }
}
