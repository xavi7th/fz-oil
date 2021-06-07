
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';

  $title = "Manage Product Bacthes";

  let productBatchModals;

  $: {
    try {
      productBatchModals.teleport_to($modalRoot)
    } catch (e) {}
  }


</script>

<div class="row pt-2 pb-2">
    <div class="col-6 col-sm-3 col-xxl-3">
        <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Total Batches</div>
            <div class="value">45</div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="element-wrapper">
            <h6 class="element-header">Stock Batch List</h6>
            <div class="element-box">
                <div class="controls-above-table pb-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <a class="btn btn-primary btn-md" data-target="#addBatchModal" data-toggle="modal" href="#">
                                <i class="icon-feather-plus"></i>
                                <span> Add New Batch</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
                        <thead>
                            <tr>
                                <th>Batch ID</th>
                                <th>Stock Type</th>
                                <th>Quantity</th>
                                <th>Retail Price</th>
                                <th>Discount Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Batch ID</th>
                                <th>Stock Type</th>
                                <th>Quantity</th>
                                <th>Retail Price</th>
                                <th>Discount Price</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>FZ0000001</td>
                                <td>Oil</td>
                                <td>450</td>
                                <td>₦10,000</td>
                                <td>₦500</td>
                                <td class="row-actions remove-center">
                                    <a data-target="#editBatchModal" data-toggle="modal" href="#">
                                        <i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Batch"></i>
                                    </a>
                                    <a class="danger" data-target="#deleteBatchModal" data-toggle="modal" href="#">
                                        <i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Batch"></i>
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

<Portal bind:this={productBatchModals} >
  <div class="onboarding-modal modal fade animated" id="deleteBatchModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-centered" role="document">
          <div class="modal-content text-center"><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="close-label">Close</span><span class="os-icon os-icon-close"></span></button>
              <div class="onboarding-media"><img alt="" src="/img/bigicon5.png" width="200px"></div>
              <div class="onboarding-content with-gradient">
                  <h4 class="onboarding-title">Delete Batch?</h4>
                  <div class="onboarding-text"><strong>This action cannot be reversed</strong></div>
                  <a class="btn btn-danger btn-md" href="#"><i class="icon-feather-trash-2"></i><span> Delete Batch</span></a>
              </div>
          </div>
      </div>
  </div>
  <div class="onboarding-modal modal fade animated" id="addBatchModal" role="dialog" tabindex="-1" style="display: none;">
      <div class="modal-dialog modal-centered" role="document">
          <div class="modal-content text-center">
              <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                  <span class="close-label">Close</span>
                  <span class="os-icon os-icon-close"></span>
              </button>
              <div class="onboarding-media"><img alt="" src="/img/bigicon6.png" width="200px"></div>
              <div class="onboarding-content with-gradient">
                  <h4 class="onboarding-title">Add Batch</h4>
                  <div class="onboarding-text">Please provide all informations.</div>
                  <form>
                      <div class="row">
                          <div class="col-sm-4">
                              <div class="form-group">
                                  <label for="">Batch Name</label>
                                  <input class="form-control" placeholder="Enter batch name..." type="text" required>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                  <label for="">Stock Type</label>
                                  <select class="form-control">
                                      <option vlaue="Oil">Oil</option>
                                      <option vlaue="Gallons">Gallons</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                                  <label for="">Quantity</label>
                                  <input class="form-control" placeholder="Enter quantity..." type="number" required>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Retail Price</label>
                                  <input class="form-control" placeholder="Enter retail..." type="text" required>
                              </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Discount Price</label>
                                  <input class="form-control" placeholder="Enter discount..." type="text" required>
                              </div>
                          </div>
                      </div>
                      <button class="btn btn-primary btn-md"><i class="icon-feather-plus-circle"></i><span> Add Batch</span></button>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <div class="onboarding-modal modal fade animated" id="editBatchModal" role="dialog" tabindex="-1" style="display: none;">
      <div class="modal-dialog modal-centered" role="document">
          <div class="modal-content text-center">
              <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                  <span class="close-label">Close</span>
                  <span class="os-icon os-icon-close"></span>
              </button>
              <div class="onboarding-media"><img alt="" src="/img/bigicon6.png" width="200px"></div>
              <div class="onboarding-content with-gradient">
                  <h4 class="onboarding-title">Edit Batch</h4>
                  <div class="onboarding-text">Please provide all informations.</div>
                  <form>
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Stock Type</label>
                                  <select class="form-control">
                                      <option vlaue="Oil">Oil</option>
                                      <option vlaue="Gallons">Gallons</option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Quantity</label>
                                  <input class="form-control" placeholder="Enter quantity..." type="number" required>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Retail Price</label>
                                  <input class="form-control" placeholder="Enter retail..." type="text" required>
                              </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <label for="">Discount Price</label>
                                  <input class="form-control" placeholder="Enter discount..." type="text" required>
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
