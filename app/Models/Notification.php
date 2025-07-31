<?php

namespace Models;
// Define your User model (matching the users table in the database)

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['notif_for', 'notif_by', 'description', 'is_read', 'ticket_id', 'warn_level'];
    protected $appends = ['time'];

    function getTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
