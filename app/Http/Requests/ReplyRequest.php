<?php

namespace App\Http\Requests;

/**
 * Class ReplyRequest
 * @property $content
 * @package App\Http\Requests
 */
class ReplyRequest extends Request
{
    public function rules()
    {
        return [
            'content' => 'required|min:2'
        ];
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
