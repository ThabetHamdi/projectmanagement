<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $managers = User::where('role', 'manager')->count();
        $developers = User::where('role', 'developer')->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'admins' => $admins,
                'managers' => $managers,
                'developers' => $developers
            ],
            'users' => User::all()
        ]);
    }
}
