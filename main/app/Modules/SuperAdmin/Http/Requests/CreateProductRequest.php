<?php

namespace App\Modules\SuperAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{

  public function rules()
  {
    if ($this->isMethod('PUT')) {
      return [
        'processor_speed_id' => 'required|exists:processor_speeds,id',
        'product_batch_id' => 'required|exists:product_batches,id',
        'product_category_id' => 'required|exists:product_categories,id',
        'product_color_id' => 'required|exists:product_colors,id',
        'storage_type_id' => 'required|exists:storage_types,id',
        'storage_size_id' => 'required|exists:storage_sizes,id',
        'ram_size_id' => 'required|exists:storage_sizes,id',
        'product_grade_id' => 'required|exists:product_grades,id',
        'product_supplier_id' => 'required|exists:product_suppliers,id',
        'product_brand_id' => 'required|exists:product_brands,id',
        'product_model_id' => 'required|exists:product_models,id',
        'imei' => 'required_without_all:serial_no,model_no|nullable|alpha_num|unique:products,imei,' . $this->imei . ',imei',
        'serial_no' => 'required_without_all:imei,model_no|nullable|alpha_num|unique:products,imei,' . $this->serial_no . ',serial_no',
        'model_no' => 'required_without_all:imei,serial_no|nullable|alpha_num|unique:products,imei,' . $this->model_no . ',model_no',
        'skip_qa' => 'boolean'
      ];
    } else {
      return [
        'processor_speed_id' => 'required|exists:processor_speeds,id',
        'product_batch_id' => 'required|exists:product_batches,id',
        'product_category_id' => 'required|exists:product_categories,id',
        'product_color_id' => 'required|exists:product_colors,id',
        'storage_type_id' => 'required_with:serial_no|exists:storage_types,id',
        'storage_size_id' => 'required_with:imei,model_no|exists:storage_sizes,id',
        'ram_size_id' => 'nullable|exists:storage_sizes,id',
        'product_grade_id' => 'required|exists:product_grades,id',
        'product_supplier_id' => 'required|exists:product_suppliers,id',
        'product_brand_id' => 'required|exists:product_brands,id',
        'product_model_id' => 'required|exists:product_models,id',
        'imei' => 'required_without_all:model_no,serial_no|alpha_num|unique:products,imei',
        'serial_no' => 'required_without_all:imei,model_no|alpha_dash|unique:products,serial_no',
        'model_no' => 'required_without_all:imei,serial_no|alpha_dash|unique:products,model_no',
        'skip_qa' => 'boolean'
    ];
   }
  }

  public function authorize()
  {
    return true;
  }

  public function messages()
  {
    return [
      'imei.required_without_all' => 'A product must have either an IMEI, a Serial Number or a Model Number',
      'serial_no.required_without_all' => 'A product must have either an IMEI, a Serial Number or a Model Number',
      'model_no.required_without_all' => 'A product must have either an IMEI, a Serial Number or a Model Number',
    ];
  }

}
