<?php

namespace App\Modules\Asset\Services;

use App\Modules\Asset\Repositories\AssetRepository;
use App\Modules\Asset\Models\Asset;

class AssetService
{
    public function __construct(private readonly AssetRepository $repo)
    {
    }

    public function createAsset(
        string $name,
        string $type,
        string $description,
    ) {
        return Asset::create([
        'name' => $name,
        'type' => $type,
        'description' => $description
        ]);
    }

    public function deleteAsset($id)
    {
        $asset = $this->repo->getOne($id);
        $asset->delete();
    }
}
