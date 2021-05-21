<?php

namespace App\Modules\AppUser\Transformers;

use App\Modules\AppUser\Models\AppUser;
use App\Modules\AppUser\Models\Transaction;

class AppUserTransformer
{
  public function collectionTransformer($collection, $transformerMethod)
  {
    try {
      return [
        'total' => $collection->count(),
        'current_page' => $collection->currentPage(),
        'path' => $collection->resolveCurrentPath(),
        'to' => $collection->lastItem(),
        'from' => $collection->firstItem(),
        'last_page' => $collection->lastPage(),
        'next_page_url' => $collection->nextPageUrl(),
        'per_page' => $collection->perPage(),
        'prev_page_url' => $collection->previousPageUrl(),
        'total' => $collection->total(),
        'first_page_url' => $collection->url($collection->firstItem()),
        'last_page_url' => $collection->url($collection->lastPage()),
        'data' => $collection->map(function ($v) use ($transformerMethod) {
          return $this->$transformerMethod($v);
        })
      ];
    } catch (\Throwable $e) {
      return [
        'data' => $collection->map(function ($v) use ($transformerMethod) {
          return $this->$transformerMethod($v);
        })
      ];
    }
  }

  public function transform(AppUser $user)
  {
    return [
      'first_name' => (string)$user->first_name,
      'last_name' => (string)$user->last_name,
      'email' => (string)$user->email,
      'phone' => (string)$user->phone,
      'address' => (string)$user->address,
      'city' => (string)$user->city,
      'num_of_days_active' => (int)$user->activeDays(),
      'is_otp_verified' => (boolean)$user->is_otp_verified()

    ];
  }

  public function transformBasic(AppUser $user)
  {
    return [
      'email' => (string)$user->email,
      'first_name' => (string)$user->first_name,
      'city' => (string)$user->city,
      'ig_handle' => (string)$user->ig_handle,
      'phone' => (string)$user->phone,
    ];
  }

  public function transformForAppUser(AppUser $user)
  {
    $curr = (function () use ($user) {
      switch ($user->currency) {
        case 'USD':
          return '$';
          break;
        case 'GBP':
          return '£';
          break;
        case 'EUR':
          return '€';
          break;

        default:
          return $user->currency;
          break;
      }
    })();
    return [
      'id' => (int)$user->id,
      'name' => (string)$user->name,
      'email' => (string)$user->email,
      'country' => (string)$user->country,
      'currency' => (string)$curr,
      'phone' => (string)$user->phone,
      'id_card' => (string)$user->id_card,
      // 'is_verified' => (bool)$user->is_verified(),
      'total_deposit' => (double)$user->total_deposit_amount(),
      'total_withdrawal' => (double)$user->total_withdrawal_amount(),
      'total_profit' => (double)$user->total_profit_amount(),
      'target_profit' => (double)$user->expected_withdrawal_amount(),
      'total_withdrawable' => (double)number_format($user->total_withdrawalable_amount(), 2, '.', '')
    ];
  }
}
