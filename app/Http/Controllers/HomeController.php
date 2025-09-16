<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('home', [
            'title' => 'WeSpeak Verify - AI-Powered Contractor Verification',
            'description' => 'Find reliable contractors with our AI-powered platform. Verify credentials, check reviews, and get comprehensive reports in seconds.',
            'companyName' => $request->get('companyName'),
            'city' => $request->get('city'),
            'state' => $request->get('state')
        ]);
    }
}
