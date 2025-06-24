<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';  // The table associated with the model
    protected $fillable = ['name', 'status'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['date_added'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
}
