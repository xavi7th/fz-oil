<?php

namespace App\Exceptions;

use Throwable;
use RuntimeException;
use Illuminate\Support\Facades\Notification;
use App\Modules\SuperAdmin\Notifications\ErrorAlertNotification;

class AdminSlackException extends RuntimeException
{
  /**
   * Report or log an exception.
   *
   * @return void
   */
  public function report(Throwable $e, string $optionalMessage)
  {
    Notification::route('telegram', config('services.telegram.error_alert_group'))->notify(new ErrorAlertNotification($e, $optionalMessage));
  }
}
