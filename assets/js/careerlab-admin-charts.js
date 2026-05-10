/**
 * Graphiques en secteurs (Chart.js v2) — admin Career Lab
 */
(function (global) {
  'use strict';

  var registry = {};

  function renderPie(canvasId, labels, data, colors) {
    var canvas = document.getElementById(canvasId);
    if (!canvas || typeof Chart === 'undefined') {
      return;
    }
    if (registry[canvasId]) {
      try {
        registry[canvasId].destroy();
      } catch (e) {}
      registry[canvasId] = null;
    }
    var sum = data.reduce(function (a, b) {
      return a + b;
    }, 0);
    var L = labels;
    var D = data;
    var C = colors;
    if (sum === 0) {
      L = ['(empty)'];
      D = [1];
      C = ['#dee2e6'];
    }
    registry[canvasId] = new Chart(canvas.getContext('2d'), {
      type: 'pie',
      data: {
        labels: L,
        datasets: [
          {
            data: D,
            backgroundColor: C
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        legend: {
          position: 'bottom'
        }
      }
    });
  }

  global.CareerLabCharts = {
    renderPie: renderPie
  };
})(window);
