(function () {
  function debounce(fn, delay) {
    let timer = null;
    return function () {
      const args = arguments;
      const context = this;
      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        fn.apply(context, args);
      }, delay);
    };
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function isValidPassword(value) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/.test(value);
  }

  function isLettersOnly(value) {
    return /^[\p{L}\s'-]{2,}$/u.test(value);
  }

  function isCompanyName(value) {
    return /^[\p{L}\d][\p{L}\d\s\-_]{2,}$/u.test(value);
  }

  function isInternationalPhone(value) {
    return /^\+[1-9]\d{6,14}$/.test(value);
  }

  function isValidFiscalCode(value) {
    return /^[A-Za-z0-9-]{6,20}$/.test(value);
  }

  function isValidHttpsUrl(value) {
    try {
      const url = new URL(value);
      return url.protocol === "https:";
    } catch (error) {
      return false;
    }
  }

  function setState(input, errorNode, message, valid) {
    if (!input) {
      return;
    }

    input.classList.remove("is-invalid", "is-valid");

    if (message) {
      input.classList.add("is-invalid");
      if (errorNode) {
        errorNode.textContent = message;
      }
      return;
    }

    if (valid) {
      input.classList.add("is-valid");
    }

    if (errorNode) {
      errorNode.textContent = "";
    }
  }

  function validatePdf(input, requiredLabel) {
    const file = input && input.files ? input.files[0] : null;
    if (!file) {
      return requiredLabel + " est obligatoire.";
    }
    if (!file.name.toLowerCase().endsWith(".pdf")) {
      return requiredLabel + " doit etre un fichier PDF.";
    }
    if (file.size > 2 * 1024 * 1024) {
      return requiredLabel + " ne doit pas depasser 2 Mo.";
    }
    if (file.type && file.type !== "application/pdf") {
      return requiredLabel + " doit avoir le type MIME application/pdf.";
    }
    return "";
  }

  document.querySelectorAll("[data-signup-form]").forEach(function (form) {
    const formType = form.getAttribute("data-form-type");
    const submitButton = form.querySelector('button[type="submit"]');
    const emailCache = { value: "", available: null, message: "" };
    const telephoneVisible = form.querySelector('[data-phone-visible]');
    const telephoneHidden = form.querySelector('[data-phone-hidden]');

    function syncPhone() {
      if (!telephoneVisible || !telephoneHidden) {
        return;
      }

      const rawValue = telephoneVisible.value.trim();
      if (window.intlTelInputGlobals) {
        const instance = window.intlTelInputGlobals.getInstance(telephoneVisible);
        if (instance) {
          const intlValue = instance.getNumber();
          telephoneHidden.value = intlValue || rawValue;
          return;
        }
      }

      telephoneHidden.value = rawValue;
    }

    function getFields() {
      const common = {
        email: form.querySelector("#email"),
        password: form.querySelector("#password"),
        confirm_password: form.querySelector("#confirmPassword"),
        telephone: form.querySelector('[data-phone-visible]'),
        cv: form.querySelector("#cv")
      };

      if (formType === "entreprise") {
        return Object.assign(common, {
          nom: form.querySelector("#nomEntreprise"),
          code_fiscal: form.querySelector("#codeFiscal"),
          adresse: form.querySelector("#adresse"),
          ville: form.querySelector("#ville"),
          secteur: form.querySelector("#secteur"),
          description: form.querySelector("#description"),
          site: form.querySelector("#siteWeb")
        });
      }

      if (formType === "utilisateur") {
        return Object.assign(common, {
          nom: form.querySelector("#nom"),
          prenom: form.querySelector("#prenom"),
          sexe: form.querySelector("#sexe"),
          niveau: form.querySelector("#niveauEtude"),
          domaine: form.querySelector("#domaine")
        });
      }

      return Object.assign(common, {
        nom: form.querySelector("#nom"),
        prenom: form.querySelector("#prenom"),
        sexe: form.querySelector("#sexe"),
        specialite: form.querySelector("#specialite"),
        diplomes: form.querySelector("#diplomeCount"),
        experience: form.querySelector("#experience")
      });
    }

    const fields = getFields();
    const errorNodes = {
      nom: form.querySelector(formType === "entreprise" ? "#nomEntrepriseError" : "#nomError"),
      prenom: form.querySelector("#prenomError"),
      sexe: form.querySelector("#sexeError"),
      email: form.querySelector("#emailError"),
      password: form.querySelector("#passwordError"),
      confirm_password: form.querySelector("#confirmPasswordError"),
      telephone: form.querySelector("#telephoneError"),
      cv: form.querySelector("#cvError"),
      code_fiscal: form.querySelector("#codeFiscalError"),
      adresse: form.querySelector("#adresseError"),
      ville: form.querySelector("#villeError"),
      secteur: form.querySelector("#secteurError"),
      description: form.querySelector("#descriptionError"),
      site: form.querySelector("#siteWebError"),
      specialite: form.querySelector("#specialiteError"),
      diplomes: form.querySelector("#diplomesError"),
      experience: form.querySelector("#experienceError"),
      niveau: form.querySelector("#niveauEtudeError"),
      domaine: form.querySelector("#domaineError")
    };

    function validateField(name) {
      syncPhone();

      const input = fields[name];
      const value = input && typeof input.value === "string" ? input.value.trim() : "";
      let message = "";

      if (name === "nom") {
        if (value === "") {
          message = formType === "entreprise" ? "Le nom de l'entreprise est obligatoire." : "Le nom est obligatoire.";
        } else if (formType === "entreprise" && !isCompanyName(value)) {
          message = "Le nom de l'entreprise doit contenir au moins 3 caracteres.";
        } else if (formType !== "entreprise" && !isLettersOnly(value)) {
          message = "Le nom doit contenir uniquement des lettres.";
        }
      }

      if (name === "prenom") {
        if (value === "") {
          message = "Le prenom est obligatoire.";
        } else if (!isLettersOnly(value)) {
          message = "Le prenom doit contenir uniquement des lettres.";
        }
      }

      if (name === "sexe") {
        if (value === "") {
          message = "Le sexe est obligatoire.";
        } else if (!["homme", "femme"].includes(value.toLowerCase())) {
          message = "Veuillez choisir un sexe valide.";
        }
      }

      if (name === "email") {
        if (value === "") {
          message = "L'email est obligatoire.";
        } else if (!isValidEmail(value)) {
          message = "Veuillez saisir une adresse email valide.";
        } else if (emailCache.value === value && emailCache.available === false) {
          message = emailCache.message || "Cet email est deja utilise.";
        }
      }

      if (name === "password") {
        if (value === "") {
          message = "Le mot de passe est obligatoire.";
        } else if (!isValidPassword(value)) {
          message = "8 caracteres minimum, avec majuscule, minuscule, chiffre et caractere special.";
        }
      }

      if (name === "confirm_password") {
        if (value === "") {
          message = "La confirmation du mot de passe est obligatoire.";
        } else if (value !== (fields.password ? fields.password.value : "")) {
          message = "La confirmation du mot de passe doit etre identique.";
        }
      }

      if (name === "telephone") {
        const phoneValue = telephoneHidden ? telephoneHidden.value.trim() : value;
        if (phoneValue === "") {
          message = "Le telephone est obligatoire.";
        } else if (!isInternationalPhone(phoneValue)) {
          message = "Le numero de telephone doit etre au format international, par exemple +216XXXXXXXX.";
        }
      }

      if (name === "cv") {
        message = validatePdf(input, "Le CV");
      }

      if (name === "code_fiscal") {
        if (value === "") {
          message = "Le code fiscal est obligatoire.";
        } else if (!isValidFiscalCode(value)) {
          message = "Le code fiscal doit etre alphanumerique (6 a 20 caracteres).";
        }
      }

      if (name === "adresse" && value === "") {
        message = "L'adresse est obligatoire.";
      }

      if (name === "ville") {
        if (value === "") {
          message = "La ville est obligatoire.";
        } else if (!isLettersOnly(value)) {
          message = "La ville doit contenir uniquement des lettres.";
        }
      }

      if (name === "secteur" && value === "") {
        message = "Le secteur d'activite est obligatoire.";
      }

      if (name === "description") {
        if (value === "") {
          message = "La description est obligatoire.";
        } else if (value.length < 20) {
          message = "La description doit contenir au moins 20 caracteres.";
        }
      }

      if (name === "site" && value !== "" && !isValidHttpsUrl(value)) {
        message = "Le site web doit etre une URL valide commencant par https://";
      }

      if (name === "specialite") {
        if (value === "") {
          message = "La specialite est obligatoire.";
        } else if (value.length < 3) {
          message = "La specialite doit contenir au moins 3 caracteres.";
        }
      }

      if (name === "diplomes") {
        const count = Number.parseInt(value, 10);
        if (value === "") {
          message = "Le nombre de diplomes est obligatoire.";
        } else if (!Number.isInteger(count) || count <= 0) {
          message = "Le nombre de diplomes doit etre un entier positif.";
        }
      }

      if (name === "experience") {
        if (value === "") {
          message = "L'experience est obligatoire.";
        } else if (value.length < 3) {
          message = "L'experience doit contenir au moins 3 caracteres.";
        }
      }

      if (name === "niveau" && value === "") {
        message = "Le niveau d'etude est obligatoire.";
      }

      if (name === "domaine" && value === "") {
        message = "Le domaine est obligatoire.";
      }

      setState(input, errorNodes[name], message, message === "" && value !== "");
      return message === "";
    }

    function validateForm() {
      const fieldNames = Object.keys(fields).filter(function (key) {
        return fields[key];
      });

      const allValid = fieldNames.every(validateField);
      if (submitButton) {
        submitButton.disabled = !allValid;
      }
      return allValid;
    }

    const debouncedEmailCheck = debounce(function () {
      const emailInput = fields.email;
      if (!emailInput) {
        return;
      }

      const email = emailInput.value.trim();
      if (!isValidEmail(email)) {
        emailCache.value = email;
        emailCache.available = null;
        emailCache.message = "";
        validateForm();
        return;
      }

      fetch("index.php?page=api-check-email&email=" + encodeURIComponent(email), {
        headers: { "X-Requested-With": "XMLHttpRequest" }
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (payload) {
          emailCache.value = email;
          emailCache.available = Boolean(payload.available);
          emailCache.message = payload.message || "";
          validateField("email");
          validateForm();
        })
        .catch(function () {
          emailCache.value = email;
          emailCache.available = null;
          emailCache.message = "";
          validateForm();
        });
    }, 300);

    Object.keys(fields).forEach(function (name) {
      const input = fields[name];
      if (!input) {
        return;
      }

      const eventName = input.type === "file" || input.tagName === "SELECT" ? "change" : "input";
      input.addEventListener(eventName, function () {
        if (name === "email") {
          emailCache.value = "";
          emailCache.available = null;
          emailCache.message = "";
          debouncedEmailCheck();
        }
        validateField(name);
        if (name === "password" && fields.confirm_password) {
          validateField("confirm_password");
        }
        validateForm();
      });

      input.addEventListener("blur", function () {
        if (name === "email") {
          debouncedEmailCheck();
        }
        validateField(name);
        validateForm();
      });
    });

    if (telephoneVisible) {
      telephoneVisible.addEventListener("countrychange", function () {
        syncPhone();
        validateField("telephone");
        validateForm();
      });
    }

    form.addEventListener("submit", function (event) {
      syncPhone();
      if (!validateForm()) {
        event.preventDefault();
      }
    });

    syncPhone();
    validateForm();
  });
})();
