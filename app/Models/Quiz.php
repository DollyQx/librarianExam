<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'topic_id',
        'title',
        'slug',
        'description',
        'type',
        'duration_minutes',
        'pass_percentage',
        'marks_per_question',
        'negative_marking_per_question',
        'is_active',
        'sort_order',
    ];

    public function hasValidQuestions(): bool
    {
        if ($this->questions()->count() === 0) {
            return false;
        }

        foreach ($this->questions as $question) {
            if ($question->options()->where('is_correct', true)->count() === 0) {
                return false;
            }
        }

        return true;
    }

    protected $casts = [
        'is_active' => 'boolean',
        'pass_percentage' => 'float',
        'marks_per_question' => 'float',
        'negative_marking_per_question' => 'float',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order', 'asc');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
