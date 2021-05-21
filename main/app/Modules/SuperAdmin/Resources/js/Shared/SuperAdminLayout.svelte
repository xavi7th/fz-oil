<script>
  import DashboardTopBar from './Partials/DashboardTopBar.svelte';

  import DesktopMenu from './Partials/DesktopMenu.svelte';

  import MobileMenu from './Partials/MobileMenu.svelte';

  import { onMount } from "svelte";
  import { page } from "@inertiajs/inertia-svelte";
  import { fly } from "svelte/transition";
  import { Portal } from 'svelte-teleport';
  import { modalRoot } from "@public-shared/stores";

  let isMounted = false;

  onMount(() => {
    isMounted = true;
  });
</script>

{#if isMounted}
  <div class="all-wrapper with-side-panel solid-bg-all" >
    <div class="layout-w">
      <MobileMenu></MobileMenu>
     <DesktopMenu></DesktopMenu>
      <div class="content-w">
        <DashboardTopBar></DashboardTopBar>


        <div class="content-i">
          <div class="content-box">
            <slot/>
          </div>
        </div>
      </div>
    </div>
    <div class="display-type"></div>
    <Portal bind:this={$modalRoot}></Portal>
  </div>

{/if}

{#if isMounted}
  <script src="/js/user-dashboard-init.js"></script>
{/if}
