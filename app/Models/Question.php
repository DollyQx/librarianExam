<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'explanation',
        'marks',
        'order',
    ];

    protected $casts = [
        'marks' => 'float',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class)->orderBy('order', 'asc');
    }

    public function correctOption()
    {
        return $this->hasOne(QuizOption::class)->where('is_correct', true);
    }
}
