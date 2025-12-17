<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\User;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table    = 'organization_user';
    public $timestamps  = true;
    protected $fillable = [ 'id', 'user_id', 'organization_id', 'role', 'created_at', 'updated_at' ];
    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
