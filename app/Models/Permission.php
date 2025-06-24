<?php

namespace Models;
// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';  // The table associated with the model
    protected $fillable = ['module', 'read_access', 'write_access', 'delete_access', 'role_id'];
    protected $hidden = ['çreated_at', 'updated_at'];
    protected $appends = ['date_added'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
}
