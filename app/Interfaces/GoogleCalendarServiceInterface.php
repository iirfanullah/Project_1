<?php

namespace App\Interfaces;

interface GoogleCalendarServiceInterface
{
    public function createEvent($calendarId, $event);
    public function updateEvent($calendarId, $eventId, $event);
    public function deleteEvent($calendarId, $eventId);
}
