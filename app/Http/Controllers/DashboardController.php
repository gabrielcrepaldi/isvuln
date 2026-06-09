<?php

namespace App\Http\Controllers;

use App\Models\Vulnerability;          // THIS ONE
use Illuminate\Support\Facades\Auth;   // THIS LINE IS MISSING

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Base query respects role visibility (admins see all, others see own)
        $base = Vulnerability::visibleTo($user);

        $stats = [
            'total'    => (clone $base)->count(),
            'critical' => (clone $base)->where('severity', 'critical')->count(),
            'high'     => (clone $base)->where('severity', 'high')->count(),
            'medium'   => (clone $base)->where('severity', 'medium')->count(),
            'low'      => (clone $base)->where('severity', 'low')->count(),
            'open'     => (clone $base)->where('status', 'open')->count(),
            'resolved' => (clone $base)->where('status', 'resolved')->count(),
        ];

        // 5 most recent findings the user can see
        $recent = (clone $base)->with(['creator', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent'));
    }
}
