(function () {
  function normalizeSpaces(value) {
    return String(value).trim().replace(/\s+/g, ' ');
  }

  function isDigits8(value) {
    return /^\d{8}$/.test(value);
  }

  function isName(value) {
    return /^[\p{L}\s\-']+$/u.test(value);
  }

  function isEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setFieldError(form, name, message) {
    const input = form.querySelector('[name="' + name + '"]');
    const error = form.querySelector('[data-error-for="' + name + '"]');
    if (!input || !error) {
      return;
    }

    if (message) {
      input.setAttribute('aria-invalid', 'true');
      input.setAttribute('aria-describedby', error.id);
      error.textContent = message;
      return;
    }

    input.removeAttribute('aria-invalid');
    input.removeAttribute('aria-describedby');
    error.textContent = '';
  }

  function clearErrors(form) {
    ['cin', 'nom', 'prenom', 'adresse', 'niveau', 'age', 'tel'].forEach(function (name) {
      setFieldError(form, name, '');
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('inscription-form');
    const submitBtn = document.getElementById('inscription-submit-btn');
    if (!form) {
      return;
    }

    form.addEventListener('submit', function (event) {
      clearErrors(form);
      const cin = normalizeSpaces(form.querySelector('[name="cin"]').value);
      const nom = normalizeSpaces(form.querySelector('[name="nom"]').value);
      const prenom = normalizeSpaces(form.querySelector('[name="prenom"]').value);
      const adresse = normalizeSpaces(form.querySelector('[name="adresse"]').value);
      const niveau = normalizeSpaces(form.querySelector('[name="niveau"]').value);
      const age = Number(form.querySelector('[name="age"]').value);
      const tel = normalizeSpaces(form.querySelector('[name="tel"]').value);

      if (!cin || !nom || !prenom || !adresse || !niveau || !tel) {
        event.preventDefault();
        if (!cin) setFieldError(form, 'cin', 'Le CIN est obligatoire.');
        if (!nom) setFieldError(form, 'nom', 'Le nom est obligatoire.');
        if (!prenom) setFieldError(form, 'prenom', 'Le prenom est obligatoire.');
        if (!adresse) setFieldError(form, 'adresse', 'L e-mail est obligatoire.');
        if (!niveau) setFieldError(form, 'niveau', 'Le niveau est obligatoire.');
        if (!tel) setFieldError(form, 'tel', 'Le telephone est obligatoire.');
        const firstInvalid = form.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        return;
      }

      if (!isDigits8(cin)) {
        event.preventDefault();
        setFieldError(form, 'cin', 'Le CIN doit contenir exactement 8 chiffres.');
        form.querySelector('[name="cin"]').focus();
        return;
      }

      if (!isDigits8(tel)) {
        event.preventDefault();
        setFieldError(form, 'tel', 'Le telephone doit contenir exactement 8 chiffres.');
        form.querySelector('[name="tel"]').focus();
        return;
      }

      if (!Number.isInteger(age) || age < 16 || age > 99) {
        event.preventDefault();
        setFieldError(form, 'age', 'L age doit etre un entier entre 16 et 99.');
        form.querySelector('[name="age"]').focus();
        return;
      }

      if (!isName(nom) || !isName(prenom)) {
        event.preventDefault();
        if (!isName(nom)) setFieldError(form, 'nom', 'Utilisez lettres, espaces, tirets ou apostrophes.');
        if (!isName(prenom)) setFieldError(form, 'prenom', 'Utilisez lettres, espaces, tirets ou apostrophes.');
        const firstInvalid = form.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        return;
      }

      if (!isEmail(adresse)) {
        event.preventDefault();
        setFieldError(form, 'adresse', 'Indiquez une adresse e-mail valide.');
        form.querySelector('[name="adresse"]').focus();
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi...';
      }
    });
  });
})();
