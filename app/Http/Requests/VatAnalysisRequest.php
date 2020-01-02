<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestHelperTrait;

class VatAnalysisRequest extends FormRequest
{
    use RequestHelperTrait;
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
            'clientCrmId' => 'required',
            'more_filters'=> 'required',
        ];
    }
}

