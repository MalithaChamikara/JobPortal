<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobPostFormrequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'feature_image' => 'required|mimes:png.jpeg,jpg',
            'roles' => 'required|min:10',
            'address' => 'required',
            'salary' => 'required',
            'date' => 'required',
            'job_type' => 'required',
        ];
    }
}
