<?php

namespace App\Http\Requests;

class StoreJobRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Add authorization logic if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_language_id' => 'required_if:user_type,' . config('customer.CUSTOMER_ROLE_ID'),
            'immediate' => 'required|in:yes,no',
            'due_date' => 'required_if:immediate,no',
            'due_time' => 'required_if:immediate,no',
            'customer_phone_type' => 'required_if:immediate,no',
            'customer_physical_type' => 'required_if:immediate,no',
            'duration' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'from_language_id.required_if' => 'Du måste fylla in alla fält',
            'immediate.required' => 'Du måste fylla in alla fält',
            'immediate.in' => 'The immediate field must be either yes or no',
            'due_date.required_if' => 'Du måste fylla in alla fält',
            'due_time.required_if' => 'Du måste fylla in alla fält',
            'customer_phone_type.required_if' => 'Du måste göra ett val här',
            'customer_physical_type.required_if' => 'Du måste göra ett val här',
            'duration.required' => 'Du måste fylla in alla fält',
        ];
    }
}
