
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { inertia, InertiaLink } from '@inertiajs/inertia-svelte';
  import { Inertia } from '@inertiajs/inertia';
  import { toCurrency } from '@public-shared/helpers';

  $title = "Customers";

  let customerModals, details= {};

  export let fz_customers = 0,
  fz_customer_count = 0,
  fz_active_customer_count = 0,
  fz_suspended_customer_count = 0,
  // can_view_details = false,
  can_edit_user = false,
  can_create_customer = false,
  can_suspend_customer = false,
  can_activate_customer = false,
  can_set_credit_limit = false,
  can_view_purchase_orders = false,
  can_view_direct_swaps = false,
  can_view_credit_transactions = false;

  $: {
    try {
      customerModals.teleport_to($modalRoot)
    } catch (e) {}
  }

  let updateCreditLimit = () => {
    Inertia.put(route('fzcustomer.set_credit_limit', details), details, {
      onSuccess: () => {
        details = {};
        jQuery('#updateCustomerCreditLimitModal').modal('hide');
      }
    })
  }

  let createCustomerAccount = () => {
    Inertia.post(route('fzcustomer.create'), details, {
      onSuccess: () => {
        details = {};
        jQuery('#addCustomerModal').modal('hide');
      }
    })
  }

  let updateCustomerAccount = () => {
    Inertia.put(route('fzcustomer.update', details), details, {
      onSuccess: () => {
        details = {};
        jQuery('#editCustomerModal').modal('hide');
      }
    })
  }


</script>

