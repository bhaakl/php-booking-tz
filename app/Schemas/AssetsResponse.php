<?php

namespace App\Schemas;

/**
 * @OA\Schema(
 *     description="Asset Response",
 *     type="object",
 *     title="AssetResponse",
 * )
 */
class AssetsResponse
{
    /**
     * @OA\Property(
     *     property="data",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Asset"))
     *
     *
     * @var array $data
     */
    public array $data;
}
