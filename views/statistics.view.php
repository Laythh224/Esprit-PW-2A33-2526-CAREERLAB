<?php
// Views/statistics.view.php
// Affichage simple des statistiques (aucune logique métier, que de l'affichage)
?>

<div class="statistics-module" style="padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="border-bottom: 2px solid #007BFF; padding-bottom: 10px; margin-bottom: 20px;">📊 Statistiques Simples et Utiles</h2>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row" style="margin-bottom: 30px;">
        <!-- Utilisateurs -->
        <div class="col-md-3">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007BFF;">
                <h4 style="color: #333; margin-top: 0;">👤 Utilisateurs</h4>
                <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                    <li><strong>Total :</strong> <?php echo htmlspecialchars((string) $totalUsers); ?></li>
                    <li><strong>Aujourd'hui :</strong> <?php echo htmlspecialchars((string) $usersToday); ?></li>
                    <li><strong>Ce mois :</strong> <?php echo htmlspecialchars((string) $usersThisMonth); ?></li>
                </ul>
            </div>
        </div>

        <!-- Entreprises -->
        <div class="col-md-3">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #FFC107;">
                <h4 style="color: #333; margin-top: 0;">🏢 Entreprises</h4>
                <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                    <li><strong>Total :</strong> <?php echo htmlspecialchars((string) $totalEntreprises); ?></li>
                    <li><strong>Ce mois :</strong> <?php echo htmlspecialchars((string) $entreprisesThisMonth); ?></li>
                    <li><strong>Actives :</strong> <?php echo htmlspecialchars((string) $entreprisesActives); ?></li>
                </ul>
            </div>
        </div>

        <!-- Formateurs -->
        <div class="col-md-3">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #28A745;">
                <h4 style="color: #333; margin-top: 0;">👨‍🏫 Formateurs</h4>
                <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                    <li><strong>Total :</strong> <?php echo htmlspecialchars((string) $totalFormateurs); ?></li>
                    <li><strong>Ce mois :</strong> <?php echo htmlspecialchars((string) $formateursThisMonth); ?></li>
                    <li><strong>Moyenne Inscriptions :</strong> <?php echo number_format($avgInscriptionsPerFormateur, 1); ?></li>
                </ul>
            </div>
        </div>

        <!-- Inscriptions -->
        <div class="col-md-3">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #17A2B8;">
                <h4 style="color: #333; margin-top: 0;">📝 Inscriptions</h4>
                <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                    <li><strong>Total :</strong> <?php echo htmlspecialchars((string) $totalInscriptions); ?></li>
                    <li><strong>Aujourd'hui :</strong> <?php echo htmlspecialchars((string) $inscriptionsToday); ?></li>
                    <li><strong>Ce mois :</strong> <?php echo htmlspecialchars((string) $inscriptionsThisMonth); ?></li>
                    <li style="margin-top: 5px; font-size: 0.9em; color: #555;">
                        (Ent: <?php echo htmlspecialchars((string) $repartitionEntreprise); ?> / For: <?php echo htmlspecialchars((string) $repartitionFormateur); ?>)
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <div class="row" style="margin-bottom: 30px;">
        <!-- Ligne : Utilisateurs -->
        <div class="col-md-6">
            <h3 style="color: #333; font-size: 1.2rem; text-align: center;">📈 Évolution Utilisateurs</h3>
            <canvas id="usersChart"></canvas>
        </div>
        <!-- Barres : Inscriptions -->
        <div class="col-md-6">
            <h3 style="color: #333; font-size: 1.2rem; text-align: center;">📊 Inscriptions par mois</h3>
            <canvas id="inscriptionsChart"></canvas>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <div class="row">
        <!-- Camembert : Répartition -->
        <div class="col-md-6 offset-md-3">
            <h3 style="color: #333; text-align: center; font-size: 1.2rem;">🎓 Répartition Inscriptions</h3>
            <div style="max-height: 300px; display: flex; justify-content: center;">
                <canvas id="repartitionChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // 1. Graphique en Ligne (Utilisateurs)
        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($usersChartLabels) ?>,
                datasets: [{
                    label: 'Nouvelles Inscriptions (Utilisateurs)',
                    data: <?= json_encode($usersChartData) ?>,
                    borderColor: '#007BFF',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            }
        });

        // 2. Graphique en Barres (Inscriptions par mois)
        new Chart(document.getElementById('inscriptionsChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($inscriptionsChartLabels) ?>,
                datasets: [{
                    label: 'Total Inscriptions',
                    data: <?= json_encode($inscriptionsChartData) ?>,
                    backgroundColor: '#17A2B8'
                }]
            }
        });

        // 3. Graphique en Camembert (Répartition)
        new Chart(document.getElementById('repartitionChart'), {
            type: 'pie', // Changed from doughnut to pie as requested (camembert)
            data: {
                labels: <?= json_encode($repartitionChartLabels) ?>,
                datasets: [{
                    label: 'Répartition',
                    data: <?= json_encode($repartitionChartData) ?>,
                    backgroundColor: ['#FFC107', '#28A745'] // Yellow (Ent) and Green (For)
                }]
            }
        });
    </script>
</div>
