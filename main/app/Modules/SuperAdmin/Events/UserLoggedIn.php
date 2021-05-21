<?php

namespace App\Modules\SuperAdmin\Events;

use App\User;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
  use SerializesModels;

  public $user;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(User $user)
  {
    $this->user = $user;
  }
}
