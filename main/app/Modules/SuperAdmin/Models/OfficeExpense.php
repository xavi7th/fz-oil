<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Modules\SuperAdmin\Models\OfficeExpense
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $recorder
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense newQuery()
 * @method static \Illuminate\Database\Query\Builder|OfficeExpense onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense today()
 * @method static \Illuminate\Database\Query\Builder|OfficeExpense withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OfficeExpense withoutTrashed()
 * @mixin \Eloquent
 */
class OfficeExpense extends BaseModel
{
  use SoftDeletes;
  protected $fillable = [];

  public function recorder()
  {
    return $this->morphTo();
  }


  public function scopeToday($query)
  {
    return $query->whereDay('created_at', today());
  }

  public function scopeThisMonth($query)
  {
    return $query->whereMonth('created_at', today());
  }

  protected static function boot()
  {
    parent::boot();
  }
}
