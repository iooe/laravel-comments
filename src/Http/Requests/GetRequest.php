<?php

namespace tizis\laraComments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer|min:1'
        ];
    }
}
