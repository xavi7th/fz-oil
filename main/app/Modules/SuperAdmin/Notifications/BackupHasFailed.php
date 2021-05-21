<?php

namespace App\Modules\SuperAdmin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Spatie\Backup\Notifications\Notifications\BackupHasFailed as SpatieBackupFailedNotification;


class BackupHasFailed extends SpatieBackupFailedNotification //implements ShouldQueue
{
  use Queueable;

  public function toTelegram()
  {

    Storage::disk('local')->put('backup-trace.txt', trans('backup::notifications.exception_message_title') . PHP_EOL . $this->event->exception->getMessage() . PHP_EOL);
    Storage::disk('local')->append('backup-trace.txt', trans('backup::notifications.exception_trace_title') . PHP_EOL . $this->event->exception->getTraceAsString() . PHP_EOL);
    Storage::disk('local')->append('backup-trace.txt', json_encode($this->backupDestinationProperties()->toArray()));

    return TelegramFile::create()
      ->token(config('services.telegram.bot_id'))
      ->to(config('services.telegram.admin_notifications_group'))
      ->content(trans('backup::notifications.backup_failed_subject', ['application_name' => $this->applicationName()]))
      ->document(storage_path('app/backup-trace.txt'), 'backup-trace.txt')
      ->button('Open App', route('auth.login'));
    // ->options([
    //   'parse_mode' => ''
    // ]);
  }


  public function toSlack(): SlackMessage
  {
    return (new SlackMessage)
    ->error()
    ->from(config('backup.notifications.slack.username'), config('backup.notifications.slack.icon'))
    ->to(config('backup.notifications.slack.channel'))
    ->content(trans('backup::notifications.backup_failed_subject', ['application_name' => $this->applicationName()]))
    ->attachment(function (SlackAttachment $attachment) {
        $attachment
            ->title(trans('backup::notifications.exception_message_title'))
            ->content($this->event->exception->getMessage());
    })
    ->attachment(function (SlackAttachment $attachment) {
        $attachment
            ->title(trans('backup::notifications.exception_trace_title'))
            ->content($this->event->exception->getTraceAsString());
    })
    ->attachment(function (SlackAttachment $attachment) {
        $attachment->fields($this->backupDestinationProperties()->toArray());
    });
  }

}
