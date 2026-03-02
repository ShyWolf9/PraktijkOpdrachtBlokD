<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('dashboard.index', compact('user'));
    }

    public function admin()
    {
        return view('dashboard.admin');
    }

    public function seller()
    {
        return view('dashboard.seller');
    }

    public function user()
    {
        return view('dashboard.user');
    }
}
