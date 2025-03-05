<?php

namespace App\Http\Controllers\Api\V1\booking;

use App\Http\Controllers\Controller;
use App\Modules\Base\Resources\ErrorResource;
use App\Modules\Booking\Requests\AssetBookingsRequest;
use App\Modules\Booking\Requests\BookingStoreRequest;
use App\Modules\Booking\Requests\DeleteBookingRequest;
use App\Modules\Booking\Resources\BookingResource;
use App\Modules\Booking\Services\BookingService;
use App\Modules\Booking\Repositories\BookingRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingRepository $bookingRepository,
        private readonly BookingService $bookingService,
    ) {
    }

    public function index()
    {
        return BookingResource::collection($this->bookingRepository->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/bookings/",
     *     summary="Создать бронирование",
     *     tags={"Booking"},
     *     description="",
     *     operationId="createBooking",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookingStoreRequest")
     *      ),
     *     @OA\Response(
     *         response="201",
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(
     *         response="400, 422",
     *         description="Error",
     *     ),
     * )
     */
    public function store(BookingStoreRequest $request)
    {
        try {
            return BookingResource::make($this->bookingService->createBooking(
                $request->asset_id,
                $request->user_id,
                $request->start_time,
                $request->end_time
            ));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $msg = 'Ошибка при создании ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(
                code: $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_UNPROCESSABLE_ENTITY,
                message: $msg,
                errors: $e->errors()
            );
        } catch (\Exception $e) {
            $msg = 'Ошибка при создании ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(code: $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : Response::HTTP_BAD_REQUEST, message: $msg);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/resources/{id}/bookings",
     *     summary="Получить всех бронирований для ресурса",
     *     tags={"Booking", "Booking resource"},
     *     operationId="assetBookingsShow",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          example="123",
     *          description="ID of the resource",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/BookingsResponse"),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error",
     *     ),
     * )
     */
    public function assetBookings(AssetBookingsRequest $request)
    {
        try {
            return BookingResource::collection($this->bookingRepository->getBookingsByAssetId($request->id));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $msg = 'Ошибка при получении бронирований для ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(code: Response::HTTP_NOT_FOUND, message: $msg);
        } catch (\App\Exceptions\ResourceEmptyException $e) {
            $msg = 'Ошибка при получении бронирований для ресурса: ' . $e->getMessage();
            \Log::error($msg);
            return ErrorResource::make(code: Response::HTTP_BAD_REQUEST, message: $e->getMessage());
        }
    }

     /**
     * @OA\Delete(
     *     path="/api/v1/bookings/{id}",
     *     summary="Booking cancel",
     *     tags={"Booking"},
     *     operationId="deleteBooking",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          example="123",
     *          description="ID of the booking",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error",
     *     ),
     * )
     */
    public function destroy(DeleteBookingRequest $request)
    {
        try {
            $this->bookingService->deleteBooking($request->id);
            return response()->noContent();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Ошибка при удалении ресурса: ' . $e->getMessage());
            return ErrorResource::make(code: Response::HTTP_NOT_FOUND, message: 'Ресурс по указанному ID не найден: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Ошибка при удалении ресурса: ' . $e->getMessage());
            return ErrorResource::make(code: $e->getCode() >= 400 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR, message: $e->getMessage());
        }
    }


    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }
}