<?php

namespace App\Modules\OfficeExpense\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use App\Modules\OfficeExpense\Http\Requests\CreateOfficeExpenseRequest;
use Gate;

class OfficeExpenseController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(OfficeExpense::DASHBOARD_ROUTE_PREFIX)->name(OfficeExpense::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list')->defaults('menu', __e('Office Expenses', 'viewAny,' . OfficeExpense::class, 'box', false));
      Route::post('create', [self::class, 'createOfficeExpense'])->name('create');
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
      'office_expenses' => OfficeExpense::with('sales_rep')->get(),
      'office_expenses_count' => OfficeExpense::count(),
      'office_expenses_amount' => OfficeExpense::sum('amount'),
      'can_create_expenses' =>Gate::allows('create', OfficeExpense::class)
    ]);
  }

  public function createOfficeExpense(CreateOfficeExpenseRequest $request)
  {
    $this->authorize('create', OfficeExpense::class);

    $request->createOfficeExpense();

    return redirect()->route('officeexpense.list')->withFlash(['success' => 'Office Expense created.']);
  }


}
