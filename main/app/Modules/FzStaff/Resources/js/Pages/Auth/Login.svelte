<script>
import { Inertia } from '@inertiajs/inertia';
import Layout from "@usershared/UserLayout.svelte";

export let errors;

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

<Layout title="Login Page">
  <form
    class="form rui-sign-form rui-sign-form-cloud"
    on:submit|preventDefault={handleLogin}
    novalidate>
    <div class="row vertical-gap sm-gap justify-content-center">
      <div class="col-12">
        <h1 class="display-4 mb-10 text-center">Sign In</h1>
      </div>

      <div class="col-12">
        <input type="email" required class="form-control" class:is-invalid={errors.email} class:is-valid={formSubmitted && !errors.email} id="email" bind:value={details.email} placeholder="Email" />

      </div>
      <div class="col-12">
        <input type="password" required class="form-control" class:is-invalid={errors.password} id="password" bind:value={details.password} on:keypress={detectCaps} placeholder="Password" />
        {#if capsOn}
          <span class="text-warning mt-10 d-block text-center">NOTE: Capslock is on</span>
        {/if}
      </div>
      <div class="col-sm-6">
        <div
          class="custom-control custom-checkbox d-flex justify-content-start">
          <input
            type="checkbox"
            class="custom-control-input"
            id="rememberMe"
            bind:value={details.remember} />
          <label class="custom-control-label fs-13" for="rememberMe">
            Remember me
          </label>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="d-flex justify-content-end">
          <a href class="fs-13">Forgot password?</a>
        </div>
      </div>
      <div class="col-12">
        <button class="btn btn-brand btn-block text-center">Sign in</button>
      </div>
    </div>
  </form>
</Layout>
