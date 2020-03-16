<?php

namespace tizis\laraComments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			'commentable_encrypted_key' => 'required|string',
            'order_by' => 'string|min:1|max:11'
        ];
    }
}
