<?php

namespace App\Modules\OfficeExpense\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\OfficeExpense\Models\OfficeExpense;

class CreateOfficeExpenseRequest extends FormRequest
{
  public function rules()
  {
    return [
      'amount' => ['required', 'numeric', function ($attribute, $value, $fail) {
        $this->payment_type == 'cash' && DB::table('purchase_orders')->where('payment_type', 'cash')->sum('total_amount_paid') > $value ? null : $fail('There is not enough cash in the office to fund this expense');
      }],
      'payment_type' => ['required', 'in:cash,transfer'],
      'description' => ['required', 'string'],
      'expense_date' => ['required', 'date'],
    ];
  }

  public function authorize()
  {
    return true;
  }


  public function validated()
  {
    return array_merge(parent::validated(), ['sales_rep_id' => $this->user()->id]);
  }


  public function createOfficeExpense()
  {
    return OfficeExpense::create($this->validated());
  }
}
