<?php

namespace App\Modules\PurchaseOrder\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\PurchaseOrder\Services\PurchaseOrderService;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\PurchaseOrder\Http\Requests\CreatePurchaseOrderRequest;

class PurchaseOrderController extends Controller
{

  static function routes()
  {
    Route::prefix(PurchaseOrder::DASHBOARD_ROUTE_PREFIX)->name(PurchaseOrder::ROUTE_NAME_PREFIX)->group(function () {
      Route::get('', [self::class, 'index'])->name('list');
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

    (new PurchaseOrderService)->setProductType($request->fz_product_type_id)
      ->setPriceBatch($request->fz_price_batch_id)
      ->setPurchasedQuantity($request->purchased_quantity)
      ->setSwapStatus($request->is_swap_purchase, $request->swap_quantity, $request->swap_product_type_id)
      ->setCustomer($request->fz_customer_id)
      ->setPaymentType($request->payment_type, $request->company_bank_account_id)
      ->setAmount($request->total_selling_price, $request->total_amount_paid)
      ->setSalesRep($request->user()->id)
      ->create();
    // $request->createPurchaseOrder();

    return redirect()->route('purchaseorders.list')->withFlash(['success' => 'Customer\'s Purchase Order created.']);
  }

  public function update(Request $request, $id)
  {
    //
  }

  public function destroy($id)
  {
    //
  }
}
