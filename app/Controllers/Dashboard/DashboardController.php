<?php

namespace App\Controllers\Dashboard;

use App\Models\User;
use App\Models\Role;
use App\Models\Donor;
use App\Core\Http\Request;
use App\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => count(User::all()),
            'total_roles' => count(Role::all()),
            'total_donors' => count(Donor::all()),
            'active_sessions' => 24, // Placeholder
            'pending_tasks' => 7    // Placeholder
        ];

        // Fetch recent users (last 5)
        $users = User::all();
        usort($users, fn($a, $b) => $b->id <=> $a->id);
        $recentUsers = array_slice($users, 0, 5);

        return view('Dashboard.index', [
            'stats' => $stats,
            'recentUsers' => $recentUsers
        ]);
    }
}
