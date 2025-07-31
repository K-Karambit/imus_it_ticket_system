<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $fillable = ['group_name'];


  function getCreatedAtAttribute($value)
  {
    return date('m/d/Y h:i A', strtotime($value));
  }
}
