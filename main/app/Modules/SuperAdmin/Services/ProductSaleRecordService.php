<?php

namespace App\Modules\SuperAdmin\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Modules\SuperAdmin\Models\ShopItem;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\SuperAdmin\Models\ProductStatus;
use App\Modules\SalesRep\Notifications\ReversedSale;
use App\Modules\SuperAdmin\Events\AccessoryReturned;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use App\Modules\SuperAdmin\Events\SaleRecordReversed;
use App\Modules\SuperAdmin\Events\SoldItemSaleReversed;
use App\Modules\SalesRep\Notifications\ReversedSoldItemSale;
use App\Modules\SuperAdmin\Models\CompanyBankAccount;

class ProductSaleRecordService
{
  private $product_sale_record;
  private $reversal_reason;
  private $super_admin;
  private $product_to_reverse_sale;
  private $company_bank_account;

  public function setSaleRecord(ProductSaleRecord $product_sale_record)
  {
    $this->product_sale_record = $product_sale_record;
    return $this;
  }

  public function setReversalReason(string $reversal_reason)
  {
    $this->reversal_reason = $reversal_reason;
    return $this;
  }

  public function setNotificationAdmin(SuperAdmin $super_admin)
  {
    $this->super_admin = $super_admin;
    return $this;
  }

  public function setproductToReverseSale(string $shop_item_uuid)
  {
    $this->product_to_reverse_sale = ShopItem::findSoleItemByUuid($shop_item_uuid)->load('sale_record');
    return $this;
  }

  public function setRefundCompanyAccount(string $company_bank_id)
  {
    $this->company_bank_account = CompanyBankAccount::find($company_bank_id);
    return $this;
  }

  public function reverseSaleRecord()
  {
    DB::beginTransaction();

    $this->product_sale_record->products()->update(['product_status_id' => ProductStatus::inStockId(), 'sold_at' => null]);
    $this->product_sale_record->swap_deals()->update(['product_status_id' => ProductStatus::inStockId(), 'sold_at' => null]);

    $this->product_sale_record->sale_receipt()->delete();
    $this->product_sale_record->delete();

    event(new AccessoryReturned($this->product_sale_record->product_accessories->toArray()));
    event(new SaleRecordReversed($this->product_sale_record, $this->reversal_reason, $this->super_admin));

    $this->product_sale_record->online_rep->notify(new ReversedSale($this->product_sale_record, $this->reversal_reason));
    $this->product_sale_record->sales_rep->notify(new ReversedSale($this->product_sale_record, $this->reversal_reason));

    //Clear the dispatch request's sold_at field if this was from a dispatch request
    if ($this->product_sale_record->is_delivery_order) {
      $this->product_sale_record->dispatch_request->sold_at = null;
      $this->product_sale_record->dispatch_request->save();
    }

    if ($this->product_sale_record->swapped_in_devices->every->in_stock()) {
      $this->product_sale_record->swapped_in_devices()->forceDelete();
    }
    else{
      throw new Exception('This sale record has swap deals and one or more of them are no longer in stock');
    }

    $this->product_sale_record->products()->detach();
    $this->product_sale_record->swap_deals()->detach();
    $this->product_sale_record->product_accessories()->detach();
    // Clear all the bank transactions for this sale record
    $this->product_sale_record->bank_account_payments()->detach();

    DB::commit();
  }

  public function reversePurchasedItemSale()
  {
    //If the sale record has no other purchased items delete it and it's receipt so that we don't have sal records with things hanging
    if ($this->product_sale_record->purchased_items()->count() == 1) {
      return $this->reverseSaleRecord();
    }


    DB::beginTransaction();

    $this->product_to_reverse_sale->product_status_id = ProductStatus::inStockId();
    $this->product_to_reverse_sale->sold_at = null;

    // Reduce the sales records amounts by the selling price of product
    $this->product_sale_record->decrement('total_amount_paid', (double)$this->product_to_reverse_sale->sale_record->purchase_record->selling_price);
    $this->product_sale_record->decrement('total_selling_price', (double)$this->product_to_reverse_sale->sale_record->purchase_record->selling_price);
    $this->product_sale_record->decrement('total_cost_price', (double)$this->product_to_reverse_sale->cost_price);

    event(new SoldItemSaleReversed($this->product_sale_record, $this->product_to_reverse_sale, $this->reversal_reason, $this->super_admin));

    $this->product_sale_record->online_rep->notify(new ReversedSoldItemSale($this->product_sale_record, $this->product_to_reverse_sale, $this->reversal_reason));
    $this->product_sale_record->sales_rep->notify(new ReversedSoldItemSale($this->product_sale_record, $this->product_to_reverse_sale, $this->reversal_reason));

    if ($this->product_sale_record->is_confirmed()) {
      $this->product_sale_record->bank_account_payments()->attach($this->company_bank_account->id, ['amount' => $this->product_to_reverse_sale->sale_record->purchase_record->selling_price, 'is_refund' => true]);
    }

    // Remove the product from the sale record's purchased items
    $this->product_sale_record->removeShopItem($this->product_to_reverse_sale);

    $this->product_to_reverse_sale->save();
    $this->product_sale_record->save();

    DB::commit();
  }

}
