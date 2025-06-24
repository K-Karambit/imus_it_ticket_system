<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class CMS extends Model
{
  protected $table = 'cms';  // The table associated with the model
  protected $fillable = ['sys_name', 'sys_logo'];
  protected $hidden = ['created_at', 'updated_at', 'id'];
}
