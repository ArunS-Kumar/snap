<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestHelperTrait;

class GetPartnersListRequest extends FormRequest
{
    use RequestHelperTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('view_partners_list', $this->companyId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyId' => 'required|exists:partner_companies,id'
        ];
    }


    public function messages(){
        return ['companyId.exists'=>'Invalid company supplied!'];
    }
}
