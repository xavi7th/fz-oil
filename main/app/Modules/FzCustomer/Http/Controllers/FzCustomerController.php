<?php

namespace App\Modules\FzCustomer\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzCustomer\Http\Requests\CreateCustomerRequest;

class FzCustomerController extends Controller
{

  static function routes()
  {
    Route::prefix(FzCustomer::DASHBOARD_ROUTE_PREFIX)->name(FzCustomer::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list');
      Route::post('create', [self::class, 'store'])->name('create');
      Route::get('credit-repayment', [self::class, 'manageCustomerCredit'])->name('credit');
    });
  }
    public function index()
    {
      $this->authorize('viewAny', FzCustomer::class);

      return Inertia::render('FzCustomer::ManageCustomers',[
        'fz_customers' => FzCustomer::all(),
        'fz_customer_count' => FzCustomer::count(),
        'fz_active_customer_count' => FzCustomer::active()->count(),
        'fz_suspended_customer_count' => FzCustomer::suspended()->count(),
        'fz_flagged_customer_count' => FzCustomer::flagged()->count(),
      ]);
    }

    public function store(CreateCustomerRequest $request)
    {
      $this->authorize('create', FzCustomer::class);

      $request->createFzCustomer();

      return redirect()->route('fzcustomer.list')->withFlash(['success' => 'Customer account created. Transactions can be carried out for the user.']);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function manageCustomerCredit(Request $request)
    {
      return Inertia::render('FzCustomer::ManageCustomerCredit')->withViewData([
        'title' => 'Hello theEects',
        'metaDesc' => ' This page is ...'
      ]);
    }
}
