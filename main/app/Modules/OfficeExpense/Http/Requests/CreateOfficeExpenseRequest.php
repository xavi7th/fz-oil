<?php

namespace App\Modules\OfficeExpense\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\SuperAdmin\Models\SuperAdmin;

class CreateOfficeExpenseRequest extends FormRequest
{
  public function rules()
  {
    return [
      'amount' => ['required', 'numeric', function ($attribute, $value, $fail) {
        if ($this->payment_type == 'cash') {
          SuperAdmin::cash_in_office() > $value ? null : $fail('There is not enough cash in the office to fund this expense');
        }
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
