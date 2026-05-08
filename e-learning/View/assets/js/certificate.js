/**
 * Certificat : impression uniquement.
 */
(function () {
  function qs(sel) {
    return document.querySelector(sel);
  }

  window.certPrint = function () {
    window.print();
  };

  document.addEventListener("DOMContentLoaded", function () {
    var btnPrint = qs("[data-cert-print]");
    if (btnPrint) {
      btnPrint.addEventListener("click", function (e) {
        e.preventDefault();
        window.print();
      });
    }
  });
})();
