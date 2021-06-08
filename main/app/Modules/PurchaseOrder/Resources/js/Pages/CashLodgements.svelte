
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from "svelte-teleport";
  import { Inertia } from '@inertiajs/inertia';
  import { toCurrency } from '@public-shared/helpers';

  $title = "Cash Lodgement";

  let tradeInModals, details={}, files;

  export let company_bank_accounts = [],
  cash_lodgements = [],
  cash_lodgements_count = 0,
  cash_lodgements_amount = 0,
  cash_in_office = 0,
  recorded_cash_in_office = 0,
  can_create_cash_lodgements = false;

  $: {
    try {
      tradeInModals.teleport_to($modalRoot);
    } catch (e) {}
  }

  let createCashLodgementRecord = () => {
    Inertia.post(route('purchaseorders.cashlodgement.create'), details, {
      onSuccess: ()=> details = {}
    })
  }
</script>
<div class="row pt-2 pb-2">
  {#if can_create_cash_lodgements}
  <div class="col-12 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form on:submit|preventDefault|stopPropagation="{createCashLodgementRecord}">
        <h5 class="form-header">Cash Lodgement</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Amount</label>
                <input class="form-control" type="number" placeholder="Enter amount" bind:value={details.amount}>
              </div>
            </div>
            <div class="col-md-6">
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
            <div class="col-md-6">
              <div class="form-group">
                <label for=""> Deposit Date</label>
                <div class="date-input">
                  <input class="single-daterange form-control" placeholder="04/12/1978" type="date" bind:value={details.lodgement_date}>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Upload Teller</label>
                <input class="form-control" type="file" bind:files on:change="{() => {details.teller = files[0]}}" accept="image/*">
              </div>
            </div>
          </div>


          <div class="form-buttons-w">
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
      </div>
    </div>
    {/if}
    <div class="col-12 col-sm-6 col-xxl-6">
      <div class="row">
        <div class="col-6 col-sm-6 col-xxl-6">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Total Cash in Office</div>
            <div class="value">{toCurrency(cash_in_office)}</div>
          </a>
        </div>
        <div class="col-6 col-sm-6 col-xxl-6">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Recorded Cash in Office</div>
            <div class="value">{toCurrency(recorded_cash_in_office)}</div>
          </a>
        </div>
        <div class="col-6 col-sm-6 col-xxl-6">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Lodgements Count</div>
            <div class="value">{cash_lodgements_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-6 col-xxl-6">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Lodgements Amount</div>
            <div class="value">{toCurrency(cash_lodgements_amount)}</div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-sm-12">
      <div class="element-wrapper">
        <h6 class="element-header">Cash Lodgement History</h6>
        <div class="element-box">
          <div class="table-responsive">
            <table id="dataTable1" width="100%"
            class="table table-striped table-lightfont">
            <thead>
              <tr>
                <th>S/N</th>
                <th>Deposited By</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Bank</th>
                <th>Teller</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>S/N</th>
                <th>Deposited By</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Bank</th>
                <th>Teller</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              {#each cash_lodgements as trans}
              <tr>
                <td>{trans.id}</td>
                <td>{trans.sales_rep?.full_name}</td>
                <td>{trans.amount}</td>
                <td>{new Date(trans.lodgement_date).toLocaleDateString()} {new Date(trans.lodgement_date).toLocaleTimeString()}</td>
                <td class="text-capitalize"> { `${trans.bank?.bank_name} bank`}</td>
                <td>
                  <a href="{trans.teller_url}" target="_blank">
                    Click to view Teller
                    <i class="icon-feather-file-text" data-placement="top" data-toggle="tooltip" data-original-title="View Teller"></i>
                  </a>
                </td>
                <!-- <td class="row-actions remove-center">
                  <a class="danger" data-target="#deleteModal"
                  data-toggle="modal" href="#">
                  <i class="icon-feather-trash-2" data-placement="top"
                  data-toggle="tooltip"
                  data-original-title="Delete"></i>
                </a>
              </td> -->
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
  <div
  class="onboarding-modal modal fade animated"
  id="deleteOrderModal"
  role="dialog"
  tabindex="-1"
  style="display: none;"
  aria-hidden="true"
  >
  <div class="modal-dialog modal-centered" role="document">
    <div class="modal-content text-center">
      <button
      aria-label="Close"
      class="close"
      data-dismiss="modal"
      type="button"
      ><span class="close-label">Close</span><span
      class="os-icon os-icon-close"
      /></button
      >
      <div class="onboarding-media">
        <img alt="" src="/img/bigicon5.png" width="200px" />
      </div>
      <div class="onboarding-content with-gradient">
        <h4 class="onboarding-title">Delete Order?</h4>
        <div class="onboarding-text">
          <strong>This action cannot be reversed</strong>
        </div>
        <a class="btn btn-danger btn-md" href="#"
        ><i class="icon-feather-trash-2" /><span> Delete Order</span></a
        >
      </div>
    </div>
  </div>
</div>
</Portal>
