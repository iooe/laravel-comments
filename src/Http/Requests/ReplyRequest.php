<?php

namespace tizis\laraComments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string'
        ];
    }
}