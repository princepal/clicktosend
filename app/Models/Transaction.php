<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'status',
        'bambora_profile_id',
        'bambora_recurring_id',
        'transaction_reference',
        'response',
    ];
} 