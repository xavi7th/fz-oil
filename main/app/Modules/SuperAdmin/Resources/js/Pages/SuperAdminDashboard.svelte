<script>
  import Layout from "@superadmin-shared/SuperAdminLayout.svelte";
</script>

<svelte:head>
  <script src="/js/html2pdf.js"></script>
</svelte:head>

<Layout title="Super Admin Dashboard">
  <div class="row pt-2 pb-2">
    <div class="col-sm-12">
      <div class="element-wrapper">
        <h6 class="element-header">Dashboard</h6>
        <div class="row pt-2 pb-2">
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Registered Customers</div>
              <div class="value">512</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Staffs</div>
              <div class="value">12</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Available Stock (Oil)</div>
              <div class="value">3,412</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Available Stock (Gallons)</div>
              <div class="value">1,245</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Batches</div>
              <div class="value">234</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Total Transactions</div>
              <div class="value">16,234</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Expense</div>
              <div class="value">₦10,200.64</div>
            </a>
          </div>
          <div class="col-6 col-sm-3 col-xxl-3">
            <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
              <div class="label">Today's Profits</div>
              <div class="value">₦220,000.00</div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</Layout>
