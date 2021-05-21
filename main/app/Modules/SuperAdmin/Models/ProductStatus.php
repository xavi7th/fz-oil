<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use App\Modules\SuperAdmin\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Modules\SuperAdmin\Models\ProductStatus
 *
 * @property int $id
 * @property string $status
 * @property string $status_slug
 * @property string $scope
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProductStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereStatusSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ProductStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProductStatus withoutTrashed()
 * @mixin \Eloquent
 */
class ProductStatus extends BaseModel
{
  use SoftDeletes;

  protected $fillable = ['status'];

  public function products()
  {
    return $this->hasMany(Product::class);
  }

  protected static function boot()
  {
    parent::boot();
  }
}
