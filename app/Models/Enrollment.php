<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'completed_at'
    ];

    /**
     * Relacionamento com o Aluno (User)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento com o Curso
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}