<?php

namespace App\Actions\EAD\Enrollments;

use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class UpsertEnrollmentAction
{
    public function execute(array $data, ?Enrollment $enrollment = null): Enrollment
    {
        return DB::transaction(function () use ($data, $enrollment) {

            if (isset($data['status'])) {
                if ($data['status'] === 'concluido') {
                    $data['completed_at'] = $enrollment?->completed_at ?? now();
                } else {
                    $data['completed_at'] = null;
                }
            }

            return Enrollment::updateOrCreate(
                ['id' => $enrollment?->id],
                [
                    'user_id'      => $data['user_id'],
                    'course_id'    => $data['course_id'],
                    'status'       => $data['status'],
                    'completed_at' => $data['completed_at'] ?? null,
                ]
            );
        });
    }
}
