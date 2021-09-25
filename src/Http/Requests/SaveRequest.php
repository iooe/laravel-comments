<?php

namespace tizis\laraComments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'commentable_encrypted_key' => ['required', 'string'],
            'message' => ['required', 'string', 'max:10000']
        ];
    }
}
