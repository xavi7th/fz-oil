<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use Ankurk91\Eloquent\MorphToOne;
use App\Modules\AppUser\Models\ProductReceipt;
use App\Exceptions\MultipleRecordsFoundException;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * App\Modules\SuperAdmin\Models\ShopItem
 *
 * @property-read ProductReceipt|null $sale_receipt
 * @method static \Illuminate\Database\Eloquent\Builder|ShopItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopItem query()
 * @mixin \Eloquent
 */
class ShopItem extends BaseModel
{
  use MorphToOne;

  public function sale_record()
  {
    return $this->morphToOne(ProductSaleRecord::class, 'purchased_item', 'purchased_items')->withTimestamps()->withPivot('selling_price', 'purchased_quantity')->using(PurchasedItem::class)->as('purchase_record');
  }

  public function sale_receipt()
  {
    return $this->hasOneThrough(ProductReceipt::class, PurchasedItem::class, 'purchased_item_id', 'product_sale_record_id', null, 'product_sale_record_id')->where('purchased_item_type', array_search(static::class, Relation::morphMap()) ?: static::class);
  }

  public function buyer()
  {
    return $this->sale_record->buyer();
  }

  /**
   * Find an item from the products or swap deals table making sure to retrieve only one
   *
   * @param string $identifier
   * @param integer $status_id
   *
   * @return self
   * @throws ModelNotFoundException
   * @throws MultipleRecordsFoundException
   * @deprecated use lar 8 sole method instead
   */
  static function findSoleItem(string $identifier, int $status_id = null): self
  {
    $shop_items = collect(Product::where(function ($query) use ($status_id) {
      if (!is_null($status_id)) {
        $query->where('product_status_id', $status_id);
      }
    })->where(fn ($query) => $query->where('imei', $identifier)->orWhere('serial_no', $identifier)->orWhere('model_no', $identifier))->get())->merge(SwapDeal::where(function ($query) use ($status_id) {
      if (!is_null($status_id)) {
        $query->where('product_status_id', $status_id);
      }
    })->where(fn ($query) => $query->where('imei', $identifier)->orWhere('serial_no', $identifier)->orWhere('model_no', $identifier))->get());

    if ($shop_items->isEmpty()) {
      throw new ModelNotFoundException('No product found', 422);
    } elseif ($shop_items->count() > 1) {
      throw new MultipleRecordsFoundException("Multiple products found", 422);
    } elseif ($shop_items->count() === 1) {
      return $shop_items->first();
    }
  }


  /**
   * Find an item from the products or swap deals table making sure to retrieve only one
   *
   * @param string $identifier
   * @param integer $status_id
   *
   * @return self
   * @throws ModelNotFoundException
   * @throws MultipleRecordsFoundException
   * @deprecated use lar 8 sole method instead
   */
  static function findSoleItemByUuid(string $uuid, int $status_id = null): self
  {
    $shop_items = collect(Product::where('product_uuid', $uuid)->where(function ($query) use ($status_id) {
      if (!is_null($status_id)) {
        $query->where('product_status_id', $status_id);
      }
    })->get())->merge(SwapDeal::where('product_uuid', $uuid)->where(function ($query) use ($status_id) {
      if (!is_null($status_id)) {
        $query->where('product_status_id', $status_id);
      }
    })->get());

    if ($shop_items->isEmpty()) {
      throw new ModelNotFoundException('No product found', 422);
    } elseif ($shop_items->count() > 1) {
      throw new MultipleRecordsFoundException("Multiple products found", 422);
    } elseif ($shop_items->count() === 1) {
      return $shop_items->first();
    }
  }

}
