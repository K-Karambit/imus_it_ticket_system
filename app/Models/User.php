<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users';  // The table associated with the model
  protected $fillable = ['user_id', 'first_name', 'middle_name', 'last_name', 'username', 'password', 'role', 'status', 'email', 'phone', 'profile', 'group_id'];
  protected $hidden = ['password'];
  protected $appends = ['full_name', 'date_added', 'user_profile', 'user_role', 'group_name'];

  function getFullNameAttribute()
  {
    $first_name = !empty($this->first_name) ? $this->first_name : null;
    $last_name = !empty($this->last_name) ? $this->last_name : null;
    $middle_name = !empty($this->middle_name) ? $this->middle_name : null;

    $full_name =  "$first_name $middle_name $last_name";

    return !is_null($full_name) ? ucwords(strtolower($full_name)) : 'User not found.';
  }

  function getDateAddedAttribute()
  {
    return date('m/d/Y h:i A', strtotime($this->created_at));
  }

  function getUserRoleAttribute()
  {
    return Role::where('id', $this->role)->first()->name ?? 'User has no role.';
  }
  function getUserProfileAttribute()
  {
    $helper = new Helper();

    if (file_exists($helper->storage_path($this->profile)) && !empty($this->profile)) {
      return $helper->storage_url($this->profile);
    }

    return $helper->storage_url('default-profile.jpg');
  }

  function getGroupNameAttribute()
  {
    return Group::where('group_id', $this->group_id)->first()->group_name ?? 'User has no group assigned.';
  }
}
