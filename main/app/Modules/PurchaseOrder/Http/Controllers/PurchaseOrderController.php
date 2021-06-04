<?php

namespace App\Modules\PurchaseOrder\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\PurchaseOrder\Models\CashLodgement;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\PurchaseOrder\Services\PurchaseOrderService;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\PurchaseOrder\Http\Requests\CreatePurchaseOrderRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseOrderController extends Controller
{

  static function routes()
  {
    Route::prefix(PurchaseOrder::DASHBOARD_ROUTE_PREFIX)->name(PurchaseOrder::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list');
      Route::get('cash-lodgement/create', [self::class, 'viewCashLodgements'])->name('cashlodgement.create');
      Route::post('cash-lodgement/create', [self::class, 'createCashLodgements']);
      Route::get('{customer}/create', [self::class, 'create'])->name('create');
      Route::post('{customer}/create', [self::class, 'store']);

    });
  }
  public function index()
  {
    $this->authorize('viewAny', PurchaseOrder::class);

    return Inertia::render('PurchaseOrder::List', [
      'purchase_orders' => PurchaseOrder::all(),
      'purchase_orders_count' => PurchaseOrder::count(),
    ]);
  }

  public function create(Request $request, FzCustomer $customer)
  {
    $this->authorize('create', PurchaseOrder::class);

    return Inertia::render('PurchaseOrder::Create', [
      'customer' => $customer,
      'stock_types' => FzProductType::all(),
      'price_batches' => FzPriceBatch::count(),
      'company_bank_accounts' => CompanyBankAccount::all()
    ]);
  }

  public function store(CreatePurchaseOrderRequest $request)
  {
    $this->authorize('create', PurchaseOrder::class);

    try {
      (new PurchaseOrderService)->setProductType($request->fz_product_type_id)
        ->setPriceBatch($request->fz_price_batch_id)
        ->setPurchasedQuantity($request->purchased_quantity)
        ->setSwapStatus($request->is_swap_purchase, $request->swap_quantity, $request->swap_product_type_id)
        ->setCustomer($request->fz_customer_id)
        ->setPaymentType($request->payment_type, $request->company_bank_account_id)
        ->setAmount($request->total_selling_price, $request->total_amount_paid)
        ->setSalesRep($request->user()->id)
        ->create();
    } catch (\Throwable $th) {
      return redirect()->route('purchaseorders.list')->withFlash(['error' => $th->getMessage()]);
    }

    return redirect()->route('purchaseorders.list')->withFlash(['success' => 'Customer\'s Purchase Order created.']);
  }

  public function viewCashLodgements(Request $request)
  {
    $this->authorize('viewAny', CashLodgement::class);

    return Inertia::render('PurchaseOrder::CashLodgements', [
      'cash_lodgements' => CashLodgement::all(),
      'cash_lodgements_count' => CashLodgement::count(),
    ]);
  }

  public function createCashLodgements(Request $request)
  {
    $this->authorize('create', CashLodgement::class);

    $request->validate([
      'company_bank_account_id' => ['required', 'exists:company_bank_accounts,id', function ($attribute, $value, $fail) {
        DB::table('company_bank_accounts')->where('id', $value)->first()->is_active ? null : $fail('This bank account has been suspended from use');
      }],
      'amount' => ['required', 'numeric'],
      'lodgement_date' => ['required', 'date'],
      'teller' => ['required', 'image']
    ]);

    try {
      (new PurchaseOrderService)->setImage('teller', 'cash-lodgement-tellers')->lodgeCashToBank($request->amount, $request->company_bank_account_id, $request->lodgement_date);
    } catch (ModelNotFoundException $th) {
      return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['error' => 'The bank account was not found']);
    } catch (\Throwable $th) {
      return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['error' => $th->getMessage()]);
    }

    return redirect()->route('purchaseorders.cashlodgement.create')->withFlash(['success' => 'Cash lodgement record created.']);
  }
}
