<?php

namespace App\Http\Requests;

use App\Rules\ConcurrentMeetingsRule;
use App\Rules\StartBeforeEndRule;
use Carbon\Carbon;

class MeetingRequest extends ApiFormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'start' => Carbon::parse($this->start)->format('Y-m-d H:i'),
            'end' => Carbon::parse($this->end)->format('Y-m-d H:i'),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => [
                'string',
                'max:255',
            ],
            'start' => [
                'date',
                'after:now',
            ],
            'end' => [
                'date',
                'after:now',
            ],
            'attendees' => [
                'array',
                'min:2',
            ],
            'attendees.*.id' => [
                'integer',
                'distinct',
                'exists:App\User,id',
                new ConcurrentMeetingsRule($this->meeting ? $this->meeting->id : 0, $this->start, $this->end),
            ],
            'facilitator_id' => [
                'integer',
                'exists:App\User,id',
                'in_array:attendees.*.id',
            ],
            'secretary_id' => [
                'integer',
                'exists:App\User,id',
                'different:facilitator_id',
                'in_array:attendees.*.id',
            ],
        ];

        if ($this->getMethod() == 'POST') {
            $prependPostRules = [
                'name' => [
                    'required',
                ],
                'start' => [
                    'required',
                ],
                'end' => [
                    'required',
                ],
                'attendees' => [
                    'required',
                ],
                'attendees.*.id' => [
                    'required',
                ],
                'facilitator_id' => [
                    'required',
                ],
                'secretary_id' => [
                    'required',
                ],
            ];
            $appendPostRules = [
                'start' => [
                    'before:end',
                ],
                'end' => [
                    'after:start',
                ],
            ];

            $rules = array_merge_recursive($prependPostRules, $rules);
            $rules = array_merge_recursive($rules, $appendPostRules);
        }

        if ($this->getMethod() == 'PUT') {
            $prependPutRules = [
                'name' => [
                    'nullable',
                ],
                'start' => [
                    'nullable',
                ],
                'end' => [
                    'nullable',
                ],
                'attendees' => [
                    'nullable',
                ],
                'attendees.*.id' => [
                    'nullable',
                ],
            ];
            $appendPutRules = [
                'start' => [
                    new StartBeforeEndRule($this->meeting->id, $this->start, $this->end),
                ],
                'end' => [
                    new StartBeforeEndRule($this->meeting->id, $this->start, $this->end),
                ],
            ];

            $rules = array_merge_recursive($prependPutRules, $rules);
            $rules = array_merge_recursive($rules, $appendPutRules);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
