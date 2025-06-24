<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
  protected $table = 'sessions';  // The table associated with the model
  protected $fillable = ['user_id', 'token'];

  public static function session_user()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $session = Session::where('token', '=', $_SESSION['SESSION_TOKEN'] ?? null)->first();
    if ($session != null) {
      return User::where('user_id', '=', $session->user_id)->first();
    }

    return null;
  }
}
