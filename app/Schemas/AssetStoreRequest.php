<?php

namespace App\Schemas;


/**
 * @OA\Schema(
 *     description="Description",
 *     type="object",
 *     title="AssetStoreRequest",
 * )
 */
class AssetStoreRequest
{
    /**
     * @OA\Property(property="name", type="string", example="Asset title", description="title")
     *
     * @var string $name
     */
    public string $name;

    /**
     * @OA\Property(property="type", type="string", example="Asset text", description="type")
     *
     * @var string $type
     */
    public string $type;

    /**
     * @OA\Property(property="description", type="string", example="Asset text", description="desc")
     *
     * @var string $description
     */
    public string $description;
}