<?php

namespace App\Http\Controllers\Api\V1\booking;

use App\Modules\Base\Resources\SuccessResource;
use App\Http\Controllers\Controller;
use App\Modules\Booking\Models\Booking;
use App\Modules\Booking\Requests\AssetBookingsRequest;
use App\Modules\Booking\Requests\BookingStoreRequest;
use App\Modules\Booking\Requests\DeleteBookingRequest;
use App\Modules\Booking\Resources\BookingCollection;
use App\Modules\Asset\Models\Asset;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return new BookingCollection($bookings);
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
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error",
     *     ),
     * )
     */
    public function store(BookingStoreRequest $request)
    {
        $booking = Booking::create($request->validated());
        return new SuccessResource($booking, 201);
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
        $id = $request->validated('id');
        $asset = Asset::findOrFail($id);
        $bookings = $asset->bookings;

        return new BookingCollection($bookings);
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
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse"),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error",
     *     ),
     * )
     */
    public function destroy(DeleteBookingRequest $request): JsonResponse
    {
        $id = $request->validated('id');
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return SuccessResource($booking, 201);
    }


    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
