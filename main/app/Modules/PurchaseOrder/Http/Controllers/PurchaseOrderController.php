<?php

namespace App\Modules\PurchaseOrder\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\PurchaseOrder\Models\CashLodgement;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\SuperAdmin\Traits\AccessibleToAllStaff;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\PurchaseOrder\Services\PurchaseOrderService;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\PurchaseOrder\Http\Requests\CreatePurchaseOrderRequest;
use Gate;

class PurchaseOrderController extends Controller
{
  use AccessibleToAllStaff;

  static function routes()
  {
    Route::prefix(PurchaseOrder::DASHBOARD_ROUTE_PREFIX)->name(PurchaseOrder::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list')->defaults('menu', __e('Purchase Orders', 'viewAny,' . PurchaseOrder::class, 'box', false));

      Route::get('sales/{customer}/create', [self::class, 'create'])->name('create');
      Route::post('sales/{customer}/create', [self::class, 'store']);

      Route::name('cashlodgement.')->prefix('cash-lodgement')->group(function () {
        Route::get('create', [self::class, 'viewCashLodgements'])->name('create')->defaults('menu', __e('Cash Lodgements', 'viewAny,' . CashLodgement::class, 'box', false));
        Route::post('create', [self::class, 'createCashLodgement']);
      });

      Route::prefix('direct-swap-transactions')->name('directswaptransactions.')->group(function () {
        Route::get('{customer}/create', [self::class, 'viewDirectSwapTransactions'])->name('create');
        Route::post('{customer}/create', [self::class, 'createDirectSwapTransaction']);
      });
    });
  }
  public function index(Request $request)
  {
    $this->authorize('viewAny', PurchaseOrder::class);

    return Inertia::render('PurchaseOrder::ManageCustomerPurchaseOrder', [
      'purchase_orders' => PurchaseOrder::with('product_type', 'buyer')->get(),
      'purchase_orders_count' => PurchaseOrder::count(),
      'can_create_customer' => $request->user()->can('create', PurchaseOrder::class),
      'can_create_view_lodgement' => $request->user()->can('viewAny', CashLodgement::class),
      'can_create_cash_lodgement' => $request->user()->can('create', CashLodgement::class),
      'can_create_view_direct_swap_transactions' => $request->user()->can('viewAny', DirectSwapTransaction::class),
      'can_create_cash_direct_swap_transactions' => $request->user()->can('create', DirectSwapTransaction::class),
    ]);
  }

  public function create(Request $request, FzCustomer $customer)
  {
    $this->authorize('viewAny', PurchaseOrder::class);

    return Inertia::render('PurchaseOrder::ManageCustomerPurchaseOrder', [
      'customer' => $customer,
      'stock_types' => FzProductType::all(),
      'price_batches_count' => FzPriceBatch::count(),
      'price_batches' => FzPriceBatch::all(),
      'company_bank_accounts' => CompanyBankAccount::all(),
      'purchase_orders' => $customer->purchase_orders->load('product_type', 'buyer', 'swap_product_type', 'bank'),
      'purchase_orders_count' => $customer->purchase_orders->count(),
      'can_create_purchase_order' => Gate::allows('create', PurchaseOrder::class)
    ]);
  }

  public function store(CreatePurchaseOrderRequest $request, FzCustomer $customer)
  {
    $this->authorize('create', PurchaseOrder::class);

    DB::beginTransaction();

    try {
      (new PurchaseOrderService)->setProductType($request->fz_product_type_id)
        ->setPriceBatch($request->fz_price_batch_id)
        ->setPurchasedQuantity($request->purchased_quantity)
        ->setSwapStatus($request->is_swap_purchase, $request->swap_quantity, $request->swap_product_type_id)
        ->setCustomer($customer->id)
        ->setPaymentType($request->payment_type, $request->company_bank_account_id)
        ->setAmount($request->total_selling_price, $request->total_amount_paid)
        ->setSalesRep($request->user()->id)
        ->create();
    } catch (\Throwable $th) {
      return redirect()->route('purchaseorders.create', $customer)->withFlash(['error' => $th->getMessage()]);
    }

    DB::commit();

    return redirect()->route('purchaseorders.create', $customer)->withFlash(['success' => 'Customer\'s Purchase Order created.']);
  }

  public function viewCashLodgements(Request $request)
  {
    $this->authorize('viewAny', CashLodgement::class);

    return Inertia::render('PurchaseOrder::CashLodgements', [
      'company_bank_accounts' => CompanyBankAccount::all(),
      'cash_lodgements' => CashLodgement::with('sales_rep', 'bank')->get(),
      'cash_lodgements_amount' => CashLodgement::sum('amount'),
      'cash_lodgements_count' => CashLodgement::count(),
      'can_create_cash_lodgements' => Gate::allows('create', CashLodgement::class),
      'cash_in_office' => SuperAdmin::cashInOffice(),
      'recorded_cash_in_office' => $request->user()->cashInOffice(),
    ]);
  }

  public function createCashLodgement(Request $request)
  {
    $this->authorize('create', CashLodgement::class);

    DB::beginTransaction();

    $request->validate([
      'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id', function ($attribute, $value, $fail) {
        DB::table('company_bank_accounts')->where('id', $value)->first()->is_active ? null : $fail('This bank account has been suspended from use');
      }],
      'amount' => ['required', 'numeric', fn ($attribute, $value, $fail) => SuperAdmin::cashInOffice() > $value ? null : $fail('There is not enough cash in the office to make this cash lodgement.')],
      'lodgement_date' => ['required', 'date'],
      'teller' => ['required', 'image']
    ]);

    try { (new PurchaseOrderService)
        ->setSalesRep($request->user()->id)
        ->setImage('teller', 'cash-lodgement-tellers')
        ->setAmount(null, $request->amount)
        ->setPaymentType(null, $request->company_bank_account_id)
        ->lodgeCashToBank($request->lodgement_date);
    } catch (ModelNotFoundException $th) {
      return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['error' => 'The bank account was not found']);
    } catch (\Throwable $th) {
      return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['error' => $th->getMessage()]);
    }

    DB::commit();
    return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['success' => 'Cash lodgement record created.']);
  }

  public function viewDirectSwapTransactions(Request $request)
  {
    $this->authorize('viewAny', DirectSwapTransaction::class);

    return Inertia::render('PurchaseOrder::DirectSwapTransactions', [
      'direct_swap_transactions' => DirectSwapTransaction::all(),
      'direct_swap_transactions_count' => DirectSwapTransaction::count(),
    ]);
  }

  public function createDirectSwapTransaction(Request $request, FzCustomer $customer)
  {
    DB::beginTransaction();

    $this->authorize('create', DirectSwapTransaction::class);

    $request->validate([
      'fz_product_type_id' => ['required', 'exists:fz_product_types,id'],
      'quantity' => ['required', 'numeric'],
      'customer_paid_via' => ['required', 'in:cash,bank'],
      'amount' => ['required', 'numeric', function ($attribute, $value, $fail) use ($request) {
        if ($request->customer_paid_via == 'cash') {
          SuperAdmin::cashInOffice() > $value ? null : $fail('There is not enough cash in the office to facilitate this trade in swap');
        }
      }],
      'company_bank_account_id' => ['exclude_unless:customer_paid_via,cash', 'required', 'exists:company_bank_accounts,id'],
    ]);

    try {
      (new PurchaseOrderService)->setAmount(null, $request->amount)
        ->setProductType($request->fz_product_type_id)
        ->setPaymentType($request->customer_paid_via, $request->company_bank_account_id)
        ->setPurchasedQuantity($request->quantity)
        ->setCustomer($customer->id)
        ->setSalesRep($request->user()->id)
        ->createDirectSwapTransaction();
    } catch (ModelNotFoundException $th) {
      return redirect()->route('purchaseorders.directswaptransactions.create')->withFlash(['error' => 'The bank account was not found']);
    } catch (\Throwable $th) {
      return redirect()->route('purchaseorders.directswaptransactions.create')->withFlash(['error' => $th->getMessage()]);
    }

    DB::commit();

    return redirect()->route('purchaseorders.directswaptransactions.create', $customer)->withFlash(['success' => 'Customer trade in recorded.']);
  }
}
