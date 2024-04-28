<?php

namespace App\Services;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Models\meetings;
use App\Models\meeting_attendees;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Interfaces\GoogleCalendarServiceInterface;

class GoogleCalendarService implements GoogleCalendarServiceInterface
{
    protected $client;

    public function __construct(Google_Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        echo "hello";
    }
    public function createEvent($calendarId, $event)
    {
        $this->client->setAccessToken(Session::get('access_token'));
        $service = new \Google_Service_Calendar($this->client);
        return $service->events->insert($calendarId, $event);
    }
    public function updateEvent($calendarId, $eventId, $event)
    {
        $this->client->setAccessToken(Session::get('access_token'));
        $service = new Google_Service_Calendar($this->client);
        return $service->events->update($calendarId, $eventId, $event);
    }
    public function deleteEvent($calendarId, $eventId)
    {
        $this->client->setAccessToken(Session::get('access_token'));
        $service = new Google_Service_Calendar($this->client);
        return $service->events->delete($calendarId, $eventId);
    }
}
