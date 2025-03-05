<?php

namespace App\Observers;

use App\Modules\Booking\Models\Booking;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Support\Facades\Notification;

class BookingObserver
{
  /**
   * Вызывается после создания бронирования.
   */
    public function created(Booking $booking): void
    {
        if ($booking->user) {
            Notification::send($booking->user, new BookingCreatedNotification($booking));
        }
    }

  /**
   * Вызывается после удаления бронирования.
   */
    public function deleted(Booking $booking): void
    {
        if ($booking->user) {
            Notification::send($booking->user, new BookingCancelledNotification($booking));
        }
    }
}
