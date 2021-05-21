<?php

namespace App\Modules\FzStaff\Models;

use App\User;
use App\Modules\SuperAdmin\Traits\IsAStaff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStaff\Database\Factories\FzStaffFactory;

class FzStaff extends User
{
  use IsAStaff, HasFactory;

  protected $fillable = [

  ];
  protected $hidden = [
  ];

  protected $casts = [
  ];

  const DASHBOARD_ROUTE_PREFIX = 'sales-reps';
  const ROUTE_NAME_PREFIX = 'salesreps.';


  public function getFullNameAttribute()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  protected static function newFactory()
  {
      return FzStaffFactory::new();
  }
}
