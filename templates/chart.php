
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mt-5">
    <h4><?= $chart_title ?? 'Statistik' ?></h4>
    <canvas id="<?= $chart_id ?? 'myChart' ?>" width="400" height="150"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('<?= $chart_id ?? 'myChart' ?>').getContext('2d');
    new Chart(ctx, {
        type: '<?= $chart_type ?? 'line' ?>',
        data: <?= json_encode($chart_data ?? []) ?>,
        options: <?= json_encode($chart_options ?? ['responsive' => true]) ?>
    });
});
</script>
