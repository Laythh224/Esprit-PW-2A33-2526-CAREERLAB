<?php
$usersStats = $usersStats ?? ['total' => 0, 'today' => 0, 'thisMonth' => 0, 'labels' => [], 'data' => []];
$entreprisesStats = $entreprisesStats ?? ['total' => 0, 'today' => 0, 'thisMonth' => 0, 'labels' => [], 'data' => []];
$formateursStats = $formateursStats ?? ['total' => 0, 'today' => 0, 'thisMonth' => 0, 'labels' => [], 'data' => []];
$globalPieStats = $globalPieStats ?? ['labels' => ['Utilisateurs', 'Entreprises', 'Formateurs'], 'data' => [0, 0, 0]];
?>

<div class="statistics-module card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <h2 class="h4 mb-0">Dashboard Statistiques (Utilisateurs, Entreprises, Formateurs)</h2>
            <form id="statsFilterForm" class="d-flex flex-wrap gap-2">
                <input type="date" class="form-control" id="startDate" name="start_date" value="<?php echo htmlspecialchars((string) ($startDate ?? '')); ?>">
                <input type="date" class="form-control" id="endDate" name="end_date" value="<?php echo htmlspecialchars((string) ($endDate ?? '')); ?>">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <button type="button" class="btn btn-outline-secondary" id="resetFilterBtn">Reset</button>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Utilisateurs</h5>
                        <p class="mb-1">Total: <strong id="users-total"><?php echo (int) $usersStats['total']; ?></strong></p>
                        <p class="mb-1">Aujourd'hui: <strong id="users-today"><?php echo (int) $usersStats['today']; ?></strong></p>
                        <p class="mb-0">Ce mois: <strong id="users-month"><?php echo (int) $usersStats['thisMonth']; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Entreprises</h5>
                        <p class="mb-1">Total: <strong id="entreprises-total"><?php echo (int) $entreprisesStats['total']; ?></strong></p>
                        <p class="mb-1">Aujourd'hui: <strong id="entreprises-today"><?php echo (int) $entreprisesStats['today']; ?></strong></p>
                        <p class="mb-0">Ce mois: <strong id="entreprises-month"><?php echo (int) $entreprisesStats['thisMonth']; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 bg-light h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Formateurs</h5>
                        <p class="mb-1">Total: <strong id="formateurs-total"><?php echo (int) $formateursStats['total']; ?></strong></p>
                        <p class="mb-1">Aujourd'hui: <strong id="formateurs-today"><?php echo (int) $formateursStats['today']; ?></strong></p>
                        <p class="mb-0">Ce mois: <strong id="formateurs-month"><?php echo (int) $formateursStats['thisMonth']; ?></strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <h6 class="text-center mb-2">Évolution Utilisateurs</h6>
                <canvas id="usersBarChart"></canvas>
            </div>
            <div class="col-lg-4">
                <h6 class="text-center mb-2">Évolution Entreprises</h6>
                <canvas id="entreprisesBarChart"></canvas>
            </div>
            <div class="col-lg-4">
                <h6 class="text-center mb-2">Évolution Formateurs</h6>
                <canvas id="formateursBarChart"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h6 class="text-center mb-2">Répartition Globale</h6>
                <canvas id="globalPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const STATS_API_URL = "index.php?page=api-statistiques";

    function getFilterQuery() {
        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;
        const params = new URLSearchParams();
        if (startDate) params.set("start_date", startDate);
        if (endDate) params.set("end_date", endDate);
        return params.toString();
    }

    function updateCard(prefix, stats) {
        document.getElementById(prefix + "-total").textContent = String(stats.total ?? 0);
        document.getElementById(prefix + "-today").textContent = String(stats.today ?? 0);
        document.getElementById(prefix + "-month").textContent = String(stats.thisMonth ?? 0);
    }

    function setBarChartData(chart, labels, data) {
        chart.data.labels = labels || [];
        chart.data.datasets[0].data = data || [];
        chart.update();
    }

    const usersBarChart = new Chart(document.getElementById("usersBarChart"), {
        type: "bar",
        data: {
            labels: <?= json_encode($usersStats['labels']) ?>,
            datasets: [{ label: "Utilisateurs", data: <?= json_encode($usersStats['data']) ?>, backgroundColor: "#0d6efd" }]
        }
    });

    const entreprisesBarChart = new Chart(document.getElementById("entreprisesBarChart"), {
        type: "bar",
        data: {
            labels: <?= json_encode($entreprisesStats['labels']) ?>,
            datasets: [{ label: "Entreprises", data: <?= json_encode($entreprisesStats['data']) ?>, backgroundColor: "#ffc107" }]
        }
    });

    const formateursBarChart = new Chart(document.getElementById("formateursBarChart"), {
        type: "bar",
        data: {
            labels: <?= json_encode($formateursStats['labels']) ?>,
            datasets: [{ label: "Formateurs", data: <?= json_encode($formateursStats['data']) ?>, backgroundColor: "#198754" }]
        }
    });

    const globalPieChart = new Chart(document.getElementById("globalPieChart"), {
        type: "pie",
        data: {
            labels: <?= json_encode($globalPieStats['labels']) ?>,
            datasets: [{
                label: "Répartition globale",
                data: <?= json_encode($globalPieStats['data']) ?>,
                backgroundColor: ["#0d6efd", "#ffc107", "#198754"]
            }]
        }
    });

    async function refreshStats() {
        try {
            const query = getFilterQuery();
            const url = query ? STATS_API_URL + "&" + query : STATS_API_URL;
            const response = await fetch(url, { headers: { "Accept": "application/json" } });
            const payload = await response.json();
            if (!payload.ok || !payload.stats) return;

            const stats = payload.stats;
            const users = stats.usersStats || {};
            const entreprises = stats.entreprisesStats || {};
            const formateurs = stats.formateursStats || {};
            const globalPie = stats.globalPieStats || { labels: [], data: [] };

            updateCard("users", users);
            updateCard("entreprises", entreprises);
            updateCard("formateurs", formateurs);

            setBarChartData(usersBarChart, users.labels || [], users.data || []);
            setBarChartData(entreprisesBarChart, entreprises.labels || [], entreprises.data || []);
            setBarChartData(formateursBarChart, formateurs.labels || [], formateurs.data || []);

            globalPieChart.data.labels = globalPie.labels || [];
            globalPieChart.data.datasets[0].data = globalPie.data || [];
            globalPieChart.update();

            const totalUsers = document.getElementById("dashboardTotalUsers");
            if (totalUsers) {
                totalUsers.textContent = String(users.total ?? 0);
            }
        } catch (error) {
            console.error("Erreur stats:", error);
        }
    }

    document.getElementById("statsFilterForm").addEventListener("submit", function (event) {
        event.preventDefault();
        refreshStats();
    });

    document.getElementById("resetFilterBtn").addEventListener("click", function () {
        document.getElementById("startDate").value = "";
        document.getElementById("endDate").value = "";
        refreshStats();
    });

    refreshStats();
    setInterval(refreshStats, 3000);
</script>
