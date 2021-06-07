<?php

namespace App\Modules\SuperAdmin\Transformers;

use App\User;

class StaffTransformer
{
  public function collectionTransformer($collection, $transformerMethod)
  {
    return $collection->map(function ($v) use ($transformerMethod) {
      return $this->$transformerMethod($v);
    });
  }

  public function transformBasic(User $user)
  {
    return [
      'id' => (int)$user->id,
      'full_name' => (string)$user->full_name,
    ];
  }
  public function transformForSuperAdminViewSalesReps(User $staff)
  {
    return [
      'id' => (int)$staff->id,
      'full_name' => (string)$staff->full_name,
      'user_name' => (string)$staff->user_name,
      'email' => (string)$staff->email,
      'phone' => (string)$staff->phone,
      'address' => (string)$staff->address,
      'id_url' => (string)$staff->id_url,
      'is_suspended' => (bool) !$staff->is_active,
      'is_deleted' => (bool) $staff->deleted_at,
    ];
  }
}
