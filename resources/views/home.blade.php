@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success mb-3 alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (isset($error))
                    <div class="alert alert-danger mb-3 alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Welcome to Meeting Scheduler.') }}</div>

                    <div class="card-body">
                        @if (Auth::check() && session('access_token'))
                            <p class="m-0">Create your new <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#createMeetingModal">meeting</a></p>
                        @else
                            <a href="{{ route('google.login') }}" class="btn btn-primary">Connect Google Account</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your Meetings.') }}</div>

                    <div class="card-body">
                        <!-- Meetings Table Bootstrap Layout-->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Suject</th>
                                        <th scope="col">Date Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($meetings as $meeting)
                                        <tr>
                                            <th scope="row">{{ $meeting->id }}</th>
                                            <td>{{ $meeting->subject }}</td>
                                            <td>{{ $meeting->date_time }}</td>
                                            <td><a href="{{ route('meetings.edit', $meeting->id) }}"
                                                    class="btn btn-primary">Edit</a> | <button class="btn btn-danger"
                                                    onclick="deleteMeeting{{ $meeting->id }}.submit()">Delete</button></td>
                                        </tr>
                                        <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                                            id="deleteMeeting{{ $meeting->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Create New Meeting Model --}}
        <div class="modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMeetingModalLabel">Create New Meeting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('meetings.store') }}">
                        @csrf
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input id="subject" type="text" class="form-control" name="subject"
                                    value="{{ old('subject') }}" required autofocus>
                                @error('subject')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date_time">Start Date and Time</label>
                                <input id="date_time" type="datetime-local" class="form-control" name="date_time"
                                    value="{{ old('date_time') }}" required>
                                @error('date_time')
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group" id="attendees-container">
                                <label for="attendees">Attendees (Email addresses)</label>
                                <div class="attendee-input">
                                    <input type="email" class="form-control" name="attendees[]"
                                        value="{{ old('attendees.0') }}" required>
                                </div>
                                @error('attendees')
                                    <span class="text-danger" role="alert">
                                        <em>{{ $message }}</em>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="add-attendee">Add Attendee</button>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Meeting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('attendees-container');
            var addAttendeeButton = document.getElementById('add-attendee');

            addAttendeeButton.addEventListener('click', function() {
                var inputGroup = document.createElement('div');
                inputGroup.classList.add('attendee-input');
                inputGroup.innerHTML = '<input type="email" class="form-control" name="attendees[]">';

                container.appendChild(inputGroup);
            });
        });
    </script>
@endsection
