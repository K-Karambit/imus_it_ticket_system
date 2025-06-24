<?php

namespace Models;
// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';  // The table associated with the model
    protected $fillable = ['name', 'description'];

    protected $appends = ['date_added'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
}
