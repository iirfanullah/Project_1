<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use App\Models\meetings;
use App\Models\meeting_attendees;
use Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'required|string',
                'date_time' => 'required|date',
                'attendees' => 'required|array|max:2',
            ]);
            $startDateTime = Carbon::parse($request->date_time)->format('Y-m-d\TH:i:sP');
            $endDateTime = Carbon::parse($request->date_time)->addHour()->format('Y-m-d\TH:i:sP');
            $client = new Google_Client();
            $client->setAccessToken(session()->get('access_token'));

            $service = new \Google_Service_Calendar($client);
            $calendarId = 'primary';
            $event = new \Google_Service_Calendar_Event([
                'summary' => $request->subject,
                'description' => '',
                'start' => ['dateTime' => $startDateTime,],
                'end' => ['dateTime' => $endDateTime,],
                'reminders' => ['useDefault' => true],
            ]);
            $results = $service->events->insert($calendarId, $event);
            $meeting = new meetings();
            $meeting->subject = $request->subject;
            $meeting->date_time = $request->date_time;
            $meeting->creator_id = Auth::id();
            $meeting->google_event_id = $results->id;
            $meeting->save();
            foreach ($request->attendees as $email) {
                $attendee = new meeting_attendees();
                $attendee->meeting_id = $meeting->id;
                $attendee->email = $email;
                $attendee->save();
            }
            return redirect()->route('home')->with('status', 'Meeting created successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('home')->with('error', 'Google authentication failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($meeting_id)
    {
        $meeting = meetings::find($meeting_id);
        return view('edit', ['meeting' => $meeting]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $meeting_id)
    {
        $data = $request->validate([
            'subject' => 'required|string',
            'date_time' => 'required|date',
            'attendees' => 'required|array|max:2',
        ]);
        $meeting = meetings::find($meeting_id);
        $client = new Google_Client();
        $client->setAccessToken(session()->get('access_token'));
        $service = new \Google_Service_Calendar($client);
        $startDateTime = Carbon::parse($request->date_time)->format('Y-m-d\TH:i:sP');
        $endDateTime = Carbon::parse($request->date_time)->addHour()->format('Y-m-d\TH:i:sP');
        $event = $service->events->get('primary', $meeting->google_event_id);
        $event->setSummary($request->subject);

        $start = new \Google_Service_Calendar_EventDateTime();
        $start->setDateTime($startDateTime);
        $event->setStart($start);

        $end = new \Google_Service_Calendar_EventDateTime();
        $end->setDateTime($endDateTime);
        $event->setEnd($end);
        $service->events->update('primary', $event->getId(), $event);

        $meeting->subject = $request->subject;
        $meeting->date_time = $request->date_time;
        $meeting->update($data);
        $meeting->attendees()->update(['email' => $request->attendees]);
        return redirect()->route('home')->with('success', 'Meeting updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($meeting_id)
    {
        $meeting = meetings::find($meeting_id);
        if ($meeting->creator_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $client = new Google_Client();
        $client->setAccessToken(session()->get('access_token'));
        $service = new \Google_Service_Calendar($client);
        $service->events->delete('primary', $meeting->google_event_id);
        $meeting->attendees()->delete();
        $meeting->delete();
        return redirect()->route('home')->with('status', 'Meeting deleted successfully');
    }
}
