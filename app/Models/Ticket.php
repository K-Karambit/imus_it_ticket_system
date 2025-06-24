<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';  // The table associated with the model
    protected $fillable = ['ticket_id', 'user_id', 'subject', 'description', 'department', 'urgency', 'status', 'added_by', 'category'];
    protected $appends = ['date_added', 'assigned_user', 'short_description', 'department_name', 'added_by_name', 'category_name'];

    function getDateAddedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }
    function getAssignedUserAttribute()
    {
        return User::where('user_id', '=', $this->user_id)->first()->full_name ?? 'No User Assigned';
    }
    function getDepartmentNameAttribute()
    {
        return ucwords(strtolower(Department::where('id', $this->department)->first()->name ?? 'No Selected Department'));
    }
    function getShortDescriptionAttribute()
    {
        return htmlspecialchars_decode($this->subject);
    }
    function getTicketStatesAttribute()
    {
        $states = State::where('ticket_id', '=', $this->ticket_id)->get(['status', 'note', 'created_at']);
        $data = "$this->date_added | New | Date Created.\n\n";
        if ($states != null) {
            foreach ($states as $key => $state) {
                $count = $key + 1;
                $note = ucfirst($state->note);
                $date_added = date('m/d/Y h:i A', strtotime($state->created_at));
                $data .= "$date_added | $state->status | $note | $state->updated_by_name.\n\n";
            }
            return $data;
        }
        return null;
    }
    function getAddedByNameAttribute()
    {
        return User::where('user_id', '=', $this->added_by)->first()->full_name ?? 'No Information';
    }
    function getCategoryNameAttribute()
    {
        return Category::where('id', $this->category)->first()->category_name ?? 'No Category';
    }
}
