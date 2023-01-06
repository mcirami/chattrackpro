<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
	        'first_name' => 'required|max:255|string',
	        'last_name' => 'required|max:255|string',
	        'office_name' => 'required|max:255|string',
	        'email' => 'required|max:255|email',
	        'messenger' => 'required|max:255|string',
	        'messenger_name' => 'required|max:255|string',
	        'location' => 'required|max:255|string',
	        'account_type' => 'required|max:255|string',
	        'agents' => 'required|max:255|string',
	        'offer_types' => 'required|min:1',
	        'experience' => 'required|max:255|string',
	        'sales' => 'required|max:255|string',
	        'additional_info' => 'max:255|string',
        ];
    }
}
