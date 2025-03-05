<?php

namespace App\Http\Controllers\Api\V1\booking;

use App\Modules\Base\Resources\ErrorResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Modules\Asset\Requests\AssetStoreRequest;
use App\Modules\Asset\Resources\AssetResource;
use App\Modules\Asset\Services\AssetService;
use App\Modules\Asset\Repositories\AssetRepository;

class AssetController extends Controller
{
    public function __construct(
        private readonly AssetRepository $bookingRepository,
        private readonly AssetService $bookingService,
    ) {
    }

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
        return AssetResource::collection($this->bookingRepository->getAll());
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
     *         @OA\JsonContent(ref="#/components/schemas/Asset"))
     *     ),
     *     @OA\Response(
     *         response="400, 422",
     *         description="Error",
     *     ),
     * )
     */
    public function store(AssetStoreRequest $request)
    {
        try {
            return AssetResource::make($this->bookingService->createAsset(
                $request->name,
                $request->type,
                $request->description,
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $msg = 'Ошибка при создании ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(code: $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY, message: $msg);
        } catch (\Exception $e) {
            \Log::error('Ошибка при создании ресурса: ' . $e->getMessage());
            $msg = 'Ошибка при создании ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(code: $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR, message: $msg);
        }
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($request)
    {
        try {
            $this->bookingService->deleteAsset($request->id);
            return response()->noContent();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Ошибка при удалении ресурса: ' . $e->getMessage());
            return ErrorResource::make(code: Response::HTTP_NOT_FOUND, message: 'Ресурс по указанному ID не найден: ' . $e->getMessage());
        }
    }
}
