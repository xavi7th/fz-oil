<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Support\Str;
use Awobaz\Compoships\Compoships;
use Ankurk91\Eloquent\BelongsToOne;
use App\Modules\SuperAdmin\Models\ShopItem;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\SuperAdmin\Interfaces\ShopItem as ShopItemInterface;

/**
 * App\Modules\SuperAdmin\Models\Product
 *
 * @property int $id
 * @property int|null $app_user_id
 * @property int $product_category_id
 * @property int $product_model_id
 * @property int $product_brand_id
 * @property int $product_batch_id
 * @property int $product_color_id
 * @property int $product_grade_id
 * @property int $product_supplier_id
 * @property int $storage_size_id
 * @property int|null $ram_size_id
 * @property int|null $storage_type_id
 * @property int|null $processor_speed_id
 * @property string|null $imei
 * @property string|null $serial_no
 * @property string|null $model_no
 * @property int $is_local
 * @property int $is_paid
 * @property int $product_status_id
 * @property string|null $sold_at
 * @property string $product_uuid
 * @property int $stocked_by
 * @property string $stocker_type
 * @property int $office_branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\SuperAdmin\Models\ProductSaleRecord[] $product_sales_record
 * @property-read int|null $product_sales_record_count
 * @property-read \App\Modules\FzStaff\Models\ProductReceipt|null $sale_receipt
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImei($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereModelNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOfficeBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProcessorSpeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRamSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSerialNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSoldAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStockerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStorageSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStorageTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends ShopItem implements ShopItemInterface
{
  use SoftDeletes, Compoships, BelongsToOne;

  protected $fillable = [
  ];

  protected $casts = [

  ];

  protected $dispatchesEvents = [
    'updated' => ProductUpdated::class,
    'saved' => ProductSaved::class,
  ];

  /** @deprecated v1 */
  public function product_sales_record()
  {
    return $this->morphMany(ProductSaleRecord::class, 'product');
  }

  public function is_sold(): bool
  {
    /**
     * Check if the product has been sold already or confirmed
     */
    return $this->product_status_id === ProductStatus::soldId()
      || $this->product_status_id === ProductStatus::saleConfirmedId()
      || $this->product_status_id === ProductStatus::soldByResellerId();
  }

  public function is_confirmed_sold(): bool
  {
    return $this->product_status_id === ProductStatus::saleConfirmedId();
  }


  public function in_stock(): bool
  {
    /**
     * Check if the product has been sold already or confirmed
     */
    return $this->product_status_id === ProductStatus::inStockId();
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($product) {
      $product->product_uuid = (string)Str::uuid();
    });

  }
}
