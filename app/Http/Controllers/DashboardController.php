<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Métricas Gerais
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses  = Course::count();

        // 2. Matrículas agrupadas por status
        $enrollmentsByStatus = Enrollment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusData = [
            'pendente'  => $enrollmentsByStatus['pendente'] ?? 0,
            'cursando'  => $enrollmentsByStatus['cursando'] ?? 0,
            'concluido' => $enrollmentsByStatus['concluido'] ?? 0,
            'cancelado' => $enrollmentsByStatus['cancelado'] ?? 0,
        ];

        // 3. Dados para o Gráfico de Evolução (Últimos 6 meses)
        $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('Y-m'));
        
        // Busca matrículas dos últimos 6 meses e agrupa no PHP (Garante compatibilidade com MySQL/SQLite)
        $trendRecords = Enrollment::where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn($val) => $val->created_at->format('Y-m'));

        $chartTrendData = [
            // Formata rótulos para o gráfico (Ex: "08/2023")
            'labels' => $months->map(fn($m) => Carbon::parse($m . '-01')->format('m/Y'))->values(),
            // Conta as matrículas por mês
            'data'   => $months->map(fn($m) => isset($trendRecords[$m]) ? $trendRecords[$m]->count() : 0)->values(),
        ];

        // 4. Últimas Matrículas (Feed)
        $recentEnrollments = Enrollment::with(['student:id,name,email,avatar_path', 'course:id,name'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'students' => $totalStudents,
                'teachers' => $totalTeachers,
                'courses'  => $totalCourses,
                'status'   => $statusData,
            ],
            'chartTrendData'    => $chartTrendData,
            'recentEnrollments' => $recentEnrollments,
        ]);
    }
}