
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { Inertia } from '@inertiajs/inertia';

  $title = "Manage Company Accounts";

  let bankAccountModals, details = {}, bankToEdit={};

  export let company_bank_accounts = [],
  company_bank_accounts_count = 0,
  can_edit = false,
  can_create = true;

  let createAccount = () =>{
    Inertia.post(route('companybankaccount.create'), details, {
      onSuccess:() =>{
        details = {};
      }
    })
  }

  let updateAccountDetails = () =>{
    Inertia.put(route('companybankaccount.update', bankToEdit), bankToEdit, {
      onSuccess:() =>{
        bankToEdit = {};
        jQuery('#editBankModal').modal('hide')
      }
    })
  }

  $: {
    try {
      bankAccountModals.teleport_to($modalRoot)
    } catch (e) {}
  }

</script>

<svelte:head>
<script src="/js/html2pdf.js"></script>
</svelte:head>

<div class="row pt-2 pb-2">

  {#if can_create}
    <div class="col-6 col-sm-6 col-xxl-6">
      <div class="element-box">
        <form on:submit|preventDefault|stopPropagation="{createAccount}">
          <h5 class="form-header">Add Bank Account</h5>
          <div class="form-desc">Create an account that can be used to record office transactions. This makes it easy to track transactions for auditing purposes</div>
          <div class="form-group">
            <label for="">Bank Name</label>
            <select class="form-control" bind:value={details.bank_name}>
              <option>Access Bank</option>
              <option>Citibank</option>
              <option>Diamond Bank</option>
              <option>Ecobank</option>
              <option>Fidelity Bank</option>
              <option>First Bank</option>
              <option>First City Monument Bank (FCMB)</option>
              <option>Guaranty Trust Bank (GTB)</option>
              <option>Heritage Bank</option>
              <option>Keystone Bank</option>
              <option>Polaris Bank</option>
              <option>Providus Bank</option>
              <option>Stanbic IBTC Bank</option>
              <option>Standard Chartered Bank</option>
              <option>Sterling Bank</option>
              <option>Suntrust Bank</option>
              <option>Union Bank</option>
              <option>United Bank for Africa (UBA)</option>
              <option>Unity Bank</option>
              <option>Wema Bank</option>
              <option>Zenith Bank</option>
            </select>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Account Number</label>
                <input class="form-control" type="text" placeholder="Enter account number" bind:value={details.account_number}>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Account Name</label>
                <input class="form-control" type="text" placeholder="Enter account name" bind:value={details.account_name}>
              </div>
            </div>
          </div>
          <div class="form-buttons-w">
            <button class="btn btn-primary" type="submit">Order</button>
          </div>
        </form>
      </div>
    </div>
  {/if}
  <div class="col-6 col-sm-6 col-xxl-6">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Total Registered Bank Accounts</div>
      <div class="value">{company_bank_accounts_count}</div>
    </a>
  </div>
</div>

<div class="row mt-5">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <div class="element-box">
        <div class="table-responsive">
          <table id="dataTable1" width="100%"
          class="table table-striped table-lightfont">
          <thead>
            <tr>
              <th>S/N</th>
              <th>Bank Name</th>
              <th>Account Number</th>
              <th>Account Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S/N</th>
              <th>Bank Name</th>
              <th>Account Number</th>
              <th>Account Name</th>
              <th>Actions</th>
            </tr>
          </tfoot>
          <tbody>
            {#each company_bank_accounts as bank, idx(bank.id)}
            <tr>
              <td>{idx + 1}</td>
              <td>{bank.bank_name}</td>
              <td>{bank.account_number}</td>
              <td>{bank.account_name}</td>
              <td class="row-actions remove-center">
                {#if can_edit}
                <span on:click="{() => {bankToEdit = bank}}" class="text-orange" data-target="#editBankModal" data-toggle="modal" href="#">
                  <i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Bank Details"></i>
                </span>
                {/if}
                <!-- <a class="danger" data-target="#deleteBankModal" data-toggle="modal" href="#">
                  <i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Bank"></i>
                </a> -->
              </td>
            </tr>
            {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>


<Portal bind:this={bankAccountModals} >
  <!-- <div class="onboarding-modal modal fade animated" id="deleteBankModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal"
        type="button"><span class="close-label">Close</span><span
        class="os-icon os-icon-close"></span></button>
        <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">Delete Bank?</h4>
          <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
          <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete
            Bank</span></a>
          </div>
        </div>
      </div>
    </div> -->
    <div class="onboarding-modal modal fade animated" id="editBankModal" role="dialog" tabindex="-1" style="display: none;">
      <div class="modal-dialog modal-centered" role="document">
        <div class="modal-content text-center">
          <button aria-label="Close" class="close" data-dismiss="modal" type="button">
            <span class="close-label">Close</span>
            <span class="os-icon os-icon-close"></span>
          </button>
          <div class="onboarding-media"><img alt="" src="/img/bigicon6.png" width="200px"></div>
          <div class="onboarding-content with-gradient">
            <h4 class="onboarding-title">Edit Bank Details</h4>
            <div class="onboarding-text">Please provide all informations.</div>
            <form on:submit|preventDefault|stopPropagation="{updateAccountDetails}">
              <div class="form-group">
                <label for="">Bank Name</label>
                <select class="form-control" bind:value={bankToEdit.bank_name}>
                  <option selected={bankToEdit.bank_name == 'Access Bank'}>Access Bank</option>
                  <option selected={bankToEdit.bank_name == 'Citibank'}>Citibank</option>
                  <option selected={bankToEdit.bank_name == 'Diamond Bank'}>Diamond Bank</option>
                  <option selected={bankToEdit.bank_name == 'Ecobank'}>Ecobank</option>
                  <option selected={bankToEdit.bank_name == 'Fidelity Bank'}>Fidelity Bank</option>
                  <option selected={bankToEdit.bank_name == 'First Bank'}>First Bank</option>
                  <option selected={bankToEdit.bank_name == 'First City Monument Bank (FCMB)'}>First City Monument Bank (FCMB)</option>
                  <option selected={bankToEdit.bank_name == 'Guaranty Trust Bank (GTB)'}>Guaranty Trust Bank (GTB)</option>
                  <option selected={bankToEdit.bank_name == 'Heritage Bank'}>Heritage Bank</option>
                  <option selected={bankToEdit.bank_name == 'Keystone Bank'}>Keystone Bank</option>
                  <option selected={bankToEdit.bank_name == 'Polaris Bank'}>Polaris Bank</option>
                  <option selected={bankToEdit.bank_name == 'Providus Bank'}>Providus Bank</option>
                  <option selected={bankToEdit.bank_name == 'Stanbic IBTC Bank'}>Stanbic IBTC Bank</option>
                  <option selected={bankToEdit.bank_name == 'Standard Chartered Bank'}>Standard Chartered Bank</option>
                  <option selected={bankToEdit.bank_name == 'Sterling Bank'}>Sterling Bank</option>
                  <option selected={bankToEdit.bank_name == 'Suntrust Bank'}>Suntrust Bank</option>
                  <option selected={bankToEdit.bank_name == 'Union Bank'}>Union Bank</option>
                  <option selected={bankToEdit.bank_name == 'United Bank for Africa (UBA)'}>United Bank for Africa (UBA)</option>
                  <option selected={bankToEdit.bank_name == 'Unity Bank'}>Unity Bank</option>
                  <option selected={bankToEdit.bank_name == 'Wema Bank'}>Wema Bank</option>
                  <option selected={bankToEdit.bank_name == 'Zenith Bank'}>Zenith Bank</option>
                </select>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Account Number</label>
                    <input class="form-control" type="text" placeholder="Enter account number" bind:value={bankToEdit.account_number}>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Account Name</label>
                    <input class="form-control" type="text" placeholder="Enter account name" bind:value={bankToEdit.account_name}>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary btn-md"><i class="icon-feather-edit"></i><span> Make Edit</span></button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </Portal>
