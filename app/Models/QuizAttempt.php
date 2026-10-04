<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'total_questions',
        'attempted_questions',
        'correct_answers',
        'wrong_answers',
        'unattempted_questions',
        'marks_obtained',
        'max_marks',
        'percentage',
        'status',
        'started_at',
        'completed_at',
        'time_taken_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'marks_obtained' => 'float',
        'max_marks' => 'float',
        'percentage' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function getFormattedTimeTakenAttribute(): string
    {
        $seconds = $this->time_taken_seconds;
        $mins = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d', $mins, $secs);
    }
}
