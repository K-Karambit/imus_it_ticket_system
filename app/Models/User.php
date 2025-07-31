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
  protected $with = ['group'];

  function getFullNameAttribute()
  {
    $full_name =  "{$this->first_name} {$this->middle_name} {$this->last_name}";
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
    return $this->group?->group_name ?? 'No group';
  }
  function group()
  {
    return $this->hasOne(Group::class, 'group_id', 'group_id');
  }
}
