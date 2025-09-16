<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        return view('privacy-policy', [
            'title' => 'Privacy Policy - WeSpeak Verify',
            'description' => 'Learn about our privacy policy and how we protect your data.'
        ]);
    }
}
