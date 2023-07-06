<?php

namespace App\Http\Requests\Api;

class TodoRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'due_date' => ['required'],
            'priority' => ['required'],
        ];
    }
}
