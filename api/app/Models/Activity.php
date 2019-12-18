<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $fillable = [
        'activity', 'status_id', 'deadline'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'activities_users', 'activity_id', 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
