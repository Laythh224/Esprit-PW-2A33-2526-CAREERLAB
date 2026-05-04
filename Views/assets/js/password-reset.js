(function () {
  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function isStrongPassword(value) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/.test(value);
  }

  document.querySelectorAll("[data-password-reset-form]").forEach(function (form) {
    const mode = form.getAttribute("data-password-reset-form");

    if (mode === "request") {
      const email = document.getElementById("email");
      const emailError = document.getElementById("emailError");
      const submitButton = form.querySelector('button[type="submit"]');

      function validateRequest() {
        let valid = true;
        email.classList.remove("is-invalid", "is-valid");
        emailError.textContent = "";

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

        if (submitButton) {
          submitButton.disabled = !valid;
        }

        return valid;
      }

      form.addEventListener("submit", function (event) {
        if (!validateRequest()) {
          event.preventDefault();
        }
      });

      email.addEventListener("input", validateRequest);
      email.addEventListener("blur", validateRequest);
      validateRequest();
    }

    if (mode === "verify") {
      const code = document.getElementById("code");
      const codeError = document.getElementById("codeError");
      const countdown = document.getElementById("resetCountdown");
      const verifyButton = form.querySelector('button[value="verify"]');
      let seconds = Number.parseInt(form.getAttribute("data-expire-seconds") || "0", 10);

      function validateVerify() {
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

        if (verifyButton) {
          verifyButton.disabled = !valid || seconds <= 0;
        }

        return valid;
      }

      if (countdown && Number.isInteger(seconds) && seconds > 0) {
        const timer = window.setInterval(function () {
          seconds -= 1;
          countdown.textContent = String(Math.max(0, seconds));
          validateVerify();
          if (seconds <= 0) {
            window.clearInterval(timer);
          }
        }, 1000);
      }

      form.addEventListener("submit", function (event) {
        const submitter = event.submitter;
        if (submitter && submitter.value === "resend") {
          return;
        }

        if (!validateVerify()) {
          event.preventDefault();
        }
      });

      code.addEventListener("input", validateVerify);
      code.addEventListener("blur", validateVerify);
      validateVerify();
    }

    if (mode === "reset") {
      const password = document.getElementById("password");
      const confirmPassword = document.getElementById("confirmPassword");
      const passwordError = document.getElementById("passwordError");
      const confirmPasswordError = document.getElementById("confirmPasswordError");
      const submitButton = form.querySelector('button[type="submit"]');

      function validateReset() {
        let valid = true;
        password.classList.remove("is-invalid", "is-valid");
        confirmPassword.classList.remove("is-invalid", "is-valid");
        passwordError.textContent = "";
        confirmPasswordError.textContent = "";

        if (!password.value) {
          password.classList.add("is-invalid");
          passwordError.textContent = "Le nouveau mot de passe est obligatoire.";
          valid = false;
        } else if (!isStrongPassword(password.value)) {
          password.classList.add("is-invalid");
          passwordError.textContent = "8 caracteres minimum avec majuscule, minuscule, chiffre et caractere special.";
          valid = false;
        } else {
          password.classList.add("is-valid");
        }

        if (!confirmPassword.value) {
          confirmPassword.classList.add("is-invalid");
          confirmPasswordError.textContent = "La confirmation du mot de passe est obligatoire.";
          valid = false;
        } else if (confirmPassword.value !== password.value) {
          confirmPassword.classList.add("is-invalid");
          confirmPasswordError.textContent = "La confirmation du mot de passe ne correspond pas.";
          valid = false;
        } else if (valid) {
          confirmPassword.classList.add("is-valid");
        }

        if (submitButton) {
          submitButton.disabled = !valid;
        }

        return valid;
      }

      form.addEventListener("submit", function (event) {
        if (!validateReset()) {
          event.preventDefault();
        }
      });

      password.addEventListener("input", validateReset);
      confirmPassword.addEventListener("input", validateReset);
      password.addEventListener("blur", validateReset);
      confirmPassword.addEventListener("blur", validateReset);
      validateReset();
    }
  });
})();
