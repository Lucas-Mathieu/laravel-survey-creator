<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Organization extends Model
{
    use HasFactory;

    protected $table    = 'organizations';
    public $timestamps  = true;
    protected $fillable = [ 'id', 'name', 'user_id', 'created_at', 'updated_at' ];
    protected $casts = [
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}
