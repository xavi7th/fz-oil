<script>
  import { page } from "@inertiajs/inertia-svelte";
</script>

<div class="top-bar color-scheme-transparent">
  <div class="top-menu-controls">
    <div class="element-search autosuggest-search-activator">
      <input placeholder="" type="text" />
    </div>
  </div>
</div>
<ul class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">FZ Oil</a></li>
  <li class="breadcrumb-item"><span class="text-capitalize">{$page.props.auth.user.user_type} Dashboard</span></li>
</ul>
<div class="content-panel-toggler"><i class="os-icon os-icon-grid-squares-22"></i>
  <span>Sidebar</span>
</div>
