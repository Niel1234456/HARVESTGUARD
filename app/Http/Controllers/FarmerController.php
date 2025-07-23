<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    // Method for success page
    public function success()
    {
        // You can pass data to the view if needed
        return view('farmer.success');
    }
}
