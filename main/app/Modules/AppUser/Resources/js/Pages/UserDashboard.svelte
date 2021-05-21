<script>
    import Layout from "@usershared/UserLayout.svelte";
    import { page, InertiaLink } from "@inertiajs/inertia-svelte";
    import { Inertia } from "@inertiajs/inertia";
    import { onMount } from "svelte";


    $: ({ errors, auth } = $page.props);
    onMount(() => {});
</script>

<Layout title="Dashboard Home">page</Layout>
