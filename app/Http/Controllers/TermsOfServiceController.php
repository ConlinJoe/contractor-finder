<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsOfServiceController extends Controller
{
    public function index()
    {
        return view('terms-of-service', [
            'title' => 'Terms of Service - WeSpeak Verify',
            'description' => 'Read our terms of service and user agreement.'
        ]);
    }
}
