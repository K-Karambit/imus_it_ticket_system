<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';  // The table associated with the model
    protected $fillable = ['user_id', 'module', 'details'];
    protected $appends = ['date_created', 'log_details'];

    // Define the relationship between Activity and User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor to format created_at
    function getDateCreatedAttribute()
    {
        return date('m/d/Y h:i A', strtotime($this->created_at));
    }

    // Accessor for log details
    function getLogDetailsAttribute()
    {
        $user = User::where('user_id', '=', $this->user_id)->first();
        $user = $user == null ? 'Deleted user' : $user->full_name;
        return $user . ' ' . strtolower($this->details);
    }
    // Static method to add a new activity log entry
    public static function addActivityLog($module, $description, $user_id = null)
    {
        $user = Session::session_user();  // Call the static method to retrieve the User object

        $userid = $user_id == null ? $user->user_id : $user_id;

        // Create a new activity log entry
        $activity = new self(); // Use 'self' to refer to the current class (Activity)
        $activity->user_id = $userid;
        $activity->module = $module;
        $activity->details = $description;
        //   $activity->group_id = $user->group_id;
        $activity->save();
    }
}
