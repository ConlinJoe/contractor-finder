<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        return view('pricing', [
            'title' => 'Pricing - WeSpeak Verify',
            'description' => 'Choose the right plan for your contractor verification needs. Start free or upgrade for unlimited access.'
        ]);
    }
}
