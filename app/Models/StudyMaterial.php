<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'topic_id',
        'title',
        'description',
        'file_path',
        'file_size',
        'downloads_count',
        'is_active',
        'is_paid',
        'access_type',
        'price',
        'sort_order',
    ];

    public function isMembershipRequired(): bool
    {
        return $this->access_type === 'membership' || (bool) $this->is_paid;
    }

    protected $casts = [
        'is_active' => 'boolean',
        'is_paid' => 'boolean',
        'price' => 'float',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
