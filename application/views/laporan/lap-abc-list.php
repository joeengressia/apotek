<div class="data-list">
    <table width="100%" class="list-data">
        <thead>
            <tr>
                <th width="3%">No.</th>
                <th width="30%">Kemasan Barang</th>
                <th width="5%">Jumlah</th>
                <th width="10%">Harga</th>
                <th width="10%">Total Nilai</th>
                <th width="10%">%</th>
                <th width="10%">% Kumulatif</th>
                <th width="3%">Gol.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totals = 0;
            $persen = 0;
            foreach ($list_data as $key => $data) { 
            $total_harga = $data->harga*$data->jumlah;
            $persen = $persen+(($total_harga/$total->total)*100);
            if($persen >= 0 and $persen <= 80) {
                $gol = "A";
            }
            else if ($persen > 80 and $persen <= 95) {
                $gol = "B";
            } else {
                $gol = "C";
            }
                ?>
            <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                <td align="center"><?= ++$key ?></td>
                <td><?= $data->nama_barang ?></td>
                <td align="center"><?= $data->jumlah ?></td>
                <td align="right"><?= rupiah($data->harga) ?></td>
                <td align="right"><?= rupiah($total_harga) ?></td>
                <td align="center"><?= ($total_harga/$total->total)*100 ?></td>
                <td align="center"><?= $persen ?></td>
                <td align="center"><?= $gol ?></td>
            </tr>
            <?php 
            $totals = $totals + $total_harga;
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" align="right">TOTAL</td>
                <td align="right"><?= rupiah($totals) ?></td>
                <td align="center"><?= $persen ?></td>
                <td align="right"></td>
                <td align="right"></td>
            </tr>
        </tfoot>
    </table>
</div>