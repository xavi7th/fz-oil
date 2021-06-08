
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from "svelte-teleport";
  import { toCurrency } from '@public-shared/helpers';
  import { Inertia } from '@inertiajs/inertia';

  $title = "TRADE IN (SWAP WITHOUT PURCHASE)";

  let tradeInModals, details = {};

  export let direct_swap_transactions = [],
  direct_swap_transactions_count = 0,
  fz_gallon_stock_count = 0,
  cash_in_office = 0,
  stock_types = [],
  company_bank_accounts = [],
  customer = {},
  can_create_direct_swap = false;

  $: {
    try {
      tradeInModals.teleport_to($modalRoot);
    } catch (e) {}
  }

  let createDirectTransaction = () => {
    Inertia.post(route('purchaseorders.directswaptransactions.create', customer), details, {
      onSuccess: () => details = {}
    })
  }
</script>

<div class="row pt-2 pb-2">
  {#if can_create_direct_swap}
  <div class="col-6 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form on:submit|preventDefault|stopPropagation="{createDirectTransaction}">
        <h5 class="form-header">Trade In (Swap without purchase)</h5>
        <fieldset class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Stock Type</label>
                <select class="form-control" bind:value={details.fz_product_type_id}>
                  <option value="{undefined}">Select Stock Type</option>
                  {#each stock_types as val}
                  <option value="{val.id}">{val.product_type}</option>
                  {/each}
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Quantity</label>
                <input class="form-control" type="text" placeholder="Enter swap quantity" bind:value={details.quantity}/>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Payment Type</label>
                <select class="form-control" bind:value={details.customer_paid_via}>
                  <option value="{undefined}">Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="bank">Bank Transfer</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Amount</label>
                <input class="form-control" type="number" bind:value={details.amount}/>
              </div>
            </div>
          </div>
          {#if details.customer_paid_via == 'bank'}
          <div class="col-sm-12">
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
        </fieldset>
        <div class="form-buttons-w">
          <button class="btn btn-primary" type="submit">Swap</button>
        </div>
      </form>
    </div>
  </div>
  {/if}
  <div class="col-md-6">
    <div class="row">
      <div class="col-6 col-sm-6 col-xxl-6">
        <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
          <div class="label">Total Trades</div>
          <div class="value">{direct_swap_transactions_count}</div>
        </a>
      </div>
      <div class="col-6 col-sm-6 col-xxl-6">
        <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
          <div class="label">Gallon Count</div>
          <div class="value">{fz_gallon_stock_count}</div>
        </a>
      </div>
      <div class="col-6 col-sm-6 col-xxl-6">
        <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
          <div class="label">Cash in Office</div>
          <div class="value">{toCurrency(cash_in_office)}</div>
        </a>
      </div>
    </div>
  </div>
</div>
<div class="row mt-5">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Trade In History</h6>
      <div class="element-box">
        <div class="table-responsive">
          <table
          id="dataTable1"
          width="100%"
          class="table table-striped table-lightfont"
          >
          <thead>
            <tr>
              <th>S/N</th>
              <th>Stock Type</th>
              <th>Qty</th>
              <th>Value</th>
              <th>Payment Type</th>
              <th>Date</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S/N</th>
              <th>Stock Type</th>
              <th>Qty</th>
              <th>Value</th>
              <th>Payment Type</th>
              <th>Date</th>
            </tr>
          </tfoot>
          <tbody>
            {#each direct_swap_transactions as trans}
            <tr>
              <td>{trans.id}</td>
              <td>{trans.fz_product_type.product_type}</td>
              <td>{trans.quantity}</td>
              <td>{toCurrency(trans.amount)}</td>
              <td>{trans.customer_paid_via}</td>
              <td>{new Date(trans.created_at).toLocaleDateString()} {new Date(trans.created_at).toLocaleTimeString()}</td>
            </tr>
            {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<Portal bind:this={tradeInModals}>
  <!-- <div class="onboarding-modal modal fade animated" id="deleteOrderModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center">
        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close" /></button>
        <div class="onboarding-media">
          <img alt="" src="/img/bigicon5.png" width="200px" />
        </div>
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">Delete Order?</h4>
          <div class="onboarding-text">
            <strong>This action cannot be reversed</strong>
          </div>
          <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2" /><span> Delete Order</span></a>
        </div>
      </div>
    </div>
  </div> -->
</Portal>
