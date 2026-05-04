(function () {
  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setupLoginForm(form) {
    const email = form.querySelector("#email");
    const password = form.querySelector("#password");
    const submitButton = form.querySelector('button[type="submit"]');
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    function validate() {
      let valid = true;
      email.classList.remove("is-invalid", "is-valid");
      password.classList.remove("is-invalid", "is-valid");
      emailError.textContent = "";
      passwordError.textContent = "";

      if (!email.value.trim()) {
        email.classList.add("is-invalid");
        emailError.textContent = "L'email est obligatoire.";
        valid = false;
      } else if (!isValidEmail(email.value.trim())) {
        email.classList.add("is-invalid");
        emailError.textContent = "Veuillez saisir une adresse email valide.";
        valid = false;
      } else {
        email.classList.add("is-valid");
      }

      if (!password.value) {
        password.classList.add("is-invalid");
        passwordError.textContent = "Le mot de passe est obligatoire.";
        valid = false;
      } else {
        password.classList.add("is-valid");
      }

      if (submitButton) {
        submitButton.disabled = !valid;
      }

      return valid;
    }

    email.addEventListener("input", validate);
    password.addEventListener("input", validate);
    form.addEventListener("submit", function (event) {
      if (!validate()) {
        event.preventDefault();
      }
    });
    validate();
  }

  function setupEmailCodeForm(form) {
    const code = form.querySelector("#code");
    const submitButton = form.querySelector('button[value="verify"]');
    const codeError = document.getElementById("codeError");
    const countdown = document.getElementById("emailVerificationCountdown");
    const resendButton = form.querySelector('button[value="resend"]');
    let expireSeconds = Number.parseInt(form.getAttribute("data-expire-seconds") || "0", 10);
    let resendSeconds = Number.parseInt(form.getAttribute("data-resend-seconds") || "0", 10);

    function validateCode() {
      let valid = true;
      code.classList.remove("is-invalid", "is-valid");
      codeError.textContent = "";

      if (!/^\d{6}$/.test(code.value.trim())) {
        code.classList.add("is-invalid");
        codeError.textContent = "Veuillez saisir un code a 6 chiffres.";
        valid = false;
      } else {
        code.classList.add("is-valid");
      }

      if (submitButton) {
        submitButton.disabled = !valid || expireSeconds <= 0;
      }

      return valid;
    }

    if (countdown && Number.isInteger(expireSeconds) && expireSeconds > 0) {
      const expireTimer = window.setInterval(function () {
        expireSeconds -= 1;
        countdown.textContent = String(Math.max(0, expireSeconds));
        validateCode();
        if (expireSeconds <= 0) {
          window.clearInterval(expireTimer);
        }
      }, 1000);
    }

    if (resendButton && Number.isInteger(resendSeconds) && resendSeconds > 0) {
      resendButton.disabled = true;
      const resendTimer = window.setInterval(function () {
        resendSeconds -= 1;
        resendButton.textContent = "Renvoyer le code (" + Math.max(0, resendSeconds) + "s)";
        if (resendSeconds <= 0) {
          resendButton.disabled = false;
          resendButton.textContent = "Renvoyer le code";
          window.clearInterval(resendTimer);
        }
      }, 1000);
    }

    code.addEventListener("input", validateCode);
    form.addEventListener("submit", function (event) {
      const submitter = event.submitter;
      if (submitter && submitter.value === "resend") {
        return;
      }
      if (!validateCode()) {
        event.preventDefault();
      }
    });
    validateCode();
  }

  document.querySelectorAll("[data-auth-form]").forEach(function (form) {
    const type = form.getAttribute("data-auth-form");
    if (type === "login") {
      setupLoginForm(form);
    }
    if (type === "email-code") {
      setupEmailCodeForm(form);
    }
  });
})();
