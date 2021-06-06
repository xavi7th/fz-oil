<script>
  export let routes = [];
  $: console.log(routes);
</script>

<div class="menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link">

  <div class="logo-w">
    <a class="logo" href="index.html">
      <div class="logo-element"></div>
      <div class="logo-label">FZ Oil</div>
    </a>
  </div>
  <div class="logged-user-w avatar-inline">
    <div class="logged-user-i">
      <div class="avatar-w"><img alt="" src="img/avatar1.jpg"></div>
      <div class="logged-user-info-w">
        <div class="logged-user-name">FZ Oil</div>
        <div class="logged-user-role">Administrator</div>
      </div>
      <div class="logged-user-toggler-arrow">
        <div class="os-icon os-icon-chevron-down"></div>
      </div>
      <div class="logged-user-menu color-style-bright">
        <div class="logged-user-avatar-info">
          <div class="avatar-w"><img alt="" src="img/avatar1.jpg"></div>
          <div class="logged-user-info-w">
            <div class="logged-user-name">FZ Oil</div>
            <div class="logged-user-role">Administrator</div>
          </div>
        </div>
        <div class="bg-icon"><i class="os-icon os-icon-wallet-loaded"></i></div>
        <!-- <ul>
          <li><a href="apps_email.html"><i class="os-icon os-icon-mail-01"></i><span>Incoming Mail</span></a></li>
          <li><a href="users_profile_big.html"><i class="os-icon os-icon-user-male-circle2"></i><span>Profile Details</span></a></li>
          <li><a href="users_profile_small.html"><i class="os-icon os-icon-coins-4"></i><span>Billing Details</span></a></li>
          <li><a href="#"><i class="os-icon os-icon-others-43"></i><span>Notifications</span></a></li>
          <li><a href="#"><i class="os-icon os-icon-signs-11"></i><span>Logout</span></a></li>
        </ul> -->
      </div>
    </div>
  </div>
  <h1 class="menu-page-header">Page Header</h1>
  <ul class="main-menu">

    <li class="sub-header"><span>Menu</span></li>

    {#each Object.entries(routes) as [route_name, route_cont]}
      {#if route_cont.length == 1}
        <li class="selected">
          <a href="{route(route_cont[0].name)}">
            <div class="icon-w">
              <div class="os-icon os-icon-layout"></div>
            </div><span>{route_cont[0].menu_name}</span>
          </a>
        </li>
      {:else if route_cont.length > 1}
        <li class="selected has-sub-menu">
          <a href="#">
            <div class="icon-w">
              <div class="os-icon os-icon-layout"></div>
            </div><span>{route_name}</span>
          </a>
          <div class="sub-menu-w">
            <div class="sub-menu-header">{route_name}</div>
            <div class="sub-menu-icon"><i class="os-icon os-icon-layout"></i></div>
            <div class="sub-menu-i">
              <ul class="sub-menu">
                {#each route_cont as elem}
                   <li><a href="{route(elem.name)}">{elem.menu_name}</a></li>
                {/each}
              </ul>
            </div>
          </div>
        </li>
      {/if}
    {/each}

  </ul>
  <div class="side-menu-magic">
    <h4>FZ OIL</h4>
    <p>Sales Application</p>
  </div>
</div>
