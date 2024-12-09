<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    // Table name (optional, if different from the plural form of the model name)
    protected $table = 'sessions';

    public $timestamps = false;

    // Fillable columns (columns that can be mass-assigned)
    protected $fillable = [
        'id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
        'sessionable_id',
        'sessionable_type'
    ];

    // Specify the polymorphic relationship with the "sessionable" entity
    public function sessionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Each session can belong to a user
    }
}
