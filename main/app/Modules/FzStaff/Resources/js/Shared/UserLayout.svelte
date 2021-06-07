<script context="module">
  import { writable } from 'svelte/store'
  export const title = writable(null)
</script>

<script>
  import { onMount } from "svelte";

  let isMounted = false;

  onMount(() => {
    isMounted = true;
  });
</script>

<svelte:head>
  <title>{$title ? `${$title} | FZ` : 'Login | FZ'}</title>
</svelte:head>

<slot></slot>

{#if isMounted}
  <script src="/js/user-dashboard-init.js">

  </script>
{/if}
