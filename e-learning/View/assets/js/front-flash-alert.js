(function () {
  document.addEventListener('DOMContentLoaded', function () {
    const node = document.getElementById('front-flash-message');
    if (!node) {
      return;
    }

    const message = (node.dataset.message || '').trim();
    if (message) {
      node.textContent = message;
      node.hidden = false;
    }
  });
})();
