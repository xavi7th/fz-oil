
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { toCurrency } from '@public-shared/helpers';
  import { Inertia } from '@inertiajs/inertia';

  $title = "Expense Management";

  let officeExpenseModals, details={};

  export let office_expenses = [],
  office_expenses_count = 0,
  office_expenses_amount = 0,
  can_create_expenses = false;

  $: {
    try {
      officeExpenseModals.teleport_to($modalRoot)
    } catch (e) {}
  }
  let createExpense  = () => {
    Inertia.post(route('officeexpense.create'), details, {
      onSuccess: () => details = {}
    })
  }

</script>

<svelte:head>
<script src="/js/html2pdf.js"></script>
</svelte:head>

<div class="row pt-2 pb-2">
  {#if can_create_expenses}
  <div class="col-6 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form on:submit|preventDefault|stopPropagation="{createExpense}">
        <h5 class="form-header">Expense Management</h5>

        <fieldset class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="">Expense Description</label>
                <textarea class="form-control" rows="3" bind:value={details.description}></textarea>
              </div>
            </div>
          </div>
        </fieldset>

        <fieldset class="form-group">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="">Payment Type</label>
                <select class="form-control" bind:value={details.payment_type}>
                  <option value={undefined}>Select Payment Type</option>
                  <option value="cash">Cash</option>
                  <option value="bank">Bank Transfer</option>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="">Amount</label>
                <input class="form-control" type="number" bind:value={details.amount}>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label for="">Expense Date</label>
                <input class="form-control" type="date" bind:value={details.expense_date}>
              </div>
            </div>
          </div>
        </fieldset>
        <div class="form-buttons-w">
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
  {/if}
  <div class="col-6 col-sm-6 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Total Expenses</div>
      <div class="value">{office_expenses_count}</div>
    </a>
  </div>
  <div class="col-6 col-sm-6 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Expenses Amount</div>
      <div class="value">{office_expenses_amount}</div>
    </a>
  </div>
</div>
<div class="row mt-5">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Expense Management History</h6>
      <div class="element-box">
        <div class="table-responsive">
          <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
              <tr>
                <th>S/N</th>
                <th>Description</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>S/N</th>
                <th>Description</th>
                <th>Payment Type</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </tfoot>
            <tbody>
              {#each office_expenses as expense}
              <tr>
                <td>{expense.id}</td>
                <td>{expense.description}</td>
                <td>{expense.payment_type}</td>
                <td>{toCurrency(expense.amount)}</td>
                <td>{new Date(expense.expense_date).toLocaleDateString()} {new Date(expense.expense_date).toLocaleTimeString()}</td>
              </tr>
              {/each}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<Portal bind:this={officeExpenseModals} >
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
