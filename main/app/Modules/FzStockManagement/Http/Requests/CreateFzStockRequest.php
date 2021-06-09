<?php

namespace App\Modules\FzStockManagement\Http\Requests;

use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class CreateFzStockRequest extends FormRequest
{

  public function rules()
  {
    if ($this->isMethod('POST')) {
      return [
        'fz_product_type_id' => ['required', 'exists:fz_product_types,id'],
        'fz_price_batch_id' => ['exclude_if:set_new_price_batch,true','required', 'required_with:fz_product_type_id','exists:fz_price_batches,id',fn ($attribute, $value, $fail) =>  optional( DB::table('fz_price_batches')->where('id', $value)->first() )->fz_product_type_id == $this->fz_product_type_id ? null : $fail('The selected price does not belong to ' . DB::table('fz_product_types')->where('id', $this->fz_product_type_id)->first()->product_type)],
        'stock_quantity' => ['required', 'numeric'],
        'set_new_price_batch' => ['nullable', 'boolean'],
        'cost_price' => ['exclude_unless:set_new_price_batch,true','required', 'numeric'],
        'selling_price' => ['exclude_unless:set_new_price_batch,true','required', 'numeric'],
      ];
    }
    elseif ($this->isMethod('PUT')) {
      return [
        'update_selling_price' => ['nullable','boolean'],
        'update_stock_quantity' => ['nullable','boolean'],
        'selling_price' => ['exclude_unless:update_selling_price,true', 'required', 'numeric'],
        'stock_quantity' => ['exclude_unless:update_stock_quantity,true', 'required', 'numeric'],
      ];
    }
  }

  public function messages()
  {
    return [
      'fz_product_type_id.required' => 'Select a stock type',
      'fz_product_type_id.exists' => 'Invalid stock type selected',
      'fz_price_batch_id.required' => 'Select a price batch for this stock',
      'fz_price_batch_id.exists' => 'Invalid price batch selected',
    ];
  }

  public function authorize()
  {
    return true;
  }


  /**
   * Configure the validator instance.
   *
   * @param  \Illuminate\Validation\Validator  $validator
   * @return void
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if ($this->set_new_price_batch && FzPriceBatch::where('cost_price', $this->cost_price)->where('selling_price', $this->selling_price)->where('fz_product_type_id', $this->fz_product_type_id)->exists()) {
        $validator->errors()->add('set_new_price_batch', "A price batch with this cost and selling prrice already exists for this stock type. Add the items to that price batch instead");
        return;
      }
    });
  }


  public function createStock(): FzStock
  {

    if (!FzStock::gallon()->exists()) {
      $this->initializeGallonStock();
    }
    else{
      if (!FzProductType::find($this->fz_product_type_id)->isGallon()) {
        FzStock::gallon()->latest('id')->first()->incrementStock($this->stock_quantity);
      }
    }

    if ($this->set_new_price_batch) {
      $fz_price_batch_id = $this->createPriceBatch();

      return FzStock::create([
        'stock_quantity' => $this->stock_quantity,
        'fz_product_type_id' => $this->fz_product_type_id,
        'fz_price_batch_id' => $fz_price_batch_id,
      ]);

    }else{
      $fz_stock = FzStock::where('fz_product_type_id', $this->fz_product_type_id)->where('fz_price_batch_id', $this->fz_price_batch_id)->first();

      if ($fz_stock) {
        $fz_stock->incrementStock($this->stock_quantity);
        return $fz_stock;
      }
      else{
        return FzStock::create($this->validated());
      }
    }
  }

  public function updateStock(): bool
  {
    if ($this->update_stock_quantity) {
      FzStock::gallon()->latest('id')->first()->incrementStock($this->stock_quantity);
      return $this->fz_stock->incrementStock($this->stock_quantity);
    }
    elseif($this->update_selling_price){
      $this->fz_stock->price_batch->selling_price = $this->selling_price;
      return $this->fz_stock->price_batch->save();
    }
  }

  private function initializeGallonStock()
  {
    FzStock::create([
      'fz_product_type_id' => FzProductType::gallon()->first()->id,
      'fz_price_batch_id' => $this->initializeGallonPriceBatch(),
      'stock_quantity' => $this->stock_quantity
    ]);
  }

  private function createPriceBatch():int
  {
    return FzPriceBatch::create([
      'fz_product_type_id' => $this->fz_product_type_id,
      'cost_price' => $this->cost_price,
      'selling_price' => $this->selling_price,
    ])->id;
  }

  private function initializeGallonPriceBatch():int
  {
    return FzPriceBatch::create([
      'fz_product_type_id' => FzProductType::gallon()->first()->id,
      'cost_price' => 0,
      'selling_price' => 0,
    ])->id;
  }
}
