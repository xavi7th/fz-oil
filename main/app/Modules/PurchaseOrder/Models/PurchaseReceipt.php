<?php

namespace App\Modules\PurchaseOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReceipt extends Model
{

  protected $fillable = [  'purchase_order_id','sales_rep_id','fz_product_type_id','product','cashier_name','payment_type','quantity','transaction_amount','amount_tendered','change_received','is_printed',];

}
