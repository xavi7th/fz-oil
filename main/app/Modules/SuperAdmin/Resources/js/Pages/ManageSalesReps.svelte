
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';

  $title = "Manage SalesReps"
  let staffModals;

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
      <div class="value">12</div>
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
        <div class="table-responsive">
          <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
              <tr>
                <th>Name</th>
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
              <tr>
                <td>Tiger Nixon</td>
                <td>+2348000000000</td>
                <td>tiger@nixon.com</td>
                <td>4, Tiger way, nixon avenue, Earth.</td>
                <td>Flagged</td>
                <td class="row-actions remove-center">
                  <a href="#"><i class="icon-feather-eye" data-placement="top" data-toggle="tooltip" data-original-title="View Complete Data"></i></a>
                  <a href="#"><i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Data"></i></a>
                  <a class="danger" data-target="#deleteStaffModal" data-toggle="modal" href="#"><i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Staff"></i></a>
                </td>
              </tr>
              <tr>
                <td>Kelly Yaw</td>
                <td>+2348000000000</td>
                <td>Kelly@Yaw.com</td>
                <td>32, Social avenue, north district, Earth.</td>
                <td>Active</td>
                <td class="row-actions remove-center">
                  <a href="#" data-placement="top" data-toggle="tooltip" data-original-title="View Complete Data"><i class="icon-feather-eye"></i></a>
                  <a href="#" data-placement="top" data-toggle="tooltip" data-original-title="Edit Data"><i class="icon-feather-settings"></i></a>
                  <a class="danger" data-target="#deleteStaffModal" data-toggle="modal" href="#"><i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Staff"></i></a>
                </td>
              </tr>
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
              <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete Staff</span></a>
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
              <form>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Full Name</label>
                      <input class="form-control" placeholder="Enter full name..." type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Email Address</label>
                      <input class="form-control" placeholder="Enter email address..." type="email" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Username</label>
                      <input class="form-control" placeholder="Enter username..." type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Password</label>
                      <input class="form-control" placeholder="Enter password..." type="password" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Mobile Number</label>
                      <input class="form-control" placeholder="Enter mobile number..." type="number" required>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Gender</label>
                      <select class="form-control">
                        <option vlaue="Male">Male</option>
                        <option vlaue="Female">Female</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-8">
                    <div class="form-group">
                      <label for="">Residential Address</label>
                      <input class="form-control" placeholder="Enter residential address..." type="text" required>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="">Role</label>
                      <select class="form-control">
                        <option vlaue="Administrator">Administrator</option>
                        <option vlaue="Manager">Manager</option>
                        <option vlaue="Sales Rep">Sales Rep</option>
                      </select>
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
