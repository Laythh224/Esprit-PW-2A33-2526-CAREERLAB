(function () {
  function togglePassword(field) {
    if (!field) {
      return;
    }

    field.type = field.type === "password" ? "text" : "password";
  }

  document.querySelectorAll("[data-toggle-password]").forEach(function (button) {
    button.addEventListener("click", function () {
      const targetId = button.getAttribute("data-toggle-target");
      const field = targetId ? document.getElementById(targetId) : null;
      togglePassword(field);
    });
  });
})();
