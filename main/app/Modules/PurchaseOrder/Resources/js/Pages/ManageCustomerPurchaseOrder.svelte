
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { toCurrency } from '@public-shared/helpers';
  import { Inertia } from '@inertiajs/inertia';

  $title = "Purchase Order";

  $: console.log((( swapValue(details.swap_product_type_id, details.swap_quantity) || 0)  ));

  let customerOrderModals, details = {};

  export let
  customer = {},
  stock_types = [],
  price_batches = [],
  company_bank_accounts = [],
  purchase_orders = [],
  purchase_orders_count = [],
  can_create_purchase_order = [];

  let validPriceBatches = prodId => price_batches.filter(batch => batch.fz_product_type_id == prodId)

  let totalCostPrice = (priceBatchId, quantity) => {
    return ((price_batches.filter(batch => batch.id === priceBatchId)[0] || {}).cost_price * quantity) || 0
  }

  let swapValue = (prodTypeId, quantity) => {
    return ((stock_types.filter(type => type.id === prodTypeId)[0] || {}).swap_value * quantity) || 0
  }

  let totalSellingPrice = (priceBatchId, quantity) => {
    return ((price_batches.filter(batch => batch.id === priceBatchId)[0] || {}).selling_price * quantity) || 0
  }

  let createPurchaseOrder = ()=>{
    details.fz_customer_id = customer.id;
    details.total_cost_price = totalCostPrice(details.fz_price_batch_id, details.purchased_quantity);
    details.total_selling_price = totalSellingPrice(details.fz_price_batch_id, details.purchased_quantity);
    if (!details.total_amount_paid) {
      Toast.fire({html:'Details not completed', position:'top', icon: 'error'});
      return;
    }

    Inertia.post(route('purchaseorders.create',customer), details,{
      onSuccess:()=>{
        details = {}
      }
    });

  }

  $: {
    try {
      customerOrderModals.teleport_to($modalRoot)
    } catch (e) {}
  }
</script>

