<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;
use Models\User;

class State extends Model
{
    protected $table = 'states';  // The table associated with the model
    protected $fillable = ['ticket_id', 'status', 'note', 'updated_by'];
    protected $hidden = ['notes'];
    protected $appends = ['date_added', 'details', 'updated_by_name'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
    function getDetailsAttribute()
    {
        return $this->note == null ? 'No Description' : ucfirst($this->note);
    }
    function getUpdatedByNameAttribute()
    {
        return User::where('user_id', '=', $this->updated_by)->first()->full_name ?? 'No information';
    }
}
