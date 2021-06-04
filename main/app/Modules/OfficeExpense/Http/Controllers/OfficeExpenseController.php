<?php

namespace App\Modules\OfficeExpense\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\OfficeExpense\Http\Requests\CreateOfficeExpenseRequest;

class OfficeExpenseController extends Controller
{
  static function routes()
  {
    Route::middleware('web')->prefix(OfficeExpense::DASHBOARD_ROUTE_PREFIX)->name(OfficeExpense::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list');
      Route::post('ceate', [self::class, 'createOfficeExpense'])->name('create');
    });
  }

  /**
   * Display a listing of the resource.
   * @return Renderable
   */
  public function index(Request $request)
  {
    $this->authorize('viewAny', OfficeExpense::class);

    return Inertia::render('OfficeExpense::ManageOfficeExpenses', [
      'office_expenses' => OfficeExpense::all(),
      'office_expenses_count' => OfficeExpense::count(),
    ]);
  }

  public function createOfficeExpense(CreateOfficeExpenseRequest $request)
  {
    $this->authorize('create', OfficeExpense::class);

    $request->createOfficeExpense();

    return redirect()->route('officeexpense.list')->withFlash(['success' => 'Office Expense created.']);
  }


}
