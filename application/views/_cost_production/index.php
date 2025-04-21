<div class="container">
    <h2 class="mt-4"><?= $title ?></h2>
    <!-- Filter Bulan (Month and Year dropdown) -->
<form method="GET" action="<?= site_url('cost_production'); ?>" class="form-inline mb-3">
    <label for="bulan" class="mr-2">Pilih Bulan:</label>
    <select id="bulan" name="bulan" class="form-control mr-3">
        <?php 
        $current_month = date('m');
        $current_year = date('Y');
        for ($i = 1; $i <= 12; $i++) :
            $month = sprintf('%02d', $i);
            $selected = $bulan == "$current_year-$month" ? 'selected' : '';
            echo "<option value='$current_year-$month' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . " $current_year</option>";
        endfor;
        ?>
    </select>

    <button type="submit" class="btn btn-primary">Terapkan</button>
</form>


    <!-- Tabel Laporan -->
    <div class="table-responsive">
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
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'cost_bar')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'revenue_bar')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'percent_bar')) / count($cost_data), 2) ?>%</th>

                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'cost_kitchen')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'revenue_kitchen')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'percent_kitchen')) / count($cost_data), 2) ?>%</th>

                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'cost_event')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'revenue_event')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'percent_event')) / count($cost_data), 2) ?>%</th>

                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'cost_bar')) + array_sum(array_column($cost_data, 'cost_kitchen')) + array_sum(array_column($cost_data, 'cost_event')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'total_revenue')), 2) ?></th>
                    <th class="text-right"><?= number_format(array_sum(array_column($cost_data, 'percent_total')) / count($cost_data), 2) ?>%</th>
                </tr>
            </tfoot>
        </table>
    </div>
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
