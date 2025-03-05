<?php

namespace App\Modules\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Mockery\Undefined;

class ErrorResource extends JsonResource
{
    private int $code;
    private string $message;

    public function __construct(int $code = null, ?string $message = null, ?array $errors = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;
    }


    public function toArray($request)
    {
        return [
            'code' => $this->code ?? $this->getCode(),
            'message' => $this->message ?? $this->getMessage(),
            'errors' => $this->errors
        ];
    }

    public function toResponse($request)
    {
        return (new ResourceResponse($this))->toResponse($request)->setStatusCode($this->code ?? 422);
    }

    public function getStatusCode()
    {
        return $this->code;
    }
}
