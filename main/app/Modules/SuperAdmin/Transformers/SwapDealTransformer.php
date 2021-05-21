<?php

namespace App\Modules\SuperAdmin\Transformers;

use App\Modules\SuperAdmin\Models\SwapDeal;

class SwapDealTransformer
{
  public function collectionTransformer($collection, $transformerMethod)
  {
    return
      $collection->map(function ($v) use ($transformerMethod) {
        return $this->$transformerMethod($v);
      });
  }

  public function basic(SwapDeal $swapDeal): array
  {
    return [
      'description' => (string)$swapDeal->description,
      'identifier' => (string)$swapDeal->primary_identifier(),
      'selling_price' => (float)$swapDeal->selling_price,
      'uuid' => $swapDeal->product_uuid,
      'status' => $swapDeal->product_status->status,
    ];
  }

}
