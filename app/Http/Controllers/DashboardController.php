<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'superadmin') {
            return view('dashboard', ['userType' => 'Super Admin']);
        }

        return view('dashboard', ['userType' => 'Admin']);
    }
}
