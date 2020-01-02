<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestHelperTrait;

class GetLatestEventRequest extends FormRequest
{
    use RequestHelperTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('view_events', $this->partner_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_type' => 'sometimes',
            'page' => 'required|numeric',
            'per_page' => 'required|numeric',
            'partner_id' => 'required|exists:users,id'            
        ];
    }

    public function messages(){
        return [
            'partner_id.exists' => 'Invalid partner supplied!',
            'page.numeric' => 'Enter a valid page value',
            'per_page.numeric' => 'Enter a valid per page value'
        ];
    }
}
