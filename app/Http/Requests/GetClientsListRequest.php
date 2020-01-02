<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestHelperTrait;

class GetClientsListRequest extends FormRequest
{
    use RequestHelperTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('view_clients_list', $this->partnerId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'partnerId' => 'required|exists:users,id'
        ];
    }

    public function messages(){
        return ['partnerId.exists'=>'Invalid partner supplied!'];
    }
}
