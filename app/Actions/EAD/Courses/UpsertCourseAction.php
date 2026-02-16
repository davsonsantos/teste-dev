<?php

namespace App\Actions\EAD\Courses;

use App\Models\Course;
use Illuminate\Support\Facades\DB;

class UpsertCourseAction
{
    public function execute(array $data, ?Course $course = null): Course
    {
        return DB::transaction(function () use ($data, $course) {
            return Course::updateOrCreate(
                ['id' => $course?->id],
                [
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'start_date'  => $data['start_date'],
                    'end_date'    => $data['end_date'],
                ]
            );
        });
    }
}
