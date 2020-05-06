<?php

namespace App\Rules;

use App\Meeting;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class StartBeforeEndRule implements Rule
{
    /**
     * Meeting id.
     *
     * @var integer
     */
    protected $meetingId;

    /**
     * Start attribute value.
     *
     * @var Carbon
     */
    protected $start;

    /**
     * End attribute value.
     *
     * @var Carbon
     */
    protected $end;

    /**
     * Checking attribute.
     *
     * @var string
     */
    protected $attribute;

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
        if ($value) {
            $this->attribute = $attribute;
            $time = Carbon::parse($value)->format('Y-m-d H:i');

            if ($attribute === 'start') {
                if (!$this->end) {
                    return Meeting::whereId($this->meetingId)
                                  ->where('end', '>=', $time)
                                  ->exists();
                }
                return $time <= $this->end;
            }
            if ($attribute === 'end') {
                if (!$this->start) {
                    return Meeting::whereId($this->meetingId)
                                  ->where('start', '<=', $time)
                                  ->exists();
                }
                return $time >= $this->start;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->attribute === 'start') {
            return ':Attribute should be before end.';
        }
        if ($this->attribute === 'end') {
            return ':Attribute should be after start.';
        }
    }
}
