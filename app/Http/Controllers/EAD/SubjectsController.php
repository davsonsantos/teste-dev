<?php

namespace App\Http\Controllers\EAD;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
use App\Actions\EAD\Subjects\UpsertSubjectAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectsController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::query()
            ->with(['course', 'teacher']) // Assumindo relação 'teacher' para user_id no Model
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Subjects/Index', [
            'subjects' => $subjects,
            'courses'  => Course::all(['id', 'name']),
            'teachers' => User::where('role', 'teacher')->get(['id', 'name', 'avatar_path']),
            'filters'  => $request->only(['search']),
        ]);
    }

    public function store(Request $request, UpsertSubjectAction $action)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'course_id'   => 'required|exists:courses,id', // Removido nullable
            'user_id'     => 'required|exists:users,id',   // Removido nullable
        ], [
            'name.required'        => 'O nome da disciplina é obrigatório.',
            'description.required' => 'A descrição é obrigatória.',
            'course_id.required'   => 'Selecione um curso para esta disciplina.',
            'user_id.required'     => 'Selecione um professor responsável.',
        ]);

        $action->execute($data);
        return back()->with(['success' => 'Disciplina criada!', 'display' => 'toast']);
    }

    public function update(Request $request, Subject $subject, UpsertSubjectAction $action)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'course_id'   => 'required|exists:courses,id',
            'user_id'     => 'required|exists:users,id',
        ], [
            'course_id.required' => 'A disciplina deve estar vinculada a um curso.',
            'user_id.required'   => 'A disciplina deve ter um professor.',
        ]);

        $action->execute($data, $subject);
        return back()->with(['success' => 'Disciplina atualizada!', 'display' => 'toast']);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return back()->with('success', 'Disciplina removida!');
    }
}
