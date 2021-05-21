<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'superadmin';
    const ROUTE_NAME_PREFIX = 'superadmin.';


    protected static function newFactory()
    {
        return SuperAdminFactory::new();
    }
}
