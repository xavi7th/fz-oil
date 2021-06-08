<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { toCurrency } from '@public-shared/helpers';
import { Inertia } from '@inertiajs/inertia';

  $title = "Manage Customer Credit";

  let customerCreditModal, details = {};

  export let credit_transactions_count = 0,
  can_create_credit_repayment = false,
  credit_transactions = [],
  company_bank_accounts = [],
  customer = [];

  $: {
    try {
      customerCreditModal.teleport_to($modalRoot)
    } catch (e) {}
  }

  let createRepaymentTransaction = () => {
    Inertia.post(route('fzcustomer.credit_transactions.repayment', customer), details, {
      onSuccess: () => details = {}
    })
  }

</script>

<div class="row pt-2 pb-2">
  {#if can_create_credit_repayment}
  <div class="col-6 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form on:submit|preventDefault|stopPropagation="{createRepaymentTransaction}">
        <h5 class="form-header">Credit Repayment</h5>
        <!-- <div class="form-desc">Discharge best employed your phase each the of shine. Be
        met even reason consider logbook redesigns. Never a turned interfaces among
        asking</div> -->

        <fieldset class="form-group">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Payment Type</label>
                <select class="form-control" bind:value={details.payment_type}>
                  <option>Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="bank">Bank Transfer</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Amount</label>
                <input class="form-control" type="text" bind:value={details.amount}>
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
            <div class:col-sm-6={details.payment_type == 'bank'} class:col-sm-12={details.payment_type != 'bank'}>
              <div class="form-group">
                <label for="">Date</label>
                <input class="form-control" type="date" bind:value={details.trans_date}>
              </div>
            </div>
          </div>

        </fieldset>
        <div class="form-buttons-w">
          <button class="btn btn-primary" type="submit">Make Payment</button>
        </div>
      </form>
    </div>
  </div>
  {/if}
  <div class="col-6 col-sm-6 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Current Debt</div>
      <div class="value">{toCurrency(customer.credit_limit - customer.credit_balance)}</div>
    </a>
  </div>
  <div class="col-6 col-sm-6 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Number of transactions</div>
      <div class="value">{credit_transactions_count}</div>
    </a>
  </div>
</div>
<div class="row mt-5">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Credit Repayment History</h6>
      <div class="element-box">
        <div class="table-responsive">
          <table id="dataTable1" width="100%"
          class="table table-striped table-lightfont">
          <thead>
            <tr>
              <th>S/N</th>
              <th>Transaction Type</th>
              <th>Payment Type</th>
              <th>Amount</th>
              <th>Recorder</th>
              <th>Date</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S/N</th>
              <th>Transaction Type</th>
              <th>Payment Type</th>
              <th>Amount</th>
              <th>Recorder</th>
              <th>Date</th>
            </tr>
          </tfoot>
          <tbody>
            {#each credit_transactions as trans}
            <tr class:text-danger={trans.trans_type != 'repayment'}>
              <td>{trans.id}</td>
              <td class="text-capitalize"> {trans.trans_type}</td>
              <td class="text-capitalize"> {trans.payment_type == 'bank' ? `${trans.bank?.bank_name} bank` : trans.payment_type}</td>
              <td>{toCurrency(trans.amount)}</td>
              <td>{trans.sales_rep?.full_name}</td>
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

<Portal bind:this={customerCreditModal} >
<!-- <div class="onboarding-modal modal fade animated" id="deleteOrderModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-centered" role="document">
    <div class="modal-content text-center">
      <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close"></span></button>
      <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
      <div class="onboarding-content with-gradient">
        <h4 class="onboarding-title">Delete Order?</h4>
        <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
        <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete Order</span></a>
      </div>
    </div>
  </div>
</div> -->
</Portal>
