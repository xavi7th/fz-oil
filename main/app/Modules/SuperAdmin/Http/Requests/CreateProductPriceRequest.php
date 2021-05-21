<?php

namespace App\Modules\SuperAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductPriceRequest extends FormRequest
{

  public function rules()
  {
    return [
      'product_batch_id' => $this->isMethod('PUT') ? 'exists:product_batches,id' : 'required|exists:product_batches,id',
      'product_color_id' => $this->isMethod('PUT') ? 'exists:product_colors,id' : 'required|exists:product_colors,id',
      'storage_size_id' => $this->isMethod('PUT') ? 'exists:storage_sizes,id' : 'required|exists:storage_sizes,id',
      'product_grade_id' => $this->isMethod('PUT') ? 'exists:product_grades,id' : 'required|exists:product_grades,id',
      'product_supplier_id' => $this->isMethod('PUT') ? 'exists:product_suppliers,id' : 'required|exists:product_suppliers,id',
      'product_brand_id' => $this->isMethod('PUT') ? 'exists:product_brands,id' : 'required|exists:product_brands,id',
      'product_model_id' => $this->isMethod('PUT') ? 'exists:product_models,id' : 'required|exists:product_models,id',
      'cost_price' => $this->isMethod('PUT') ? 'numeric' : 'required|numeric',
      'proposed_selling_price' => 'numeric|gte:cost_price'
    ];
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [
      'proposed_selling_price.gte' => 'The proposed selling price should be greater than or equal to the cost price'
    ];
  }
}
