<?php

namespace App\Modules\PurchaseOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Modules\PurchaseOrder\Models\PurchaseReceipt
 *
 * @property int $id
 * @property int $purchase_order_id
 * @property int $sales_rep_id
 * @property int $fz_product_type_id
 * @property string $product
 * @property string $cashier_name
 * @property string $payment_type
 * @property int $quantity
 * @property string $transaction_amount
 * @property string $amount_tendered
 * @property string $change_received
 * @property int $is_printed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereAmountTendered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereCashierName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereChangeReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereFzProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereIsPrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt wherePurchaseOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereTransactionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseReceipt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseReceipt extends Model
{

  protected $fillable = [  'purchase_order_id','sales_rep_id','fz_product_type_id','product','cashier_name','payment_type','quantity','transaction_amount','amount_tendered','change_received','is_printed',];

}
