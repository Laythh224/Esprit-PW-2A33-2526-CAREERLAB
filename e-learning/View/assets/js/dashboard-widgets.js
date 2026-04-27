(function () {
  function initStatisticsChart() {
    if (typeof window.Chart === "undefined") {
      return;
    }

    const canvas = document.getElementById("statisticsChart");
    if (!canvas) {
      return;
    }

    const ctx = canvas.getContext("2d");
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [
          {
            label: "Subscribers",
            data: [180, 190, 205, 214, 225, 240, 265, 278, 290, 305, 332, 350],
            borderColor: "#ef4444",
            backgroundColor: "rgba(239, 68, 68, 0.18)",
            tension: 0.35,
            fill: true,
            pointRadius: 0,
            borderWidth: 2,
          },
          {
            label: "New Visitors",
            data: [120, 132, 145, 150, 160, 170, 182, 195, 205, 216, 230, 244],
            borderColor: "#f59e0b",
            backgroundColor: "rgba(245, 158, 11, 0.15)",
            tension: 0.35,
            fill: true,
            pointRadius: 0,
            borderWidth: 2,
          },
          {
            label: "Active Users",
            data: [280, 260, 248, 276, 268, 245, 228, 242, 280, 292, 320, 410],
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59, 130, 246, 0.2)",
            tension: 0.35,
            fill: true,
            pointRadius: 0,
            borderWidth: 2.2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              usePointStyle: true,
              boxWidth: 8,
            },
          },
        },
        scales: {
          x: {
            grid: {
              display: false,
            },
          },
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 100,
            },
            grid: {
              color: "rgba(148, 163, 184, 0.16)",
            },
          },
        },
      },
    });
  }

  function initDailySalesChart() {
    if (typeof window.Chart === "undefined") {
      return;
    }

    const canvas = document.getElementById("dailySalesChart");
    if (!canvas) {
      return;
    }

    const ctx = canvas.getContext("2d");
    if (!ctx) {
      return;
    }

    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [
          {
            data: [420, 460, 390, 410, 370, 355, 340],
            borderColor: "rgba(255,255,255,0.95)",
            backgroundColor: "rgba(255,255,255,0.15)",
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            enabled: false,
          },
        },
        scales: {
          x: {
            display: false,
          },
          y: {
            display: false,
          },
        },
      },
    });
  }

  function initWorldMap() {
    if (typeof window.jsVectorMap === "undefined") {
      return;
    }

    const mapNode = document.getElementById("world-map");
    if (!mapNode) {
      return;
    }

    new window.jsVectorMap({
      selector: "#world-map",
      map: "world",
      zoomButtons: false,
      regionStyle: {
        initial: {
          fill: "#dfe6f3",
          stroke: "#dfe6f3",
        },
      },
      markers: [
        { coords: [-6.2, 106.8], name: "Indonesia", style: { fill: "#22c55e" } },
        { coords: [38.9, -77], name: "USA", style: { fill: "#334155" } },
        { coords: [55.7, 37.6], name: "Russia", style: { fill: "#1d4ed8" } },
        { coords: [39.9, 116.4], name: "China", style: { fill: "#ef4444" } },
        { coords: [-15.7, -47.8], name: "Brazil", style: { fill: "#f59e0b" } },
      ],
      markerStyle: {
        initial: {
          r: 4,
        },
      },
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initStatisticsChart();
    initDailySalesChart();
    initWorldMap();
  });
})();
