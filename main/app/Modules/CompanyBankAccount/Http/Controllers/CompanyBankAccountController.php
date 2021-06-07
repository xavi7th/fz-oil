<?php

namespace App\Modules\CompanyBankAccount\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\CompanyBankAccount\Http\Requests\CreateCompanyBankAccountRequest;

class CompanyBankAccountController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(CompanyBankAccount::DASHBOARD_ROUTE_PREFIX)->name(CompanyBankAccount::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('index')->defaults('menu', __e('Bank Accounts', 'viewAny,' . CompanyBankAccount::class, 'box', false));
      Route::post('create', [self::class, 'store'])->name('create');
      Route::put('{bank_account}/update', [self::class, 'update'])->name('update');
      Route::put('{bank_account}/suspend', [self::class, 'suspend'])->name('suspend');
      Route::put('{bank_account}/activate', [self::class, 'activate'])->name('activate');
    });
  }

  public function index(Request $request)
  {
    $this->authorize('viewAny', CompanyBankAccount::class);
    return Inertia::render('CompanyBankAccount::ManageAccounts',[
      'company_bank_accounts' => CompanyBankAccount::all(),
      'company_bank_accounts_count' => CompanyBankAccount::count(),
      'can_create' => Gate::allows('create', CompanyBankAccount::class),
      'can_edit' => Gate::allows('update', CompanyBankAccount::class),
    ]);
  }

  public function store(CreateCompanyBankAccountRequest $request)
  {
    $this->authorize('create', CompanyBankAccount::class);

    $request->createAccount();
    return redirect()->route('companybankaccount.index')->withFlash(['success' => 'Bank account created. Sales reps can naow record transactions for this bank.']);
  }

  public function update(CreateCompanyBankAccountRequest $request, CompanyBankAccount $bank_account)
  {
    $this->authorize('update', CompanyBankAccount::class);

    $request->updateAccount();
    return redirect()->route('companybankaccount.index')->withFlash(['success' => 'Bank account updated. This update will reflect instantly.']);
  }

  public function suspend(Request $request, CompanyBankAccount $bank_account)
  {
    $this->authorize('suspend', CompanyBankAccount::class);

    $bank_account->is_active = false;
    $bank_account->save();

    return redirect()->route('companybankaccount.index')->withFlash(['success' => 'Bank account suspended. Transactions can no longer be recorded to this bank account.']);
  }

  public function activate(Request $request, CompanyBankAccount $bank_account)
  {
    $this->authorize('activate', CompanyBankAccount::class);

    $bank_account->is_active = true;
    $bank_account->save();

    return redirect()->route('companybankaccount.index')->withFlash(['success' => 'Bank account activated. Transactions can now be recorded to this bank account.']);
  }

}
