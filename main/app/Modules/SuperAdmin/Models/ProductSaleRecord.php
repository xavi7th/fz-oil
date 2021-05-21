<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Modules\SuperAdmin\Models\ProductSaleRecord
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $product_type
 * @property int|null $buyer_id
 * @property string|null $buyer_type
 * @property string|null $other_items
 * @property string|null $other_items_amount
 * @property string|null $total_cost_price
 * @property string|null $total_selling_price
 * @property string|null $total_amount_paid
 * @property string|null $selling_price
 * @property float $online_rep_bonus
 * @property float $walk_in_rep_bonus
 * @property int|null $sales_channel_id
 * @property int|null $online_rep_id
 * @property int|null $sales_rep_id
 * @property string|null $sales_rep_type
 * @property int|null $sale_confirmed_by
 * @property string|null $sale_confirmer_type
 * @property int $is_swap_transaction
 * @property int|null $product_dispatch_request_id
 * @property string|null $delivered_at
 * @property int|null $office_branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\SuperAdmin\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductSaleRecord onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereBuyerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereBuyerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereIsSwapTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereOfficeBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereOnlineRepBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereOnlineRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereOtherItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereOtherItemsAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereProductDispatchRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSaleConfirmedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSaleConfirmerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSalesChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSalesRepType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereTotalAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereTotalCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereTotalSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSaleRecord whereWalkInRepBonus($value)
 * @method static \Illuminate\Database\Query\Builder|ProductSaleRecord withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductSaleRecord withoutTrashed()
 * @mixin \Eloquent
 */
class ProductSaleRecord extends BaseModel
{
  use SoftDeletes;
  use Compoships;

  protected $fillable = [
  ];

  protected $casts = [
  ];

  protected $dispatchesEvents = [
    'updated' => ProductSaleRecordUpdated::class,
    'saved' => ProductSaleRecordSaved::class,
  ];

  protected $appends = ['total_bank_payments_amount', 'is_payment_complete'];

  public function products()
  {
    return $this->morphedByMany(Product::class, 'purchased_item', 'purchased_items', 'product_sale_record_id')->withTimestamps()->withPivot('selling_price', 'purchased_quantity')->using(PurchasedItem::class);
  }


}
