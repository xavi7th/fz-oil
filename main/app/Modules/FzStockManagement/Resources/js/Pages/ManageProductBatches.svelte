
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { modalRoot } from "@public-shared/stores";
  import { Portal } from 'svelte-teleport';
  import { toCurrency } from '@public-shared/helpers';
  import { Inertia } from '@inertiajs/inertia';
import { onMount } from 'svelte';

  $title = "Manage Product Bacthes";

  $: createPriceBatch = details.set_new_price_batch || !price_batches.length;

  let productBatchModals, details = {};

  let validPriceBatches = prodId => price_batches.filter(batch => batch.fz_product_type_id == prodId)

  export let fz_stock = [],
  price_batches = [],
  stock_types = [],
  fz_stock_count = 0,
  fz_oil_stock_count = 0,
  fz_gallon_stock_count = 0,
  can_create_stock = false,
  can_edit_stock = false;

  $: {
    try {
      productBatchModals.teleport_to($modalRoot)
    } catch (e) {}
  }

  let createStock = () => {
    Inertia.post(route('fzstock.create'), details, {
      onSuccess: () => {
        jQuery('#addBatchModal').modal('hide');
        details = {}
      }
    })
  }

  let updateStock = () => {
    Inertia.put(route('fzstock.update', details), details, {
      onSuccess: () => {
        jQuery('#editBatchModal').modal('hide');
        details = {}
      }
    })
  }


  onMount(() => {
    if (!price_batches.length) {
      details.set_new_price_batch = true;
    }
  })

</script>

<div class="row pt-2 pb-2">
  <div class="col-12 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Total Stock Batches</div>
      <div class="value">{fz_stock_count}</div>
    </a>
  </div>
  <div class="col-12 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Total Oil Stock</div>
      <div class="value">{fz_oil_stock_count}</div>
    </a>
  </div>
  <div class="col-12 col-sm-3 col-xxl-3">
    <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
      <div class="label">Total Gallon Stock</div>
      <div class="value">{fz_gallon_stock_count}</div>
    </a>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Stock Batch List</h6>
      <div class="element-box">
        {#if can_create_stock}
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
        {/if}
        <div class="table-responsive">
          <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
            <thead>
              <tr>
                <th>Batch ID</th>
                <th>Stock Type</th>
                <th>Quantity</th>
                {#if can_create_stock}
                <th>Cost Price</th>
                {/if}
                <th>Retail Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Batch ID</th>
                <th>Stock Type</th>
                <th>Quantity</th>
                {#if can_create_stock}
                <th>Cost Price</th>
                {/if}
                <th>Retail Price</th>
                <th>Actions</th>
              </tr>
            </tfoot>
            <tbody>
              {#each fz_stock as item}
              <tr>
                <td>{item.fz_price_batch_id}</td>
                <td>{item.product_type.product_type}</td>
                <td>{item.stock_quantity}</td>
                <td>{toCurrency(item.price_batch.cost_price)}</td>
                {#if can_create_stock}
                <td>{toCurrency(item.price_batch.selling_price)}</td>
                {/if}
                <td class="row-actions remove-center">
                  {#if can_edit_stock}
                  <span on:click="{() => {details = item}}" data-target="#editBatchModal" data-toggle="modal" href="#">
                    <i class="icon-feather-settings" data-placement="top" data-toggle="tooltip" data-original-title="Edit Batch"></i>
                  </span>
                  {/if}
                  <!-- <a class="danger" data-target="#deleteBatchModal" data-toggle="modal" href="#">
                    <i class="icon-feather-trash-2" data-placement="top" data-toggle="tooltip" data-original-title="Delete Batch"></i>
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

<Portal bind:this={productBatchModals} >

    <!-- <div class="onboarding-modal modal fade animated" id="deleteBatchModal" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
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
    </div> -->

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
            <form on:submit|preventDefault|stopPropagation="{createStock}">
              <div class="row">
                <div class:col-sm-4={!createPriceBatch} class:col-sm-6={createPriceBatch}>
                  <div class="form-group">
                    <label for="">Stock Type</label>
                    <select class="form-control" bind:value={details.fz_product_type_id}>
                      <option value={undefined}>Select Stock Type</option>
                      {#each stock_types as stock_type(stock_type.id)}
                      <option value={stock_type.id}>{stock_type.product_type}</option>
                      {/each}
                    </select>
                  </div>
                </div>

                {#if !createPriceBatch}
                <div class:col-sm-4={!createPriceBatch} class:col-sm-6={createPriceBatch}>
                  <div class="form-group">
                    <label for="">Batch/Price</label>
                    <select class="form-control" bind:value={details.fz_price_batch_id}>
                      <option value={undefined}>Select Batch</option>
                      {#each validPriceBatches(details.fz_product_type_id) as price_batch (price_batch.id)}
                      <option value={price_batch.id}>{toCurrency(price_batch.selling_price)}</option>
                      {/each}
                    </select>
                  </div>
                </div>
                {/if}

                <div class:col-sm-4={!createPriceBatch} class:col-sm-6={createPriceBatch}>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <input class="form-control" placeholder="Enter quantity..." type="number" required bind:value={details.stock_quantity}>
                  </div>
                </div>

                {#if price_batches.length}
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="form-control">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" bind:checked={details.set_new_price_batch}> Existing prices don't match stock
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                {/if}
              </div>

              {#if createPriceBatch}
              <fieldset class="form-group">
                <legend><span>New Price Batch</span></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Cost Price</label>
                      <input class="form-control" placeholder="Enter retail..." type="text" required bind:value={details.cost_price}>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Selling Price</label>
                      <input class="form-control" placeholder="Enter discount..." type="text" required bind:value={details.selling_price}>
                    </div>
                  </div>
                </div>
              </fieldset>
              {/if}

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
            <div class="onboarding-text">Choose information to edit.</div>
            <form on:submit|preventDefault|stopPropagation={updateStock}>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="form-control">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" bind:checked={details.update_selling_price}> Update Selling Price
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="form-control">
                      <div class="form-check">
                        <label class="form-check-label">
                          <input class="form-check-input" type="checkbox" bind:checked={details.update_stock_quantity}> Update Stock Quantity
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                {#if details.update_selling_price}
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="">Selling Price</label>
                    <input class="form-control" placeholder="Enter new selling price" type="text" required bind:value={details.selling_price}>
                  </div>
                </div>
                {/if}
                {#if details.update_stock_quantity}
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <input class="form-control" placeholder="Enter quantity" type="text" required bind:value={details.stock_quantity}>
                  </div>
                </div>
                {/if}
              </div>
              {#if details.update_selling_price || details.update_stock_quantity}
                 <button class="btn btn-primary btn-md"><i class="icon-feather-edit"></i><span> Make Edit</span></button>
              {/if}
            </form>
          </div>
        </div>
      </div>
    </div>
  </Portal>
