<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * App\Modules\SuperAdmin\Models\PurchasedItem
 *
 * @property int $id
 * @property int $product_sale_record_id
 * @property int $purchased_item_id
 * @property string $purchased_item_type
 * @property float $selling_price
 * @property int $purchased_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $purchased_item
 * @property-read \App\Modules\SuperAdmin\Models\ProductSaleRecord $sale_record
 * @method static Builder|PurchasedItem newModelQuery()
 * @method static Builder|PurchasedItem newQuery()
 * @method static Builder|PurchasedItem query()
 * @method static Builder|PurchasedItem today()
 * @method static Builder|PurchasedItem whereCreatedAt($value)
 * @method static Builder|PurchasedItem whereId($value)
 * @method static Builder|PurchasedItem whereProductSaleRecordId($value)
 * @method static Builder|PurchasedItem wherePurchasedItemId($value)
 * @method static Builder|PurchasedItem wherePurchasedItemType($value)
 * @method static Builder|PurchasedItem wherePurchasedQuantity($value)
 * @method static Builder|PurchasedItem whereSellingPrice($value)
 * @method static Builder|PurchasedItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchasedItem extends MorphPivot
{
  protected $fillable = ['selling_price', 'purchased_quantity'];
  public $incrementing = true;
  protected $table = "purchased_items";

  protected $casts = [
    'purchased_quantity' => 'int',
    'purchased_item_id' => 'int',
    'product_sale_record_id' => 'int',
    'selling_price' => 'float',
  ];

  public function purchased_item()
  {
    return $this->morphTo();
  }

  public function sale_record()
  {
    return $this->belongsTo(ProductSaleRecord::class, 'product_sale_record_id');
  }

  public function scopeToday(Builder $query)
  {
    return $query->whereDate('created_at', today());
  }

}
