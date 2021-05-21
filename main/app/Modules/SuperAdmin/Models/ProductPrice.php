<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Modules\SuperAdmin\Models\ProductPrice
 *
 * @property int $id
 * @property int $product_batch_id
 * @property int $product_brand_id
 * @property int $product_model_id
 * @property int $product_color_id
 * @property int $storage_size_id
 * @property int $product_grade_id
 * @property int $product_supplier_id
 * @property float $cost_price
 * @property float|null $proposed_selling_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProductSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereProposedSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereStorageSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductPrice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductPrice withoutTrashed()
 * @mixin \Eloquent
 */
class ProductPrice extends BaseModel
{
  use SoftDeletes;

  protected $fillable = [
  ];

  protected $casts = [
  ];


  protected static function boot()
  {
    parent::boot();
  }
}
