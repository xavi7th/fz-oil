<?php

namespace App\Modules\SuperAdmin\Traits;

use Throwable;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\SuperAdmin\Models\ActivityLog;
use App\Modules\SuperAdmin\Transformers\StaffTransformer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Modules\SuperAdmin\Http\Requests\CreateStaffRequest;

/**
 * A trait to make a model commentable
 */
trait IsAStaff
{
  use AuthorizesRequests;

  static function findByEmail(string $email)
  {
    return self::whereEmail($email)->first();
  }

  public function is_verified(): bool
  {
    return $this->verified_at !== null || $this->isSuperAdmin();
  }

  /**
   * @use self::staffRoutes() after the get route group
   *
   * @return void
   */
  static function staffRoutes()
  {
    Route::middleware('auth:super_admin,supervisor')->group(function () {
      Route::get('list', [self::class, 'getAllStaff'])->name('list')->defaults('ex', __e('ss', 'aperture'));
      Route::post('create', [self::class, 'createStaff'])->name('create');
      Route::put('{staff}/edit', [self::class, 'editStaff'])->name('update');
      Route::put('{staff}/suspend', [self::class, 'suspendStaff'])->name('suspend');
      Route::put('{staff}/activate', [self::class, 'activateStaff'])->name('activate');
      Route::delete('{staff}/delete', [self::class, 'deleteStaff'])->name('delete');
      // Route::put('{staff}/restore', [self::class, 'restoreStaff'])->name('restore');
    });
  }

  public function getAllStaff(Request $request)
  {
    $this->authorize('viewAny', self::class);

    return Inertia::render('SuperAdmin,ManageStaff/Manage' . Str::of(__CLASS__)->afterLast('\\')->plural(), [
      (string)Str::of(class_basename(self::class))->snake()->plural() => (new StaffTransformer)->collectionTransformer(self::all(), 'transformForSuperAdminViewSalesReps'),
    ]);
  }

  public function createStaff(CreateStaffRequest $request)
  {
    $this->authorize('create', self::class);

    $userType =  Str::of(class_basename(self::class))->snake()->plural();

    try {
      $request->createStaff(self::class);
    } catch (\Throwable $th) {
      return back()->withFlash(['error' => $th->getMessage()]);
    }

    return redirect()->route($userType->singular()->slug('') .'.list')->withFlash(['success' => $userType->singular()->replaceFirst('_',' ')->title() . ' account created. Activate the account so the user can login']);
  }

  public function editStaff(CreateStaffRequest $request, self $staff)
  {
    $this->authorize('update', $staff);

    $userType =  Str::of(class_basename(self::class))->snake()->plural();

    try {
      $request->updateStaff($staff);
    } catch (\Throwable $th) {
      return back()->withFlash(['error' => $th->getMessage()]);
    }

    return redirect()->route($userType->singular()->slug('') .'.list')->withFlash(['success' => $userType->singular()->replaceFirst('_',' ')->title() . ' account updated.']);
  }

  public function suspendStaff(self $staff)
  {
    $userType = Str::of(class_basename(self::class))->snake()->plural();

    $this->authorize('suspend', $staff);

    $staff->is_active = false;
    $staff->save();

    return redirect()->route($userType->singular()->slug('') .'.list')->withFlash(['success' => $userType->singular()->replaceFirst('_',' ')->title() . ' account suspended. They will no longer be able to login']);
  }

  public function activateStaff(self $staff)
  {
    $this->authorize('activate', $staff);
    $userType = Str::of(class_basename(self::class))->snake()->plural();

    $staff->is_active = true;
    $staff->save();

    return redirect()->route($userType->singular()->slug('') .'.list')->withFlash(['success' => $userType->singular()->replaceFirst('_',' ')->title() . ' account activated.']);
  }

  public function restoreStaff(self $staff)
  {
    $this->authorize('restore', $staff);

    $staff->is_active = true;
    $staff->save();

    ActivityLog::logUserActivity(auth()->user()->email . ' restored the account of ' . $staff->email);

    return back()->withFlash(['success' => 'User account re-activated']);
  }

  public function deleteStaff(self $staff)
  {
    $this->authorize('delete', $staff);
    $userType = Str::of(class_basename(self::class))->snake()->plural();

    $staff->delete();

    return redirect()->route($userType->singular()->slug('') .'.list')->withFlash(['success' => $userType->singular()->replaceFirst('_',' ')->title() . ' account permanently deleted.']);
  }

  public function scopeActive(Builder $query)
  {
    return $query->whereIsActive(true);
  }

  protected static function booted()
  {
    static::addGlobalScope('safeRecords', function (Builder $builder) {
      $builder->where('id', '>', 1);
    });

  }
}
