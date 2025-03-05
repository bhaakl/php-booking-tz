<?php

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'int'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
