
<script context="module">
  import Layout, {title} from "@fzstaff-shared/UserLayout.svelte";
  export const layout = Layout
</script>

<script>
  import { Inertia } from '@inertiajs/inertia';

  $title = "Login Page";

  let details = {
    remember: true
  },
  formSubmitted = false, capsOn = false;

  let detectCaps = (e) =>{
    if (e.getModifierState("CapsLock")) {
      capsOn = true;
    } else {
      capsOn = false;
    }
  }

  let handleLogin = e => {
    if (e.target.checkValidity() === false) {
      e.stopPropagation();
      e.target.classList.add("was-validated");
      return;
    } else {
      formSubmitted = true;
      BlockToast.fire({ text: "Accessing your dashboard..." });

      Inertia.post(route("auth.login"), details,{
        onBefore: visit =>{
          e.target.classList.remove("was-validated");
        },
        onSuccess: page => {
          if (page.props.flash.action_required) {
            swal
            .fire({
              title: "One more thing!",
              text: `This seems to be your first login. You need to supply a password`,
              icon: "info"
            })
            .then(() => {
              swal
              .fire({
                title: "Enter a password",
                input: "text",
                inputAttributes: {
                  autocapitalize: "off",
                  autocomplete: false,
                  placeholder: "New password"
                },
                showCancelButton: true,
                confirmButtonText: "Set Password",
                showLoaderOnConfirm: true,
                preConfirm: pw => {
                  if (!pw) {
                    swal.showValidationMessage(
                    `You must provide a password for your account`
                    );
                    return false;
                  }
                  return Inertia.post(route("app.password.new"), {
                    pw,
                    email: details.email
                  })
                },
                allowOutsideClick: () => !swal.isLoading()
              })
              .then(result => {
                if (result.dismiss) {
                  swal.fire({
                    title: "Cancelled",
                    text: "You can´t login without setting a password",
                    icon: "info"
                  });
                }
              });
            });
          }
        }
      })
    }
  };
</script>

<div class="all-wrapper menu-side with-pattern">
  <div class="auth-box-w">
    <div class="logo-w"><a href="index.html"><img alt="" src="/img/logo-big.png"></a>
    </div>
    <h4 class="auth-header">Login Access</h4>
    <form on:submit|preventDefault={handleLogin}>
      <div class="form-group">
        <label for="">Username</label>
        <input class="form-control" placeholder="Enter your username" type="text" bind:value={details.user_name}>
        <div class="pre-icon os-icon os-icon-user-male-circle"></div>
      </div>
      <div class="form-group">
        <label for="">Password</label>
        <input class="form-control"placeholder="Enter your password" type="password" bind:value={details.password} on:keypress={detectCaps}>
        <div class="pre-icon os-icon os-icon-fingerprint"></div>
      </div>
      {#if capsOn}
      <span class="text-warning mt-10 d-block text-center">NOTE: Capslock is on</span>
      {/if}
      <div class="buttons-w">
        <button type="submit" class="btn btn-primary">Log me in</button>
      </div>
    </form>
  </div>
</div>
