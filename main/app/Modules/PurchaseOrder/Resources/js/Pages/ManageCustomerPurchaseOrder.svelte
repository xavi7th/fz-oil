
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';

  $title = "Purchase Order";

  let customerOrderModals;

  $: {
    try {
      customerOrderModals.teleport_to($modalRoot)
    } catch (e) {}
  }
</script>

<div class="row pt-2 pb-2">
  <div class="col-6 col-sm-6 col-xxl-6">
    <div class="element-box">
      <form>
        <h5 class="form-header">New Purchase Order for Tiger Nixon</h5>
        <!-- <div class="form-desc">Discharge best employed your phase each the of shine. Be
          met even reason consider logbook redesigns. Never a turned interfaces among
          asking</div> -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Stock Type</label>
                <select class="form-control">
                  <option>Select Stock Type</option>
                  <option>Oil</option>
                  <option>Gallon</option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="">Batch/Price</label>
                <select class="form-control">
                  <option>Select Batch</option>
                  <option>FZ0000001 / ₦10,000</option>
                  <option>FZ0000002 / ₦12,000</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">Quantity</label>
            <input class="form-control" type="text" placeholder="Enter purchase quantity">
          </div>


          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox"> Swap Order
            </label>
          </div>

          <fieldset class="form-group">
            <legend><span>Swap Section</span></legend>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Swap Stock Type</label>
                  <select class="form-control">
                    <option>Select Stock Type</option>
                    <option>Gallon</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Swap Quantity</label>
                  <input class="form-control" type="text" placeholder="Enter swap quantity">
                </div>
              </div>
            </div>
          </fieldset>

          <fieldset class="form-group">
            <legend><span>Total Order Cost</span></legend>
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
                  <label for="">Total</label>
                  <input class="form-control" disabled type="text" value="₦23,000">
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
            <button class="btn btn-primary" type="submit">Order</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-xxl-6">
      <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
        <div class="label">Total Orders</div>
        <div class="value">284</div>
      </a>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-sm-12">
      <div class="element-wrapper">
        <h6 class="element-header">Tiger Nixon Purchase Order History</h6>
        <div class="element-box">
          <div class="table-responsive">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
              <thead>
                <tr>
                  <th>S/N</th>
                  <th>Stock Type</th>
                  <th>Qty</th>
                  <th>Swap / Qty / Value</th>
                  <th>Payment Type</th>
                  <th>Total</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>S/N</th>
                  <th>Stock Type</th>
                  <th>Qty</th>
                  <th>Swap / Qty / Value</th>
                  <th>Payment Type</th>
                  <th>Total</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </tfoot>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Oil</td>
                  <td>450</td>
                  <td>Gallon / 12 / ₦12,000</td>
                  <td>Swap</td>
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
              <tr>
                <td>2</td>
                <td>Oil</td>
                <td>450</td>
                <td>Gallon / 12 / ₦12,000</td>
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

<Portal bind:this={customerOrderModals} >
  <div class="onboarding-modal modal fade animated" id="deleteOrderModal" role="dialog" tabindex="-1"
  style="display: none;" aria-hidden="true">
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
