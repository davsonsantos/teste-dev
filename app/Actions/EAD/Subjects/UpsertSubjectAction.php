<?php

namespace App\Actions\EAD\Subjects;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class UpsertSubjectAction
{
    public function execute(array $data, ?Subject $subject = null): Subject
    {
        return DB::transaction(function () use ($data, $subject) {
            return Subject::updateOrCreate(
                ['id' => $subject?->id],
                [
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'course_id'   => $data['course_id'] ?? null,
                    'user_id'     => $data['user_id'] ?? null,
                ]
            );
        });
    }
}
