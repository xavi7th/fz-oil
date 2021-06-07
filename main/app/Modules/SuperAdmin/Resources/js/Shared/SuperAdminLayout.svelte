<script context="module">
  import { writable } from 'svelte/store'
  export const title = writable(null)
</script>

<script>
  import DashboardTopBar from './Partials/DashboardTopBar.svelte';

  import DesktopMenu from './Partials/DesktopMenu.svelte';

  import MobileMenu from './Partials/MobileMenu.svelte';

  import { onMount } from "svelte";
  import { page } from "@inertiajs/inertia-svelte";
  import { Inertia } from "@inertiajs/inertia";
  import { fly } from "svelte/transition";
  import { Portal } from 'svelte-teleport';
  import { modalRoot } from "@public-shared/stores";

  $: ({ routes, isInertiaRequest } = $page.props);

  if (!isInertiaRequest) {
    Inertia.reload({ only: ['routes'] })
  }

  let isMounted = false;

  onMount(() => {
    isMounted = true;
  });
</script>

<svelte:head>
  <title>{$title ? `${$title} | FZ` : 'Welcome | FZ'}</title>
</svelte:head>

{#if isMounted}
  <div class="all-wrapper with-side-panel solid-bg-all" >
    <div class="layout-w">
      <MobileMenu {routes}></MobileMenu>
     <DesktopMenu {routes}></DesktopMenu>
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
