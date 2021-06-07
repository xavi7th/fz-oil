
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';

  $title = "Manage Company Accounts";

  let bankAccountModals;

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
  <div class="col-6 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form>
        <h5 class="form-header">Add Bank Account</h5>
        <!-- <div class="form-desc">Discharge best employed your phase each the of shine. Be
          met even reason consider logbook redesigns. Never a turned interfaces among
          asking</div> -->
          <div class="form-group">
            <label for="">Bank Name</label>
            <select class="form-control">
              <option>Select Bank</option>
              <option>Access Bank</option>
              <option>Guaranty Trust Bank</option>
            </select>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Account Number</label>
                <input class="form-control" type="text" placeholder="Enter account number">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Account Name</label>
                <input class="form-control" type="text" placeholder="Enter account name">
              </div>
            </div>
          </div>
          <div class="form-buttons-w">
            <button class="btn btn-primary" type="submit">Order</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-xxl-6">
      <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
        <div class="label">Total Registered Bank Accounts</div>
        <div class="value">4</div>
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
              <tr>
                <td>1</td>
                <td>Guaranty Trust Bank</td>
                <td>909099099990</td>
                <td>FZ Oil</td>
                <td class="row-actions remove-center">
                  <a data-target="#editBankModal" data-toggle="modal" href="#">
                    <i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Bank Details"></i>
                  </a>
                  <a class="danger" data-target="#deleteBankModal"
                  data-toggle="modal" href="#">
                  <i class="icon-feather-trash-2" data-placement="top"
                  data-toggle="tooltip"
                  data-original-title="Delete Bank"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>


<Portal bind:this={bankAccountModals} >
  <div class="onboarding-modal modal fade animated" id="deleteBankModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
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
    </div>
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
            <form>
              <div class="form-group">
                <label for="">Bank Name</label>
                <select class="form-control">
                  <option>Select Bank</option>
                  <option>Access Bank</option>
                  <option>Guaranty Trust Bank</option>
                </select>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Account Number</label>
                    <input class="form-control" type="text" placeholder="Enter account number">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="">Account Name</label>
                    <input class="form-control" type="text" placeholder="Enter account name">
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
