<?php

namespace App\Modules\PurchaseOrder\Http\Requests;

use App\Modules\FzStockManagement\Models\FzProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class CreatePurchaseOrderRequest extends FormRequest
{
  private $stock_exists = false;

  public function rules()
  {
    return [
      'fz_customer_id' => ['required', 'exists:fz_customers,id', function ($attribute, $value, $fail) {
        $this->customer->is_flagged ? $fail('This customer\'s account is currently flagged because ' . $this->customer->flag_message . '. Contact your supervisor') : null;
      }, function ($attribute, $value, $fail) {
        DB::table('fz_customers')->where('id', $value)->first()->is_active ? null : $fail('This customer\'s account is currently suspended and cannot make purchases. Contact your supervisor');
      }],
      'fz_product_type_id' => ['required', 'exists:fz_product_types,id', function ($attribute, $value, $fail) {
        $this->stock_exists ? null : $fail('Not enough products in stock to process this order');
      }],
      'fz_price_batch_id' => ['required', 'exists:fz_price_batches,id', function ($attribute, $value, $fail) {
        $this->stock_exists && (DB::table('fz_price_batches')->where('id', $value)->first()->fz_product_type_id == $this->fz_product_type_id ? null : $fail('The selected price does not belong to ' . DB::table('fz_product_types')->where('id', $this->fz_product_type_id)->first()->product_type));
      }],
      'purchased_quantity' => ['required', 'numeric',   function ($attribute, $value, $fail) {
        $this->stock_exists &&( DB::table('fz_stock')->where('fz_product_type_id', $this->fz_product_type_id)->where('fz_price_batch_id', $this->fz_price_batch_id)->first()->stock_quantity >= $value ? null : $fail('Not enough products in stock to process this order'));
      }, function ($attribute, $value, $fail) {
        $this->stock_exists && (DB::table('fz_stock')->where('fz_product_type_id', FzProductType::gallonId())->first()->stock_quantity >= $value ? null : $fail('Not enough gallons in stock to process this order'));
      }],
      'is_swap_purchase' => ['nullable', 'boolean'],
      'swap_product_type_id' => ['exclude_unless:is_swap_purchase,true', 'required', 'exists:fz_product_types,id',   function ($attribute, $value, $fail) {
        DB::table('fz_product_types')->where('id', $value)->first()->swap_value ? null : $fail('This item is not swapable');
      }],
      'swap_quantity' => ['exclude_unless:is_swap_purchase,true', 'required', 'numeric'],
      'payment_type' => ['required', 'in:cash,bank,credit'],
      'total_selling_price' => ['required', 'numeric'],
      'total_amount_paid' => ['required', 'numeric', function ($attribute, $value, $fail) {
        $this->payment_type == 'credit' && $value > ($this->customer->credit_balance) ? $fail('This customer\'s credit balance is not up to ' . $value) : null;
      }],
      'company_bank_account_id' => ['exclude_unless:payment_type,bank','required', 'exists:company_bank_accounts,id', function ($attribute, $value, $fail) {
        DB::table('company_bank_accounts')->where('id', $value)->first()->is_active ? null : $fail('This bank account has been suspended from use');
      }],
    ];
  }

  public function messages()
  {
    return [
      'company_bank_account_id.required' => 'A bank account must be selected for bank transactions'
    ];
  }

  public function validated()
  {
    return array_merge(parent::validated(), ['sales_rep_id' => $this->user()->id]);
  }

  public function authorize()
  {
    $this->stock_exists = DB::table('fz_stock')->where('fz_product_type_id', $this->fz_product_type_id)->exists();
    return true;
  }
}
