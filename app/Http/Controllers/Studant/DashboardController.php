<?php

namespace App\Http\Controllers\Studant;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $enrollments = Enrollment::with('course:id,name,description')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $activeEnrollments = $enrollments->where('status', 'cursando')->values();
        $completedEnrollments = $enrollments->where('status', 'concluido')->values();
        $pendingEnrollments = $enrollments->where('status', 'pendente')->values();

        return Inertia::render('Studant/Dashboard/Index', [
            'stats' => [
                'active'    => $activeEnrollments->count(),
                'completed' => $completedEnrollments->count(),
                'total'     => $enrollments->count(),
            ],
            'activeEnrollments'    => $activeEnrollments,
            'completedEnrollments' => $completedEnrollments,
            'pendingEnrollments'   => $pendingEnrollments,
        ]);
    }

    public function profile()
    {
        return Inertia::render('Studant/Profile/Index');
    }
}
