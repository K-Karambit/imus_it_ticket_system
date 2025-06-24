<?php

namespace Models;
// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';  // The table associated with the model
    protected $fillable = ['ticket_id', 'rated_by', 'question', 'rate'];

    protected $appends = ['date_added'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
}
