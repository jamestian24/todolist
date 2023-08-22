<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'task_descr' => ['required', 'string', 'max:255'],
            'member_id' => ['required', 'int', 'max:255', Rule::exists("members", "id")],
            'creator_id' => ['required', 'int', 'max:255', Rule::exists("users", "id")],
        ];
    }
}
