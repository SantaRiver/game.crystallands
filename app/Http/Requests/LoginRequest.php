<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'account.account_name' => 'required|string',
            'proof.chainId' => 'required|string',
            'proof.signer.actor' => 'required|string',
            'proof.signer.permission' => 'required|string',
            'proof.signature' => 'required|string',
            'proofKey' => 'required|string',
            'proofValid' => 'boolean|required',
            'authType' => 'string|required',
        ];
    }
}
