<?php

namespace App\Http\Controllers\Api\V1\booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\Asset\Models\Asset;
use App\Modules\Base\Resources\SuccessResource;
use App\Modules\Asset\Requests\AssetStoreRequest;
use App\Modules\Asset\Resources\AssetCollection;


class AssetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/resources",
     *     summary="Получить список всех ресурсов",
     *     tags={"Booking resource"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/AssetsResponse"))
     *     )
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *     ),
     * )
     */
    public function index()
    {
        $assets = Asset::all();
        return new AssetCollection($assets);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/resources/",
     *     summary="Создать ресурс",
     *     tags={"Booking resource"},
     *     description="",
     *     operationId="createAsset",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AssetStoreRequest")
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error",
     *     ),
     * )
     */
    public function store(AssetStoreRequest $request): SuccessResource
    {
        $asset = Asset::create($$request->validated());
        return new SuccessResource($asset, 201);
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
