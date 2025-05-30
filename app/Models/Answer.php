<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'question_id',
        'is_solution'
    ];

    /**
     * Get the user that owns the answer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question that owns the answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}