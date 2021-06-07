
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>

<script>
import { toCurrency } from '@public-shared/helpers';

  $title = "Supervisor Dashboard";
  export let registered_customers_count = 0,
  available_oil_stock_count = 0,
  available_gallon_stock_count = 0,
  price_batch_count = 0,
  total_daily_expenses = 0,
  sales_rep_daily_purchase_order_amount = 0,
  sales_rep_daily_purchase_order_count = 0,
  total_daily_purchase_order_count = 0;
</script>

<svelte:head>
  <script src="/js/html2pdf.js"></script>
</svelte:head>

<div class="row pt-2 pb-2">
  <div class="col-sm-12">
    <div class="element-wrapper">
      <h6 class="element-header">Dashboard</h6>
      <div class="row pt-2 pb-2">
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Registered Customers</div>
            <div class="value">{registered_customers_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Your Daily Sale Count</div>
            <div class="value">{sales_rep_daily_purchase_order_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Available Stock (Oil)</div>
            <div class="value">{available_oil_stock_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Available Stock (Gallons)</div>
            <div class="value">{available_gallon_stock_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Total Batches</div>
            <div class="value">{price_batch_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Total Transactions Today</div>
            <div class="value">{total_daily_purchase_order_count}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Today's Expense</div>
            <div class="value">{toCurrency(total_daily_expenses)}</div>
          </a>
        </div>
        <div class="col-6 col-sm-3 col-xxl-3">
          <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
            <div class="label">Your Sales Amount Today</div>
            <div class="value">{toCurrency(sales_rep_daily_purchase_order_amount)}</div>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
