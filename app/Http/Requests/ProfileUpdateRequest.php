<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $regions = json_decode(
            file_get_contents(resource_path('js/data/indonesia-regions.json')),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $provinces = $regions['provinces'];
        $province = collect($provinces)->firstWhere('name', $this->input('province'));
        $cityNames = $province
            ? array_column($regions['regencies'][$province['code']] ?? [], 'name')
            : [];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'province' => ['nullable', 'string', Rule::in(array_column($provinces, 'name'))],
            'city' => ['nullable', 'required_with:province', 'string', Rule::in($cityNames)],
        ];
    }
}
