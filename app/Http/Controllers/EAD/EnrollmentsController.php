<?php

namespace App\Http\Controllers\EAD;

use App\Actions\EAD\Enrollments\UpsertEnrollmentAction;
use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EnrollmentsController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $enrollments = Enrollment::query()
            ->with(['student:id,name,email', 'course:id,name'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('course', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Enrollments/Index', [
            'enrollments' => $enrollments,
            'courses'     => Course::all(['id', 'name']),
            'students'    => User::where('role', 'student')->get(['id', 'name']),
            'filters'     => $request->only(['search', 'status', 'sort_field', 'sort_direction']),
        ]);
    }

    public function store(Request $request, UpsertEnrollmentAction $action)
    {
        $data = $request->validate([
            'user_id'   => ['required', 'exists:users,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                })
            ],
            'status'    => ['required', 'in:pendente,cursando,concluido,cancelado'],
        ], [
            'user_id.required'   => 'Selecione um aluno.',
            'course_id.required' => 'Selecione um curso.',
            'course_id.unique'   => 'Este aluno já possui uma matrícula neste curso.',
        ]);

        $action->execute($data);

        return back()->with(['success' => 'Matrícula realizada com sucesso!', 'display' => 'toast']);
    }

    public function update(Request $request, Enrollment $enrollment, UpsertEnrollmentAction $action)
    {
        // Na edição, validamos apenas o status, pois bloqueamos a troca de aluno/curso no frontend
        $data = $request->validate([
            'status' => ['required', 'in:pendente,cursando,concluido,cancelado'],
        ]);

        // Injetamos os IDs originais para garantir que a Action não os sobrescreva com null
        $data['user_id'] = $enrollment->user_id;
        $data['course_id'] = $enrollment->course_id;

        $action->execute($data, $enrollment);

        return back()->with(['success' => 'Status da matrícula atualizado!', 'display' => 'toast']);
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Matrícula cancelada e removida!');
    }
}
