<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'topic_id',
        'title',
        'description',
        'youtube_url',
        'youtube_id',
        'thumbnail_url',
        'is_active',
        'access_type',
        'sort_order',
    ];

    public function isMembershipRequired(): bool
    {
        return $this->access_type === 'membership';
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function parseYoutubeId(string $url): ?string
    {
        $url = trim($url);
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return $match[1] ?? null;
    }
}
