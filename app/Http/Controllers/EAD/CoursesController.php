<?php

namespace App\Http\Controllers\EAD;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Actions\EAD\Courses\UpsertCourseAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy($request->input('sort_field', 'created_at'), $request->input('sort_direction', 'desc'))
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'filters' => $request->only(['search', 'sort_field', 'sort_direction']),
        ]);
    }

    public function store(Request $request, UpsertCourseAction $action)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $action->execute($data);

        return back()->with(['success' => 'Curso criado com sucesso!', 'display' => 'toast']);
    }

    public function update(Request $request, Course $course, UpsertCourseAction $action)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $action->execute($data, $course);

        return back()->with(['success' => 'Curso atualizado!', 'display' => 'toast']);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Curso removido com sucesso!');
    }
}
