<?php

namespace App\Modules\SuperAdmin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramFile;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Spatie\Backup\Notifications\Notifications\BackupWasSuccessful as SpatieBackupWasSuccessful;


class BackupWasSuccessful extends SpatieBackupWasSuccessful  //implements ShouldQueue
{
  use Queueable;

  public function toTelegram()
  {
    Storage::disk('local')->put('backup-trace.txt', json_encode($this->backupDestinationProperties()->toArray()));

    return TelegramFile::create()
      ->token(config('services.telegram.bot_id'))
      ->to(config('services.telegram.admin_notifications_group'))
      ->content(trans('backup::notifications.backup_successful_subject_title'))
      ->document(storage_path('app/backup-trace.txt'), 'backup-location.txt');
  }

  public function toSlack(): SlackMessage
  {
    return (new SlackMessage)
        ->success()
        ->from(config('backup.notifications.slack.username'), config('backup.notifications.slack.icon'))
        ->to(config('backup.notifications.slack.channel'))
        ->content(trans('backup::notifications.backup_successful_subject_title'))
        ->attachment(function (SlackAttachment $attachment) {
            $attachment->fields($this->backupDestinationProperties()->toArray());
        });
  }
}
