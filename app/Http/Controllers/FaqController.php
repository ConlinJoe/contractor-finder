<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('faq', [
            'title' => 'FAQ - WeSpeak Verify',
            'description' => 'Frequently asked questions about our contractor verification platform.'
        ]);
    }
}
