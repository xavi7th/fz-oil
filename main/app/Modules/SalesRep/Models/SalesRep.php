<?php

namespace App\Modules\SalesRep\Models;

use App\Modules\FzStaff\Models\FzStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SalesRep\Database\Factories\SalesRepFactory;

class SalesRep extends FzStaff
{
  use HasFactory;

  protected $table = parent::TABLE_NAME;

  protected static function newFactory()
  {
    return SalesRepFactory::new();
  }

  protected static function booted()
  {
    static::addGlobalScope('salesrep', function (Builder $builder) {
      $builder->where('staff_role_id', StaffRole::salesRepId());
    });
  }
}
