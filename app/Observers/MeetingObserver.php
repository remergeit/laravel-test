<?php

namespace App\Observers;

use App\Meeting;
use App\Jobs\MeetingUpdateStatusJob;
use App\Notifications\NewMeetingNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class MeetingObserver
{
    /**
     * Validated data of request.
     *
     * @var array
     */
    protected $request;

    /**
     * Create a new observer instance.
     *
     * @param  App\Http\Requests\MeetingUpdateRequest  $request
     * @return void
     */
    public function __construct()
    {
        // $this->request = $request->validated();
    }

    /**
     * Handle the meeting "created" event.
     *
     * @param  \App\Meeting  $meeting
     * @return void
     */
    public function created(Meeting $meeting)
    {
        $attendeesIds = data_get(request()->only('attendees'), 'attendees.*.id');
        $attendees = User::find($attendeesIds)->all();

        $meeting->attendees()->attach($attendeesIds);
        MeetingUpdateStatusJob::dispatch($meeting, 'started')
                              ->delay(Carbon::parse($meeting->start)->diffInSeconds(now()));
        MeetingUpdateStatusJob::dispatch($meeting, 'finished')
                              ->delay(Carbon::parse($meeting->end)->diffInSeconds(now()));
        Notification::send($attendees, new NewMeetingNotification($meeting));
    }

    /**
     * Handle the meeting "updated" event.
     *
     * @param  \App\Meeting  $meeting
     * @return void
     */
    public function updated(Meeting $meeting)
    {
        $meeting->attendees()
                ->sync(data_get(request()->only('attendees'), 'attendees.*.id'));
    }

    /**
     * Handle the meeting "deleted" event.
     *
     * @param  \App\Meeting  $meeting
     * @return void
     */
    public function deleted(Meeting $meeting)
    {
        //
    }

    /**
     * Handle the meeting "restored" event.
     *
     * @param  \App\Meeting  $meeting
     * @return void
     */
    public function restored(Meeting $meeting)
    {
        //
    }

    /**
     * Handle the meeting "force deleted" event.
     *
     * @param  \App\Meeting  $meeting
     * @return void
     */
    public function forceDeleted(Meeting $meeting)
    {
        //
    }
}
