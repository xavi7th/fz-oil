<?php

namespace App\Modules\FzCustomer\Transformers;

use App\Modules\FzCustomer\Models\FzCustomer;

class FzCustomerTransformer
{
  public function collectionTransformer($collection, $transformerMethod)
  {
    return $collection->map(fn ($v) => $this->$transformerMethod($v));
  }

  public function transformBasic(FzCustomer $user)
  {
    return [
      'id' => (int)$user->id,
      'full_name' => (string)$user->full_name,
    ];
  }

  public function transformForDetails(FzCustomer $user)
  {
    return [
      'id' => (int)$user->id,
      'full_name' => (string)$user->full_name,
      'email' => (string)$user->email,
      'address' => (string)$user->address,
      'phone' => (string)$user->phone,
      'is_active' => (bool)$user->is_active,
      'is_flagged' => (bool)$user->is_flagged,
      'credit_limit' => (float)$user->credit_limit,
      'credit_balance' => (float)$user->credit_balance,
      'credit_debt' => (float)$user->credit_limit - $user->credit_balance,
      'total_purchase_count' => (int)$user->purchase_orders()->count(),
      'total_purchase_amount' => (int)$user->purchase_orders()->sum('total_selling_price'),
      'credit_purchase_count' => (int)$user->purchase_orders()->credit()->count(),
      'total_credit_purchase_amount' => (int)$user->purchase_orders()->credit()->sum('total_selling_price'),
      'repayment_count' => (int)$user->credit_transactions()->repayment()->count(),
      'repayment_amount' => (int)$user->credit_transactions()->repayment()->sum('amount'),
    ];
  }
}
