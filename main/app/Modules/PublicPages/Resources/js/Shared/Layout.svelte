<script>
  import { fade } from "svelte/transition";
  import { onMount } from "svelte";

  let isLoaded = false,
    pageLoaded = false;

  onMount(() => {
    isLoaded = true;
  });
</script>

<svelte:window on:load={() => {pageLoaded = true; }} />

<div  transition:fade>
  <slot />
</div>

{#if isLoaded}
  <script src="/js/public-init.js"></script>
{/if}
