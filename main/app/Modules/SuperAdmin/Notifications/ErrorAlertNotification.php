<?php

namespace App\Modules\SuperAdmin\Notifications;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramChannel;
use Illuminate\Notifications\Messages\SlackMessage;

class ErrorAlertNotification extends Notification implements ShouldQueue
{
  use Queueable;

  protected $errorPinpointLocation;
  protected $errorCode;
  protected $errorMessage;
  protected $errorTrace;
  protected $errorTraceAsString;
  protected $optionalMessage;
  protected $accessedUrl;
  protected $inputFields;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(Throwable $throwable, string $optionalMessage = null)
  {
    $this->errorCode =  $throwable->getCode();
    $this->errorPinpointLocation =  $throwable->getFile() . ':' . $throwable->getLine();
    $this->errorMessage =  $throwable->getMessage();
    $this->errorTrace =  $throwable->__toString();
    $this->errorTraceAsString =  $throwable->getTraceAsString();
    $this->optionalMessage =  $optionalMessage;
    $this->accessedUrl = Request::fullUrl();
    $this->inputFields = json_encode(Request::except(['password', 'password_confirmation']));
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array
   */
  public function via()
  {
    return ['slack', TelegramChannel::class];
  }

  public function toTelegram()
  {
    Storage::disk('local')->put('error-trace.txt', $this->optionalMessage . "\n\n Error Code \n"  . $this->errorCode . PHP_EOL . "Stack Trace \n"  . PHP_EOL . $this->errorTraceAsString . PHP_EOL);

    return TelegramFile::create()
      ->token(config('services.telegram.bot_id'))
      ->to(config('services.telegram.error_alert_group'))
      ->content($this->errorMessage)
      ->document(storage_path('app/error-trace.txt'), 'trace-logs.txt')
      ->button('Open App', route('auth.login'));
    // ->options([
    //   'parse_mode' => ''
    // ]);
  }

    /**
   * Get the Slack representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return SlackMessage
   */
  public function toSlack($notifiable)
  {
    $url = route('auth.login');

    return (new SlackMessage)
      ->error()
      ->content($this->optionalMessage ?? 'Whoops! Something went wrong.')
      ->attachment(function ($attachment) use ($url) {
        $attachment->title($this->errorMessage, $url)
          ->content($this->errorTrace)
          ->fields([
            'url' => $this->accessedUrl,
            'input' => $this->inputFields,
            'error_code' => $this->errorCode,
            'error_location' => $this->errorPinpointLocation,
          ]);
        // ->markdown(['text']);
      });
  }
}
