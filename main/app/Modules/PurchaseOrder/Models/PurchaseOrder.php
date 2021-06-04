<?php

namespace App\Modules\PurchaseOrder\Models;

use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Database\Factories\PurchaseOrderFactory;

class PurchaseOrder extends Model
{
  use HasFactory;

  protected $fillable = ['fz_customer_id', 'fz_product_type_id', 'fz_price_batch_id', 'sales_rep_id', 'is_swap_transaction', 'purchased_quantity', 'swap_product_type_id', 'swap_quantity', 'swap_value', 'total_selling_price', 'total_amount_paid', 'payment_type'];

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

  public function getTotalCostPriceAttribute()
  {
    return $this->quantity * $this->product_type->cost_price;
  }

  public function scopeCash(Builder $query)
  {
    return $query->where('payment_type', 'cash');
  }

  public function scopeTransfer(Builder $query)
  {
    return $query->where('payment_type', 'transfer');
  }

  public function scopeDeposit(Builder $query)
  {
    return $query->where('payment_type', 'deposit');
  }

  public function scopeCredit(Builder $query)
  {
    return $query->where('payment_type', 'credit');
  }

  protected static function newFactory()
  {
    return PurchaseOrderFactory::new();
  }
}
