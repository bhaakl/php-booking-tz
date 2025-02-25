<?php

namespace App\Modules\Booking\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'int', Rule::exists('assets', 'id')],
            'user_id' => ['required', 'int'],
            'start_time' => ['required', 'date', 'after_or_equal:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ];
    }
}