<?php

namespace App\Interfaces;

use Carbon\Carbon;
use Google_Client;
use App\Models\meetings;
use App\Models\meeting_attendees;
use Auth;
use Illuminate\Http\Request;

interface GoogleCalendarServiceInterface
{
    public function index();
    public function createEvent($calendarId, $event);
    public function updateEvent($calendarId, $eventId, $event);
    public function deleteEvent($calendarId, $eventId);
}
