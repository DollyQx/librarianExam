<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubtReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'doubt_id',
        'user_id',
        'reply_text',
    ];

    public function doubt()
    {
        return $this->belongsTo(Doubt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
