<?php

namespace App\Modules\PurchaseOrder\Services;

use Exception;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzStockManagement\Models\FzProductType;

class PurchaseOrderService
{
  private $sales_rep_id;
  private $fz_product_type_id;
  private $fz_price_batch_id;
  private $purchased_quantity;
  private $is_swap_purchase;
  private $swap_quantity;
  private $swap_product_type_id;
  private $fz_customer_id;
  private $payment_type;
  private $bank_id;
  private $total_selling_price;
  private $total_amount_paid;
  private $fz_stock;
  private $swapped_in_fz_stock;

  public function setProductType($fz_product_type_id)
  {
    $this->fz_product_type_id = $fz_product_type_id;
    return $this;
  }

  public function setPriceBatch($fz_price_batch_id)
  {
    $this->fz_price_batch_id = $fz_price_batch_id;
    return $this;
  }

  public function setPurchasedQuantity($purchased_quantity)
  {
    $this->purchased_quantity = $purchased_quantity;
    return $this;
  }

  public function setSwapStatus($is_swap_purchase, $swap_quantity = null, $swap_product_type_id = null)
  {
    $this->is_swap_purchase = $is_swap_purchase;
    $this->swap_quantity = $swap_quantity;
    $this->swap_product_type_id = $swap_product_type_id;
    return $this;
  }
  public function setCustomer($fz_customer_id)
  {
    $this->fz_customer_id = $fz_customer_id;
    return $this;
  }
  public function setPaymentType($payment_type, $bank_id = null)
  {
    $this->payment_type = $payment_type;
    $this->bank_id = $bank_id;
    return $this;
  }

  public function setSalesRep($sales_rep_id)
  {
    $this->sales_rep_id = $sales_rep_id;
    return $this;
  }

  public function setAmount($total_selling_price, $total_amount_paid)
  {
    $this->total_amount_paid = $total_amount_paid;
    $this->total_selling_price = $total_selling_price;
    return $this;
  }

  public function create()
  {

    if (
      is_null($this->fz_product_type_id) || is_null($this->fz_price_batch_id) || is_null($this->purchased_quantity) || is_null($this->fz_customer_id)
      || is_null($this->payment_type) || is_null($this->bank_id) || is_null($this->total_selling_price) || is_null($this->total_amount_paid)
    ) {
      throw new Exception('Required parameters not set');
    }

    $this->fz_stock = FzStock::getStock($this->fz_product_type_id, $this->fz_price_batch_id);
    if (!$this->fz_stock) throw new Exception("Invalid stock selected");

    $this->fz_stock->decrementStock($this->purchased_quantity);
    FzStock::gallon()->first()->decrementStock($this->purchased_quantity);

    if ($this->payment_type == 'credit') {
      $this->fz_customer = FzCustomer::find($this->fz_customer_id);
      if (!$this->fz_customer) throw new Exception("Invalid customer selected");

      try {
        $this->fz_customer->deductCreditBalance($this->total_amount_paid);
      } catch (\Throwable $th) {
        throw new Exception('Insufficient Customer credit balance');
      }
    }


    if ($this->is_swap_purchase) {
      $purchase_order = $this->processSwap();
    } else {
      $purchase_order = $this->createPurchaseOrder();
    }
  }

  private function processSwap(): PurchaseOrder
  {
    $this->swapped_in_fz_stock = FzStock::getStock($this->swap_product_type_id);
    $fz_product_type = FzProductType::find($this->fz_product_type_id);

    if (!$this->swapped_in_fz_stock) throw new Exception("Invalid swap stock selected");

    $this->swapped_in_fz_stock->incrementStock($this->swap_quantity);

    return PurchaseOrder::create([
      'payment_type' => strtolower($this->payment_type),
      'fz_customer_id' => $this->fz_customer_id,
      'fz_product_type_id' => $this->fz_product_type_id,
      'fz_price_batch_id' => $this->fz_price_batch_id,
      'sales_rep_id' => $this->sales_rep_id,
      'purchased_quantity' => $this->purchased_quantity,
      'total_selling_price' => $this->total_selling_price,
      'is_swap_transaction' => $this->is_swap_purchase,
      'swap_product_type_id' => $this->swap_product_type_id,
      'swap_quantity' => $this->swap_quantity,
      'swap_value' => $fz_product_type->swap_value,
      'total_amount_paid' => $this->total_amount_paid,
    ]);
  }

  private function createPurchaseOrder(): PurchaseOrder
  {
    return  PurchaseOrder::create([
      'payment_type' => strtolower($this->payment_type),
      'fz_customer_id' => $this->fz_customer_id,
      'fz_product_type_id' => $this->fz_product_type_id,
      'fz_price_batch_id' => $this->fz_price_batch_id,
      'sales_rep_id' => $this->sales_rep_id,
      'purchased_quantity' => $this->purchased_quantity,
      'total_selling_price' => $this->total_selling_price,
      'total_amount_paid' => $this->total_amount_paid,
    ]);
  }
}
