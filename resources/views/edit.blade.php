@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Meeting') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success mb-3 alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="name" name="subject"
                                    value="{{ $meeting->subject }}">
                                @error('subject')
                                    <em class="text-danger">{{ $message }}</em>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Date Time</label>
                                <input type="datetime-local" class="form-control" id="name" name="date_time"
                                    value="{{ $meeting->date_time }}">
                                @error('date_time')
                                    <em class="text-danger">{{ $message }}</em>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Attendees</label>
                                @foreach ($meeting->attendees as $attendee)
                                    <input type="email" class="form-control mt-2" id="name" name="attendees[]"
                                        value="{{ $attendee->email }}">
                                @endforeach
                                @error('attendees')
                                    <em class="text-danger">{{ $message }}</em>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
