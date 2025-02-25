<?php

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetBookingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'int', Rule::exists('assets', 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

}