<div class="row pt-2 pb-2">

  <div class="col-6 col-sm-6 col-xxl-6">
    {#if can_create_purchase_order && stock_types.length}
    <div class="element-box">
      <form on:submit|preventDefault|stopPropagation="{createPurchaseOrder}">
        <h5 class="form-header">New Purchase Order for Tiger Nixon</h5>
        <!-- <div class="form-desc">Discharge best employed your phase each the of shine. Be
          met even reason consider logbook redesigns. Never a turned interfaces among
          asking</div> -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Stock Type</label>
                <select class="form-control" bind:value={details.fz_product_type_id}>
                  <option value={undefined}>Select Stock Type</option>
                  {#each stock_types as stock_type(stock_type.id)}
                  <option value={stock_type.id}>{stock_type.product_type}</option>
                  {/each}
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Batch/Price</label>
                <select class="form-control" bind:value={details.fz_price_batch_id}>
                  <option>Select Batch</option>
                  {#each validPriceBatches(details.fz_product_type_id) as price_batch (price_batch.id)}
                  <option value={price_batch.id}>{toCurrency(price_batch.selling_price)}</option>
                  {/each}
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">Quantity</label>
            <input class="form-control" type="text" placeholder="Enter purchase quantity" bind:value={details.purchased_quantity}>
          </div>


          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" bind:checked={details.is_swap_purchase}> Swap Order?
            </label>
          </div>

          {#if details.is_swap_purchase}
          <fieldset class="form-group">
            <legend><span>Swap Section</span></legend>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Swap Stock Type</label>
                  <select class="form-control" bind:value={details.swap_product_type_id}>
                    <option value={undefined}>Select Stock Type</option>
                    {#each stock_types as stock_type(stock_type.id)}
                    <option value={stock_type.id}>{stock_type.product_type}</option>
                    {/each}
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Swap Quantity</label>
                  <input class="form-control" type="text" placeholder="Enter swap quantity" bind:value={details.swap_quantity}>
                </div>
              </div>
            </div>
          </fieldset>
          {/if}

          <fieldset class="form-group">
            <legend><span>Total Order Cost</span></legend>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Payment Type</label>
                  <select class="form-control" bind:value={details.payment_type}>
                    <option value={undefined}>Select Payment Type</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="credit">Credit</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group" class:text-danger={(totalSellingPrice(details.fz_price_batch_id, details.purchased_quantity) - swapValue(details.swap_product_type_id, details.swap_quantity)) < 0}>
                  <label for="">Total</label>
                  <input class="form-control" disabled type="text" value="{( (totalSellingPrice(details.fz_price_batch_id, details.purchased_quantity) - swapValue(details.swap_product_type_id, details.swap_quantity) ) )}" class:text-danger={(totalSellingPrice(details.fz_price_batch_id, details.purchased_quantity) - swapValue(details.swap_product_type_id, details.swap_quantity)) < 0}>
                </div>
              </div>
            </div>
            <div class="row">
              {#if details.payment_type == 'bank'}
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Select Bank</label>
                    <select class="form-control" bind:value={details.company_bank_account_id}>
                      <option value={undefined}>Select Bank</option>
                      {#each company_bank_accounts as bank(bank.id)}
                      <option value={bank.id}>{bank.bank_name}</option>
                      {/each}
                    </select>
                  </div>
                </div>
              {/if}
              <div class:col-sm-6={details.payment_type == 'bank'} class:col-sm-12={details.payment_type !== 'bank'}>
                <div class="form-group">
                  <label for="">Total Amount Paid</label>
                  <input class="form-control" type="text" bind:value={details.total_amount_paid}>
                </div>
              </div>
            </div>
          </fieldset>
          <div class="form-buttons-w">
            <button class="btn btn-primary" type="submit">Order</button>
          </div>
        </form>
      </div>
      {/if}
    </div>
    <div class="col-6 col-sm-6 col-xxl-6">
      <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
        <div class="label">Total Orders</div>
        <div class="value">{purchase_orders_count}</div>
      </a>
    </div>
  </div>

  <div class="row mt-5">
    <div class="col-sm-12">
      <div class="element-wrapper">
        <h6 class="element-header">Tiger Nixon Purchase Order History</h6>
        <div class="element-box">
          <div class="table-responsive">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
              <thead>
                <tr>
                  <th>S/N</th>
                    <th>Buyer</th>
                  <th>Stock Type</th>
                  <th>Qty</th>
                  <th>Swap / Qty / Value</th>
                  <th>Payment Type</th>
                  <th>Paid/Selling Price</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>S/N</th>
                  <th>Buyer</th>
                  <th>Stock Type</th>
                  <th>Qty</th>
                  <th>Swap / Qty / Value</th>
                  <th>Payment Type</th>
                  <th>Paid/Selling Price</th>
                  <th>Date</th>
                </tr>
              </tfoot>
              <tbody>
                {#each purchase_orders as order}
                <tr>
                  <td>{order.id}</td>
                  <td class="text-capitalize">{order.buyer.full_name}</td>
                  <td class="text-capitalize">{order.product_type.product_type}</td>
                  <td>{order.purchased_quantity}</td>
                  <td class="text-capitalize">{order.swap_product_type?.product_type || 'N/A'} / {order.swap_quantity || 'N/A'} / {toCurrency(order.swap_value * order.swap_quantity)}</td>
                  <td class="text-capitalize"> {order.payment_type == 'bank' ? `${order.bank?.bank_name} bank` : order.payment_type}</td>
                  <td><span class="text-success">{toCurrency(order.total_amount_paid)}</span> / <span class="text-orange">{toCurrency(order.total_selling_price)}</span></td>
                  <td>{new Date(order.created_at).toLocaleDateString()} {new Date(order.created_at).toLocaleTimeString()}</td>
              </tr>
              {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<Portal bind:this={customerOrderModals} >
  <div class="onboarding-modal modal fade animated" id="deleteOrderModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close"></span></button>
        <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">Delete Order?</h4>
          <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
          <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete Order</span></a>
        </div>
      </div>
    </div>
  </div>
</Portal>
