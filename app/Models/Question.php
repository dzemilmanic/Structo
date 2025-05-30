<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'status',
        'views'
    ];

    /**
     * Get the user that owns the question.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get answer count for the question.
     */
    public function getAnswerCountAttribute()
    {
        return $this->answers->count();
    }

    /**
     * Scope a query to search questions.
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'LIKE', "%{$search}%")
                         ->orWhere('content', 'LIKE', "%{$search}%");
        }
        
        return $query;
    }

    /**
     * Scope a query to sort questions.
     */
    public function scopeSort($query, $sort)
    {
        switch ($sort) {
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
            case 'most_answers':
                return $query->withCount('answers')->orderBy('answers_count', 'desc');
            case 'views':
                return $query->orderBy('views', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }
}