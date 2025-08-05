<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class FarmerHelpController extends Controller
{

    public function index()
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        return view('farmer.help', compact('notifications'));

    }
}
