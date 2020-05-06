<?php

namespace App\Jobs;

use App\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MeetingUpdateStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Meeting for updating.
     *
     * @var Meeting
     */
    protected $meeting;

    /**
     * Status for update meeting.
     *
     * @var string
     */
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Meeting $meeting, $status)
    {
        $this->meeting = $meeting;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->meeting) {
            $this->meeting->withoutEvents(function ($meeting) {
                $meeting->update(['status' => $this->status]);
            });
        }
    }
}
