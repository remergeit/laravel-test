<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class ConcurrentMeetingsRule implements Rule
{
    /**
     * Meeting id.
     *
     * @var integer
     */
    protected $meetingId;

    /**
     * Meeting start time.
     *
     * @var Carbon
     */
    protected $start;

    /**
     * Meeting end time.
     *
     * @var Carbon
     */
    protected $end;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($meetingId, $start, $end)
    {
        $this->meetingId = $meetingId;
        $this->start = Carbon::parse($start)->format('Y-m-d H:i');
        $this->end = Carbon::parse($end)->format('Y-m-d H:i');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $noConcurrentMeetings = true;

        if (User::whereId($value)->exists()) {
            $noConcurrentMeetings = User::whereHas('meetings', function ($query) {
                                            $query->where('start', '<=', $this->end)
                                                  ->where('end', '>=', $this->start)
                                                  ->where('meetings.id', '!=', $this->meetingId);
                                        })
                                        ->whereId($value)
                                        ->count()
                                        === 0;
        }

        return $noConcurrentMeetings;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User can\'t be on two meeting at the same time';
    }
}
