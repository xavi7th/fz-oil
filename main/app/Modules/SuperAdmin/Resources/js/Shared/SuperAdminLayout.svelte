<script>
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
  <div in:fly={{ x: -300, duration: 700, delay: 400 }} out:fly={{ y: 30, duration: 400, delay: 0 }}>
     <slot />
  </div>

  <Portal bind:this={$modalRoot}></Portal>

{/if}

{#if isMounted}
  <script src="/js/user-dashboard-init.js"></script>
{/if}
