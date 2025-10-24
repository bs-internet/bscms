<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => [
                'required',
                'string',
                'max:255',
                Rule::unique('menus', 'location')->ignore($this->route('menu')),
            ],
            'description' => ['nullable', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*.title' => ['nullable', 'string', 'max:255'],
            'items.*.url' => ['nullable', 'string', 'max:255'],
            'items.*.target' => ['nullable', 'string', 'max:20'],
        ];
    }
}
