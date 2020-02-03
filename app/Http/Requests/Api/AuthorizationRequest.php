<?php

namespace App\Http\Requests\Api;

class AuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        // If login by code, openid is got from $driver->getAccessTokenResponse($code);
        // else if by access_token, need to provide open_id
        if ($this->social_type == 'weixin' && !$this->code) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
