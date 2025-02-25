<?php

namespace App\Modules\Base\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    private int $status;

    public function __construct($resource, int $status = 200)
    {
        parent::__construct($resource);
        $this->status = $status;
    }

    public function toArray($request): array
    {
        return [
            'status' => true,
            'errors' => null,
        ];
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->status);
    }
}