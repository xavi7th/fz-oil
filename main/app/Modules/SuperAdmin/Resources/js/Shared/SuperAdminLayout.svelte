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
  import { fade } from "svelte/transition";
  import { Portal } from 'svelte-teleport';
  import { modalRoot } from "@public-shared/stores";

  $: ({ routes } = $page.props);

  let isMounted = false;

  onMount(() => {
    isMounted = true;
  });
</script>

<style>
  .layout-w{
    min-height: 100vh;
  }
</style>

<svelte:head>
  <title>{$title ? `${$title} | FZ` : 'Welcome | FZ'}</title>
</svelte:head>

{#if isMounted}
  <div class="all-wrapper with-side-panel solid-bg-all" out:fade>
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
