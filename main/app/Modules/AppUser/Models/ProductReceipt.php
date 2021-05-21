<?php

namespace App\Modules\AppUser\Models;

use App\BaseModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Modules\AppUser\Models\ProductReceipt
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $product_sale_record_id
 * @property string|null $product_type
 * @property string $user_email
 * @property string $user_name
 * @property string $user_phone
 * @property string $user_address
 * @property string $user_city
 * @property string $order_ref
 * @property string $amount_paid
 * @property float|null $tax_rate
 * @property float|null $delivery_fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductReceipt onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereOrderRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereProductSaleRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUserAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUserCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductReceipt whereUserPhone($value)
 * @method static \Illuminate\Database\Query\Builder|ProductReceipt withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductReceipt withoutTrashed()
 * @mixin \Eloquent
 */
class ProductReceipt extends BaseModel
{
  use SoftDeletes;

  protected $fillable = [];

  protected $casts = [
  ];


  protected static function boot()
  {
    parent::boot();

    static::creating(function (self $productReceipt) {
      $productReceipt->order_ref = (string)Str::random();
    });
  }
}
