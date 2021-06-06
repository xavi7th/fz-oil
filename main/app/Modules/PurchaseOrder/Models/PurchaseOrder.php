<?php

namespace App\Modules\PurchaseOrder\Models;

use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Database\Factories\PurchaseOrderFactory;

/**
 * App\Modules\PurchaseOrder\Models\PurchaseOrder
 *
 * @property int $id
 * @property string $payment_type
 * @property int $fz_customer_id
 * @property int $fz_product_type_id
 * @property int $fz_price_batch_id
 * @property int $sales_rep_id
 * @property int $purchased_quantity
 * @property string $total_selling_price
 * @property int $is_swap_transaction
 * @property int|null $swap_product_type_id
 * @property int|null $swap_quantity
 * @property string|null $swap_value
 * @property string $total_amount_paid
 * @property int $is_lodged
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read FzCustomer $buyer
 * @property-read FzStock $fz_stock
 * @property-read mixed $total_cost_price
 * @method static Builder|PurchaseOrder bank()
 * @method static Builder|PurchaseOrder cash()
 * @method static Builder|PurchaseOrder credit()
 * @method static \App\Modules\PurchaseOrder\Database\Factories\PurchaseOrderFactory factory(...$parameters)
 * @method static Builder|PurchaseOrder newModelQuery()
 * @method static Builder|PurchaseOrder newQuery()
 * @method static Builder|PurchaseOrder notLodged()
 * @method static Builder|PurchaseOrder query()
 * @method static Builder|PurchaseOrder today()
 * @method static Builder|PurchaseOrder whereCreatedAt($value)
 * @method static Builder|PurchaseOrder whereDeletedAt($value)
 * @method static Builder|PurchaseOrder whereFzCustomerId($value)
 * @method static Builder|PurchaseOrder whereFzPriceBatchId($value)
 * @method static Builder|PurchaseOrder whereFzProductTypeId($value)
 * @method static Builder|PurchaseOrder whereId($value)
 * @method static Builder|PurchaseOrder whereIsLodged($value)
 * @method static Builder|PurchaseOrder whereIsSwapTransaction($value)
 * @method static Builder|PurchaseOrder wherePaymentType($value)
 * @method static Builder|PurchaseOrder wherePurchasedQuantity($value)
 * @method static Builder|PurchaseOrder whereSalesRepId($value)
 * @method static Builder|PurchaseOrder whereSwapProductTypeId($value)
 * @method static Builder|PurchaseOrder whereSwapQuantity($value)
 * @method static Builder|PurchaseOrder whereSwapValue($value)
 * @method static Builder|PurchaseOrder whereTotalAmountPaid($value)
 * @method static Builder|PurchaseOrder whereTotalSellingPrice($value)
 * @method static Builder|PurchaseOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseOrder extends Model
{
  use HasFactory;

  protected $fillable = ['fz_customer_id', 'fz_product_type_id', 'fz_price_batch_id', 'sales_rep_id', 'is_swap_transaction', 'purchased_quantity', 'swap_product_type_id', 'swap_quantity', 'swap_value', 'total_selling_price', 'total_amount_paid', 'payment_type', 'is_lodged'];

  const DASHBOARD_ROUTE_PREFIX = 'purchase-orders';
  const ROUTE_NAME_PREFIX = 'purchaseorders.';

  public function fz_stock()
  {
    return $this->belongsTo(FzStock::class, 'fz_product_type_id', 'fz_product_type_id')->where('fz_price_batch_id', $this->fz_price_batch_id);
  }

  public function buyer()
  {
    return $this->belongsTo(FzCustomer::class, 'fz_customer_id');
  }

  static function cashInOffice(): float
  {
    return self::cash()->notLodged()->sum('total_amount_paid');
  }

  public function getTotalCostPriceAttribute()
  {
    return $this->quantity * $this->product_type->cost_price;
  }

  public function scopeNotLodged(Builder $query)
  {
    return $query->where('is_lodged', false);
  }

  public function scopeCash(Builder $query)
  {
    return $query->where('payment_type', 'cash');
  }

  public function scopeBank(Builder $query)
  {
    return $query->where('payment_type', 'bank');
  }

  public function scopeCredit(Builder $query)
  {
    return $query->where('payment_type', 'credit');
  }

  public function scopeToday(Builder $query)
  {
    return $query->whereDate('created_at', today());
  }

  protected static function newFactory()
  {
    return PurchaseOrderFactory::new();
  }
}
