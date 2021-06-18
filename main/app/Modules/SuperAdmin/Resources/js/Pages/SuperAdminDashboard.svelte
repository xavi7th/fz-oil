
<script context="module">
  import Layout, {title} from "@superadmin-shared/SuperAdminLayout.svelte";
  export const layout = Layout
</script>


<script>
  import { toCurrency } from '@public-shared/helpers';

    $title = "Super Admin Dashboard";

    export let registered_customers_count = 0,
      registered_sales_rep_count = 0,
      registered_supervisors_count = 0,
      available_oil_stock_count = 0,
      available_gallon_stock_count = 0,
      price_batch_count = 0,
      total_daily_expenses = 0,
      total_purchase_orders_count = 0,
      total_daily_purchase_order_count = 0,
      total_daily_purchase_order_amount = 0,
      total_monthly_purchase_order_count = 0,
      total_monthly_purchase_order_amount = 0,
      total_daily_profit = 0,
      total_monthly_profit = 0;
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
              <div class="label">Total Staffs</div>
              <div class="value">{registered_sales_rep_count}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Supervisors</div>
              <div class="value">{registered_supervisors_count}</div>
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
              <div class="label">Total Expenses</div>
              <div class="value">{toCurrency(total_daily_expenses)}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Transactions</div>
              <div class="value">{total_purchase_orders_count}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Transactions</div>
              <div class="value">{total_daily_purchase_order_count}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Purchase</div>
              <div class="value">{toCurrency(total_daily_purchase_order_amount)}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Purchase</div>
              <div class="value">{total_monthly_purchase_order_count}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Purchase</div>
              <div class="value">{toCurrency(total_monthly_purchase_order_amount)}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Profit</div>
              <div class="value">{toCurrency(total_daily_profit)}</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">This Month's Profit</div>
              <div class="value">{toCurrency(total_monthly_profit)}</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
