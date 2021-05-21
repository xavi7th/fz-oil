<?php

namespace App\Exceptions;

use Throwable;
use RuntimeException;

class MultipleRecordsFoundException extends RuntimeException
{
  /**
   * Report or log an exception.
   *
   * @return void
   */
  public function report(Throwable $e)
  {

    //Send to slack
    \Log::notice($e);
  }
}
