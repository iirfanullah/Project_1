<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Meeting;
use App\Models\MeetingAttendee;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_meeting()
    {
        // Create a user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Define meeting data
        $meetingData = [
            'subject' => 'Test Meeting',
            'start_date_time' => now()->addDay(),
            'end_date_time' => now()->addDay(),
            'attendees' => ['attendee1@example.com', 'attendee2@example.com'],
        ];

        // Make a POST request to store the meeting
        $response = $this->post(route('meetings.store'), $meetingData);

        // Assert that the meeting was successfully stored in the database
        $this->assertDatabaseHas('meetings', [
            'subject' => 'Test Meeting',
            'start_date_time' => $meetingData['start_date_time'],
            'end_date_time' => $meetingData['end_date_time'],
            'creator_id' => $user->id,
        ]);

        // Assert that the attendees were successfully stored in the database
        foreach ($meetingData['attendees'] as $attendee) {
            $this->assertDatabaseHas('meeting_attendees', [
                'email' => $attendee,
            ]);
        }

        // Assert that the user is redirected to the meetings index page after creating the meeting
        $response->assertRedirect(route('home'));

        // Assert that the success message is flashed
        $response->assertSessionHas('status', 'Meeting created successfully!');
    }
}