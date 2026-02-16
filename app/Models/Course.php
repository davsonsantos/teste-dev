<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date'
    ];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function students()
    {
        return $this->hasMany(Enrollment::class);
    }
}
