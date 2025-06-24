<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configurations';  // The table associated with the model
    protected $fillable = ['api', 'api_key', 'ip'];
    protected $hidden = ['created_at', 'updated_at'];
}
