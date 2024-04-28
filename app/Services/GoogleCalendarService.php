<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\Session;
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
