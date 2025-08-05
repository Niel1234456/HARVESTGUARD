<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class AdminHelpController extends Controller
{

    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc', 'approval')
            ->take(20)
            ->get();
            
        return view('admin.help', compact('notifications'));

    }
}
