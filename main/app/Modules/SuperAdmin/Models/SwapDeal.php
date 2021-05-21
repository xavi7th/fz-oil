<?php

namespace App\Modules\SuperAdmin\Models;

use App\Modules\SuperAdmin\Models\ShopItem;
use App\Modules\SuperAdmin\Interfaces\ShopItem as ShopItemInterface;

/**
 * App\Modules\SuperAdmin\Models\SwapDeal
 *
 * @property int $id
 * @property int|null $app_user_id
 * @property string $description
 * @property string $owner_details
 * @property string|null $id_url
 * @property string|null $receipt_url
 * @property string|null $imei
 * @property string|null $serial_no
 * @property string|null $model_no
 * @property float $swap_value
 * @property float|null $selling_price
 * @property string|null $sold_at
 * @property int|null $swapped_in_record_id
 * @property int|null $swapped_with_id
 * @property string|null $swapped_with_type
 * @property int $product_status_id
 * @property int|null $office_branch_id
 * @property string $product_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Modules\SuperAdmin\Models\ProductPrice $product_price
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereAppUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereIdUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereModelNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereOfficeBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereOwnerDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereProductStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereReceiptUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSerialNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSoldAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSwapValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSwappedInRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSwappedWithId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereSwappedWithType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SwapDeal whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Modules\FzStaff\Models\ProductReceipt|null $sale_receipt
 */
class SwapDeal extends ShopItem implements ShopItemInterface
{
  protected $fillable = [
  ];

  protected $apennds = [];

  protected $casts = [
  ];

  /**
   * Mock a product price relationship fro swap deal to keep its API consistent with product
   * ? The relationship query will always return null because swapdDealModel.product_price_id will always give null
   * @uses select * from `product_prices` where `product_prices`.`id` is null and `product_prices`.`deleted_at` is null
   *
   */
  public function product_price()
  {
    return $this->belongsTo(
      ProductPrice::class
    )->withDefault(function ($product_price, $swap_deal) {
      $product_price->cost_price = $swap_deal->swap_value;
      $product_price->proposed_selling_price = $swap_deal->selling_price;
    });
  }

  protected static function boot()
  {
    parent::boot();
  }
}
