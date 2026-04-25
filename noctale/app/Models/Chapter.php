<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['novel_id', 'title', 'image', 'content', 'chapter_number', 'views', 'publish_status', 'scheduled_at'];

    public function novel() { return $this->belongsTo(Novel::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function readingHistories() { return $this->hasMany(ReadingHistory::class); }

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('publish_status', 'published')
              ->orWhere(function ($sq) {
                  $sq->where('publish_status', 'scheduled')
                     ->where('scheduled_at', '<=', now());
              });
        });
    }
}
