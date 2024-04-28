<?php

namespace App\Http\Controllers\Meeting;

use Carbon\Carbon;
use Google_Client;
use App\Models\meetings;
use Illuminate\Http\Request;
use App\Models\meeting_attendees;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\GoogleCalendarServiceInterface;

class MeetingController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarServiceInterface $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'subject' => 'required|string',
                'date_time' => 'required|date',
                'attendees' => 'required|array|max:2',
            ]);

            // Prepare Meeting data
            $startDateTime = Carbon::parse($request->date_time)->format('Y-m-d\TH:i:sP');
            $endDateTime = Carbon::parse($request->date_time)->addHour()->format('Y-m-d\TH:i:sP');
            $event = new \Google_Service_Calendar_Event([
                'summary' => $request->subject,
                'description' => '',
                'start' => ['dateTime' => $startDateTime],
                'end' => ['dateTime' => $endDateTime],
                'reminders' => ['useDefault' => true],
            ]);
            // Create event using Google Calendar service
            $results = $this->googleCalendarService->createEvent('primary', $event);

            // Save meeting details im database:
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
            return redirect()->route('home')->with('error', 'Meeting creating Failed!');
        }
    }

    public function edit($meeting_id)
    {
        $meeting = meetings::find($meeting_id);
        return view('edit', ['meeting' => $meeting]);
    }

    public function update(Request $request, $meeting_id)
    {
        try {
            $data = $request->validate([
                'subject' => 'required|string',
                'date_time' => 'required|date',
                'attendees' => 'required|array|max:2',
            ]);

            $meeting = meetings::find($meeting_id);

            // Prepare event data
            $startDateTime = Carbon::parse($request->date_time)->format('Y-m-d\TH:i:sP');
            $endDateTime = Carbon::parse($request->date_time)->addHour()->format('Y-m-d\TH:i:sP');

            // Create event object for update
            $event = new \Google_Service_Calendar_Event([
                'summary' => $request->subject,
                'start' => ['dateTime' => $startDateTime],
                'end' => ['dateTime' => $endDateTime],
            ]);

            // Update event on Google Calendar
            $this->googleCalendarService->updateEvent('primary', $meeting->google_event_id, $event);

            // Update meeting details in the local database
            $meeting->update([
                'subject' => $request->subject,
                'date_time' => $request->date_time,
            ]);

            // Update attendees in the local database
            $meeting->attendees()->delete();
            foreach ($request->attendees as $email) {
                $meeting->attendees()->create(['email' => $email]);
            }

            return redirect()->route('home')->with('success', 'Meeting updated successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('home')->with('error', 'Meeting updating Failed!');
        }
    }

    public function destroy($meeting_id)
    {
        $meeting = meetings::find($meeting_id);

        // Check if the current user is the creator of the meeting
        if ($meeting->creator_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Delete event from Google Calendar
        $this->googleCalendarService->deleteEvent('primary', $meeting->google_event_id);

        // Delete attendees related to the meeting
        $meeting->attendees()->delete();

        // Delete meeting from local database
        $meeting->delete();

        return redirect()->route('home')->with('status', 'Meeting deleted successfully');
    }
}