<div class="row pt-2 pb-2">
  <div class="col-6 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Registered Customers</div>
      <div class="value">{fz_customer_count}</div>
    </a>
  </div>
  <div class="col-6 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Active Customers</div>
      <div class="value">{fz_active_customer_count}</div>
    </a>
  </div>
  <div class="col-6 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Suspended Customers</div>
      <div class="value">{fz_suspended_customer_count}</div>
    </a>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Customers</h6>
      <div class="element-box">
        {#if can_create_customer}
        <div class="controls-above-table pb-3">
          <div class="row">
            <div class="col-sm-6">
              <a class="btn btn-primary btn-md" data-target="#addCustomerModal" data-toggle="modal"href="#">
                <i class="icon-feather-user-plus"></i>
                <span> Add Customer</span>
              </a>
            </div>
          </div>
        </div>
        {/if}
        <div class="table-responsive">
          <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
              <tr>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Credit Limit</th>
                <th>Account Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>Credit Limit/Balance</th>
                <th>Account Status</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>

              {#each fz_customers as customer,idx(customer.id)}
              <tr>
                <td>{customer.full_name}</td>
                <td>{customer.phone}</td>
                <td>{customer.email}</td>
                <td>{customer.address}</td>
                <td>
                  <span class="text-info">{toCurrency(customer.credit_balance)} </span>/<span class="text-success">{toCurrency(customer.credit_limit)} </span>
                  {#if can_view_credit_transactions}
                  <a href="#">
                    <i class="icon-feather-file-text" data-placement="top" data-toggle="tooltip" data-original-title="View Details"></i>
                  </a>
                  {/if}
                </td>
                <td>
                  {#if can_suspend_customer && customer.is_active}
                  {#key customer.is_suspended}
                  <span use:inertia="{{ href: route('fzcustomer.suspend', customer), method: 'put', preserveState: true, preserveScroll:true }}" class="pointer text-orange">
                    <i class="icon-feather-shield-off" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"></i>
                  </span>
                  {/key}
                  {:else if can_activate_customer && !customer.is_active}
                  {#key customer.is_suspended}
                  <span  use:inertia="{{ href: route('fzcustomer.activate', customer), method: 'put', preserveState: true, preserveScroll:true }}" class="pointer text-success" href="#">
                    <i class="icon-feather-shield" data-placement="top" data-toggle="tooltip" data-original-title="Activate"></i>
                  </span>
                  {/key}
                  {/if}
                </td>
                <td class="row-actions remove-center">
                  {#if can_view_purchase_orders}
                  <InertiaLink href="{route('purchaseorders.create', customer)}" data-placement="top" data-toggle="tooltip" data-original-title="New Purchase Order">
                    <i class="icon-feather-credit-card"></i>
                  </InertiaLink>
                  {/if}
                  {#if can_view_credit_transactions}
                  <InertiaLink href="{route('fzcustomer.credit_transactions.list',customer)}" data-placement="top" data-toggle="tooltip" data-original-title="Credit Repayment">
                    <i class="icon-feather-repeat"></i>
                  </InertiaLink>
                  {/if}

                  {#if can_view_direct_swaps}
                    <InertiaLink href="{route('purchaseorders.directswaptransactions.create',customer)}" data-placement="top" data-toggle="tooltip" data-original-title="View Direct Swaps">
                      <i class="text-orange icon-feather-eye"></i>
                    </InertiaLink>
                  {/if}

                  {#if can_edit_user}
                    <span class="pointer text-orange" on:click="{() => {details = customer}}" data-target="#editCustomerModal" data-toggle="modal" href="#">
                      <i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Data"></i>
                    </span>
                  {/if}
                  {#if can_set_credit_limit}
                    <span class="pointer text-info" on:click="{() => {details = customer}}" data-target="#updateCustomerCreditLimitModal" data-toggle="modal" href="#">
                      <i class="icon-feather-edit" data-placement="top" data-toggle="tooltip" data-original-title="Set credit Limit"></i>
                    </span>
                  {/if}
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

<Portal bind:this={customerModals} >
  {#if can_set_credit_limit}
    <div class="onboarding-modal modal fade animated" id="updateCustomerCreditLimitModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-centered" role="document">
        <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close"></span></button>
          <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
          <div class="onboarding-content with-gradient">
            <h4 class="onboarding-title">Set Credit Limit?</h4>
            <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <input class="form-control" placeholder="Enter credit limit" type="text" required bind:value={details.credit_limit}>
              </div>
            </div>
          </div>
            <button class="btn btn-danger btn-md" on:click="{updateCreditLimit}"><i class="icon-feather-trash-2"></i><span> Update Limit</span></button>
          </div>
        </div>
      </div>
    </div>
  {/if}
  <div class="onboarding-modal modal fade animated" id="addCustomerModal" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center">
        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
          <span class="close-label">Close</span>
          <span class="os-icon os-icon-close"></span>
        </button>
        <div class="onboarding-media"><img alt="" src="/img/bigicon6.png" width="200px"></div>
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">Add Customer</h4>
          <div class="onboarding-text">Please provide all informations.</div>
          <form on:submit|preventDefault|stopPropagation="{createCustomerAccount}">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Full Name</label>
                  <input class="form-control" placeholder="Enter full name..." type="text" required bind:value={details.full_name}>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Email Address</label>
                  <input class="form-control" placeholder="Enter email address..." type="email" required bind:value={details.email}>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Mobile Number</label>
                  <input class="form-control" placeholder="Enter mobile number..." type="number" required bind:value={details.phone}>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group"><label for="">Gender</label>
                  <select class="form-control" bind:value={details.gender}>
                    <option vlaue="Male">Male</option>
                    <option vlaue="Female">Female</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Residential Address</label>
                  <input class="form-control" placeholder="Enter residential address..." type="text" required bind:value={details.address}>
                </div>
              </div>
            </div>
            <button class="btn btn-primary btn-md"><i class="icon-feather-plus-circle"></i><span> Add Customer</span></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="onboarding-modal modal fade animated" id="editCustomerModal" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center">
        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
          <span class="close-label">Close</span>
          <span class="os-icon os-icon-close"></span>
        </button>
        <div class="onboarding-media"><img alt="" src="img/bigicon6.png" width="200px"></div>
        <div class="onboarding-content with-gradient">
          <h4 class="onboarding-title">Edit Customer</h4>
          <div class="onboarding-text">Please provide all informations.</div>
          <form on:submit|preventDefault|stopPropagation="{updateCustomerAccount}">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Full Name</label>
                  <input class="form-control" placeholder="Enter full name..." type="text" required bind:value={details.full_name}>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Email Address</label>
                  <input class="form-control" placeholder="Enter email address..." type="email" required bind:value={details.email}>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Mobile Number</label>
                  <input class="form-control" placeholder="Enter mobile number..." type="number" required bind:value={details.phone}>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group"><label for="">Gender</label>
                  <select class="form-control" bind:value={details.gender}>
                    <option vlaue="Male">Male</option>
                    <option vlaue="Female">Female</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="">Residential Address</label>
                  <input class="form-control" placeholder="Enter residential address..." type="text" required bind:value={details.address}>
                </div>
              </div>
            </div>
            <button class="btn btn-primary btn-md"><i class="icon-feather-plus-circle"></i><span> Update Customer</span></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</Portal>
