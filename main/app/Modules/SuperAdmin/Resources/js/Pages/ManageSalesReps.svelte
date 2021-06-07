<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { inertia } from '@inertiajs/inertia-svelte';
  import { Inertia } from '@inertiajs/inertia';

  $title = "Manage SalesReps";

  export let sales_reps = [],
  staff_count = 0,
  can_delete = false,
  can_create = false,
  can_activate = false,
  can_suspend = false,
  can_edit = false;

  let staffModals, staffToDelete, details = {
    staff_role_id: 1
  }, files;

  let createSalesRep = () => {
    Inertia.post(route('salesrep.create'), details,{
      onSuccess: () => { jQuery('#addStaffModal').modal('hide'); details = { staff_role_id: 1 } }
    });
  }

  $: {
    try {
      staffModals.teleport_to($modalRoot)
    } catch (e) {}
  }
</script>

<div class="row pt-2 pb-2">
  <div class="col-6 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Registered Staffs</div>
      <div class="value">{staff_count}</div>
    </a>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Staffs</h6>
      <div class="element-box">
        <!-- <h5 class="form-header">Powerful Datatables</h5>
          <div class="form-desc">DataTables is a plug-in for the jQuery Javascript library. It is
            a highly flexible tool, based upon the foundations of progressive enhancement, and
            will add advanced interaction controls to any HTML table.</div> -->
            {#if can_create}
            <div class="controls-above-table pb-3">
              <div class="row">
                <div class="col-sm-6">
                  <a class="btn btn-primary btn-md" data-target="#addStaffModal" data-toggle="modal"href="#">
                    <i class="icon-feather-user-plus"></i>
                    <span> Add New Staff</span>
                  </a>
                </div>
              </div>
            </div>
            {/if}
            <div class="table-responsive">
              <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                <thead>
                  <tr>
                    <th>Full Name</th>
                    <th>User Name</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Address</th>
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
                    <th>Account Status</th>
                    <th>Actions</th>
                  </tr>
                </tfoot>
                <tbody>
                  {#each sales_reps as user (user.id)}
                  <tr>
                    <td>{user.full_name}</td>
                    <td>{user.user_name}</td>
                    <td>{user.phone}</td>
                    <td>{user.email}</td>
                    <td>{user.address}</td>
                    <td>
                      {#if user.is_flagged}
                      <div class="value badge badge-pill badge-warning">FLAGGED</div>
                      {/if}
                      {#if user.is_suspended}
                      <div class="value badge badge-pill badge-danger">Inactive</div>
                      {:else}
                      <div class="value badge badge-pill badge-success">Active</div>
                      {/if}
                    </td>
                    <td class="row-actions remove-center">
                      <a href="#"><i class="icon-feather-eye" data-placement="top" data-toggle="tooltip" data-original-title="View Complete Data"></i></a>
                      {#if can_edit}
                      <a href="#"><i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Data"></i></a>
                      {/if}
                      {#if can_suspend && !user.is_suspended}
                      {#key user.is_suspended}
                      <span use:inertia="{{ href: route('salesrep.suspend', user), method: 'put', preserveState: true, preserveScroll:true }}" class="pointer text-orange">
                        <i class="icon-feather-shield-off" data-placement="top" data-toggle="tooltip" data-original-title="Suspend"></i>
                      </span>
                      {/key}
                      {:else if can_activate && user.is_suspended}
                      {#key user.is_suspended}
                      <span  use:inertia="{{ href: route('salesrep.activate', user), method: 'put', preserveState: true, preserveScroll:true }}" class="pointer text-success" href="#">
                        <i class="icon-feather-shield" data-placement="top" data-toggle="tooltip" data-original-title="Activate"></i>
                      </span>
                      {/key}
                      {/if}
                      {#if can_delete}
                      <span on:click="{() => {staffToDelete = user}}" class="text-danger" data-target="#deleteStaffModal" data-toggle="modal" href="#"><i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Staff"></i></span>
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

    <Portal bind:this={staffModals} >
      <div class="onboarding-modal modal fade animated" id="deleteStaffModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-centered" role="document">
          <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close"></span></button>
            <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
            <div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Delete Staff?</h4>
              <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
              {#if staffToDelete}
              <span  use:inertia="{{ href: route('salesrep.delete', staffToDelete), method: 'delete', preserveState: false, preserveScroll:true, onSuccess:()=>{staffToDelete = null; jQuery('#deleteStaffModal').modal('hide'); } }}" class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete Staff</span></span>
              {/if}
            </div>
          </div>
        </div>
      </div>

      <div class="onboarding-modal modal fade animated" id="addStaffModal" role="dialog" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-centered" role="document">
          <div class="modal-content text-center">
            <button aria-label="Close" class="close" data-dismiss="modal" type="button">
              <span class="close-label">Close</span>
              <span class="os-icon os-icon-close"></span>
            </button>
            <div class="onboarding-media"><img alt="" src="/img/bigicon6.png" width="200px"></div>
            <div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Add Staff</h4>
              <div class="onboarding-text">Please provide all informations.</div>
              <form on:submit|preventDefault|stopPropagation="{createSalesRep}">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Full Name</label>
                      <input bind:value={details.full_name} class="form-control" placeholder="Enter full name..." type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Email Address</label>
                      <input bind:value={details.email} class="form-control" placeholder="Enter email address..." type="email" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Username</label>
                      <input bind:value={details.user_name} class="form-control" placeholder="Enter username..." type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Mobile Number</label>
                      <input bind:value={details.phone} class="form-control" placeholder="Enter mobile number..." type="text" required>
                    </div>
                  </div>
                </div>
                <div class="row">

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Gender</label>
                      <select bind:value={details.gender} class="form-control">
                        <option vlaue="male">Male</option>
                        <option vlaue="female">Female</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Profile Image</label>
                      <input class="form-control" type="file" bind:files on:change="{()=> {details.img = files[0]}}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="">Residential Address</label>
                      <input bind:value={details.address}  class="form-control" placeholder="Enter residential address..." type="text" required>
                    </div>
                  </div>
                </div>
                <button class="btn btn-primary btn-md"><i class="icon-feather-plus-circle"></i><span> Add Staff</span></button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </Portal>
