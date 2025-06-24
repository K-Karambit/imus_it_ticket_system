<?php

namespace Models;

// Define your User model (matching the users table in the database)

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';  // The table associated with the model
    protected $fillable = ['category_name'];
    protected $hidden = [];
    protected $appends = ['date_added'];


    function getDateAddedAttribute()
    {
        return Carbon::parse($this->created_at)->format('F/d/Y h:i A');
    }
}
