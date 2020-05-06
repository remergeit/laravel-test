<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Http\Requests\MeetingRequest;

class MeetingController extends Controller
{
    /**
     * Get Meetings list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Meeting::with('attendees')
                       ->latest()
                       ->paginate(10);

        return response()->json($list, 200);
    }

    /**
     * Get the Meeting.
     *
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        $meeting->load('attendees');

        return response()->json($meeting, 200);
    }

    /**
     * Create a Meeting.
     *
     * @param  App\Http\Requests\MeetingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeetingRequest $request)
    {
        $meeting = Meeting::create($request->validated());

        return response()->json($meeting, 200);
    }

    /**
     * Update the Meeting.
     *
     * @param  Meeting  $meeting
     * @param  App\Http\Requests\MeetingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(MeetingRequest $request, Meeting $meeting)
    {
        $meeting->update($request->validated());

        return response()->json($meeting, 200);
    }

    /**
     * Remove the Meeting.
     *
     * @param  Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();

        return response()->json($meeting, 200);
    }
}
