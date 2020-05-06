<?php

namespace App\Http\Requests;

class UserRequest extends ApiFormRequest
{
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
            'surname' => [
                'string',
                'max:255',
            ],
            'email' => [
                'email',
                'unique:App\User',
                'max:255',
            ],
        ];

        if ($this->getMethod() == 'POST') {
            $prependPostRules = [
                'name' => [
                    'required',
                ],
                'surname' => [
                    'required',
                ],
                'email' => [
                    'required',
                ],
            ];

            $rules = array_merge_recursive($prependPostRules, $rules);
        }

        if ($this->getMethod() == 'PUT') {
            $prependPutRules = [
                'name' => [
                    'nullable',
                ],
                'surname' => [
                    'nullable',
                ],
                'email' => [
                    'nullable',
                ],
            ];

            $rules = array_merge_recursive($prependPutRules, $rules);
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
