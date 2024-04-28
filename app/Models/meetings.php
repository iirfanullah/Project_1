<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class meetings extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'date_time', 'creator_id', 'google_event_id', 'google_id', 'google_access_token', 'google_refresh_token'
    ];

    public function attendees(){
        return $this->hasMany(meeting_attendees::class, 'meeting_id', 'id');
    }
}
