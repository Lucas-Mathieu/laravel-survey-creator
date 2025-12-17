<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table    = 'surveys';
    public $timestamps  = true;
    protected $fillable = [
        'id', 'organization_id', 'user_id',
        'title', 'description', 'start_date', 'end_date', 'survey_closed', 'is_anonymous',
        'created_at', 'updated_at'
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'survey_closed' => 'boolean',
        'is_anonymous' => 'boolean',
    ];
}
