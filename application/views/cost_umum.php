<div class="container">
    <h2>Laporan Cost Produksi</h2>



    <!-- Tabel Laporan -->
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2" style="position: sticky; left: 0; background-color: #f8f9fa; z-index: 10;">Tanggal</th>
                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">Bar</th>
                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">Kitchen</th>
                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">Event</th>
                    <th colspan="3" style="text-align: center; background-color: #f8f9fa;">Total</th>
                </tr>
                <tr>
                    <th style="text-align: center">Cost</th>
                    <th style="text-align: center">Revenue</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">Cost</th>
                    <th style="text-align: center">Revenue</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">Cost</th>
                    <th style="text-align: center">Revenue</th>
                    <th style="text-align: center">%</th>
                    <th style="text-align: center">Cost</th>
                    <th style="text-align: center">Revenue</th>
                    <th style="text-align: center">%</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cost_data as $row): ?>
                    <tr>
                        <td><?= $row['tanggal'] ?></td>
                        <td class="text-right"><?= number_format($row['cost_bar'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['revenue_bar'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['percent_bar'], 2) ?>%</td>

                        <td class="text-right"><?= number_format($row['cost_kitchen'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['revenue_kitchen'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['percent_kitchen'], 2) ?>%</td>

                        <td class="text-right"><?= number_format($row['cost_event'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['revenue_event'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['percent_event'], 2) ?>%</td>

                        <td class="text-right"><?= number_format($row['cost_bar'] + $row['cost_kitchen'] + $row['cost_event'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['total_revenue'], 2) ?></td>
                        <td class="text-right"><?= number_format($row['percent_total'], 2) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

<!-- Additional Styles for Sticky Table -->
<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }
    td, th {
        vertical-align: middle;
    }
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }

    /* Sticky Tanggal Column */
    tbody td:first-child, thead th:first-child {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 10;
    }
</style>
