<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
        return [
            'started_at' => 'required|date_format:Y-m-d H:i:s|after:1 hours',
            'address' => 'present',
            'address.postcode' => 'required|string',
            'client' =>'present',
            'client.firstname' => 'required|string',
            'client.lastname' => 'required|string',
            'client.email' => 'required|email',
            'client.phone_number' => 'required|digits:10',
        ];
    }
}
