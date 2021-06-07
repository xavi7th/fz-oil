<?php

namespace App\Modules\FzCustomer\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzCustomer\Models\CreditTransaction;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use App\Modules\FzCustomer\Transformers\FzCustomerTransformer;
use App\Modules\FzCustomer\Http\Requests\CreateCustomerRequest;
use App\Modules\FzCustomer\Http\Requests\CreateCustomerCreditRepaymentTransactionRequest;

class FzCustomerController extends Controller
{

  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(FzCustomer::DASHBOARD_ROUTE_PREFIX)->name(FzCustomer::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list')->defaults('menu', __e('Manage Customers', 'viewAny,' . FzCustomer::class, 'box'));
      Route::get('{customer}/details', [self::class, 'details'])->name('details');
      Route::post('create', [self::class, 'store'])->name('create');
      Route::put('{customer}/update', [self::class, 'update'])->name('update');
      Route::put('{customer}/suspend', [self::class, 'suspend'])->name('suspend');
      Route::put('{customer}/activate', [self::class, 'activate'])->name('activate');

      Route::prefix('credit-transactions')->name('credit_transactions.')->group(function () {
        Route::get('{customer}', [self::class, 'viewCustomerCreditTransactions'])->name('list');
        Route::post('{customer}/repayment', [self::class, 'createCustomerCreditRepaymentTransaction'])->name('repayment');
      });
    });
  }

  public function index(Request $request)
  {
    $this->authorize('viewAny', FzCustomer::class);

    return Inertia::render('FzCustomer::ManageCustomers', [
      'fz_customers' => FzCustomer::all(),
      'fz_customer_count' => FzCustomer::count(),
      'fz_active_customer_count' => FzCustomer::active()->count(),
      'fz_suspended_customer_count' => FzCustomer::suspended()->count(),
      'fz_flagged_customer_count' => FzCustomer::flagged()->count(),
      'can_view_details' => $request->user()->can('viewAny', FzCustomer::class),
      'can_view_credit_transactions' => $request->user()->can('viewAny', CreditTransaction::class),
    ]);
  }

  public function details(Request $request, FzCustomer $customer)
  {
    $this->authorize('view', $customer);

    return Inertia::render('FzCustomer::CustomerDetails', [
      'customer_details' => (new FzCustomerTransformer)->transformForDetails($customer),
    ]);
  }

  public function store(CreateCustomerRequest $request)
  {
    $this->authorize('create', FzCustomer::class);

    $request->createFzCustomer();

    return redirect()->route('fzcustomer.list')->withFlash(['success' => 'Customer account created. Transactions can be carried out for the user.']);
  }

  public function suspend(Request $request, FzCustomer $customer)
  {
    $this->authorize('suspend', $customer);

    $customer->is_active = false;
    $customer->save();

    return redirect()->route('fzcustomer.list')->withFlash(['success' => 'Customer\'s account has been suspended. The sales rep will be notified to contact their supervisor on next customer transaction.']);
  }

  public function activate(Request $request, FzCustomer $customer)
  {
    $this->authorize('activate', $customer);

    $customer->is_active = true;
    $customer->save();

    return redirect()->route('fzcustomer.list')->withFlash(['success' => 'Customer\'s account has been activated. They can resume transactions without issues.']);
  }

  public function update(CreateCustomerRequest $request, FzCustomer $customer)
  {
    $this->authorize('update', $customer);

    $request->updateFzCustomeDetails();

    return redirect()->route('fzcustomer.list')->withFlash(['success' => 'Customer\'s details updated.']);
  }

  public function destroy($id)
  {
    //
  }

  public function viewCustomerCreditTransactions(Request $request, FzCustomer $customer)
  {
    $this->authorize('viewAny', CreditTransaction::class);
    return Inertia::render('FzCustomer::ManageCustomerCredit', [
      'credit_transactions' => $customer->credit_transactions,
      'credit_transactions_count' => $customer->credit_transactions()->count(),
    ]);
  }

  public function createCustomerCreditRepaymentTransaction(CreateCustomerCreditRepaymentTransactionRequest $request, FzCustomer $customer)
  {
    $this->authorize('create', CreditTransaction::class);

    try {
      $request->createRepaymentTransaction();
    } catch (\Throwable $th) {
      return redirect()->route('fzcustomer.credit_transactions.list', $customer)->withFlash(['error' => $th->getMessage()]);
    }

    return redirect()->route('fzcustomer.credit_transactions.list', $customer)->withFlash(['success' => 'Repayment transaction created.']);
  }
}
