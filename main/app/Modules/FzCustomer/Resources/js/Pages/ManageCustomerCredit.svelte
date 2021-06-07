<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';

  $title = "Manage Customer Credit";

  let customerCreditModal;

  $: {
    try {
      customerCreditModal.teleport_to($modalRoot)
    } catch (e) {}
  }


</script>

<div class="row pt-2 pb-2">
    <div class="col-6 col-sm-6 col-xxl-6">
        <div class="element-box">
            <form>
                <h5 class="form-header">Credit Repayment</h5>
                <!-- <div class="form-desc">Discharge best employed your phase each the of shine. Be
                    met even reason consider logbook redesigns. Never a turned interfaces among
                    asking</div> -->

                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Payment Type</label>
                                <select class="form-control">
                                    <option>Select Payment Type</option>
                                    <option>Cash</option>
                                    <option>Bank Transfer</option>
                                    <option>Swap</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Amount</label>
                                <input class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Select Bank</label>
                        <select class="form-control">
                            <option>Select Bank</option>
                            <option>GTB</option>
                            <option>FCMB</option>
                            <option>ACcess Bank</option>
                        </select>
                    </div>
                </fieldset>
                <div class="form-buttons-w">
                    <button class="btn btn-primary" type="submit">Make Payment</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-6 col-sm-6 col-xxl-6">
        <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Current Debt</div>
            <div class="value">₦23,000</div>
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
                                <th>Payment Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>S/N</th>
                                <th>Payment Type</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Bank Transfer / GTB</td>
                                <td>₦22500</td>
                                <td>05/03/2021</td>
                                <td class="row-actions remove-center">
                                    <a class="danger" data-target="#deleteOrderModal"
                                        data-toggle="modal" href="#">
                                        <i class="icon-feather-trash-2" data-placement="top"
                                            data-toggle="tooltip"
                                            data-original-title="Delete Order"></i>
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

<Portal bind:this={customerCreditModal} >
<div class="onboarding-modal modal fade animated" id="deleteOrderModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-centered" role="document">
      <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal"
              type="button"><span class="close-label">Close</span><span
                  class="os-icon os-icon-close"></span></button>
          <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
          <div class="onboarding-content with-gradient">
              <h4 class="onboarding-title">Delete Order?</h4>
              <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
              <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete
                      Order</span></a>
          </div>
      </div>
  </div>
</div>
</Portal>
