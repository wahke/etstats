
</div> <!-- Ende container-fluid -->

<footer class="text-center text-muted mt-5 mb-3">
    <hr>
    <p>© <?= date('Y') ?> ETStats Web by <a href="https://wahke.lu">wahke.lu - Version: <?= htmlspecialchars($version) ?></a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('killChart').getContext('2d');
    const killChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($kill_dates) ?>,
            datasets: [{
                label: 'Kills pro Tag',
                data: <?= json_encode($kill_counts) ?>,
                tension: 0.2,
                fill: false,
                borderColor: 'blue',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

</body>
</html>
