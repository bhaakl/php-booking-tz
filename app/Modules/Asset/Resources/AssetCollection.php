<?php

namespace App\Modules\Asset\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AssetCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => AssetResource::collection($this->collection),
        ];
    }
}