<script context="module">
  import { writable } from 'svelte/store'
  export const title = writable(null)
</script>

<script>
  import { afterUpdate } from "svelte";

  export let title = app.name;

  let isLoaded = false;

  afterUpdate(() => {
    isLoaded = true;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<slot></slot>

{#if isLoaded}
  <script src="/js/user-dashboard-init.js">

  </script>
{/if}
