<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SurveyQuestion;

class Survey extends Model
{
    use HasFactory;

    protected $table    = 'surveys';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'public_token', 'organization_id', 'user_id',
        'title', 'description', 'start_date', 'end_date', 'survey_closed', 'is_anonymous', 'notify_on_answer',
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'survey_closed' => 'boolean',
        'is_anonymous' => 'boolean',
        'notify_on_answer' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
