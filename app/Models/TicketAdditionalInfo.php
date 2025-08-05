<?php

namespace Models;

// Define your User model (matching the users table in the database)
use Illuminate\Database\Eloquent\Model;

class TicketAdditionalInfo extends Model
{
    protected $fillable = [
        'claimant_name',
        'client_name',
        'contact_no',
        'particulars',
        'amount'
    ];
}
