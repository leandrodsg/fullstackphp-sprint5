<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:services,name,NULL,id,user_id,' . auth()->id()
            ],
            'category' => 'required|in:Streaming,Software,Cloud Storage,Music,Gaming,Other',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url'
        ];
    }
}
