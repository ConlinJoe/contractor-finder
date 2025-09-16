<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact', [
            'title' => 'Contact Us - WeSpeak Verify',
            'description' => 'Get in touch with our team for support, questions, or feedback about our contractor verification platform.'
        ]);
    }
}
