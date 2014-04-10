<?php
$border=null;
if (isset($_GET['do'])) {
    echo "<table width='100%' style='font-family: 'Lucida Sans Unicode'; color: #ffffff' bgcolor='#31849b'><tr><td colspan=8 align=center><b>LAPORAN KAS <br/>TANGGAL $_GET[awal] s/d $_GET[akhir] <br/>$_GET[jenis]</b></td></tr></table>";
    header_excel("kas-".$_GET['awal']." sd".$_GET['akhir']."-".$_GET['jenis'].".xls");
    $border = "border=1";
}
?>
<script>
    $(function() {
        $('.view_transaction').click(function() {
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#result_detail').html(data);
                $('#result_detail').dialog({
                    autoOpen: true,
                    height: 500,
                    width: 900,
                    modal: true,
                    close: function() {
                        $(this).dialog('close');
                    }
                });
            });
            return false;
        });
    });
</script>
<div id="result_detail" style="display: none"></div>
    <table class="list-data" width="100%" <?= $border ?>>
        <thead>
        <tr>
            <th width="3%">No.</th>
            <th width="10%">Waktu</th>
            <th width="3%">ID</th>
            <th width="10%">Jenis Transaksi</th>
            <th width="30%">Keterangan</th>
            <th width="10%">Masuk</th>
            <th width="10%">Keluar</th>
            <th width="10%">Sisa</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $penerimaan = 0;
        $pengeluaran= 0;
        $sisa = 0;
        foreach ($list_data as $key => $data) {
            $sisa = $sisa+($data->masuk-$data->keluar);
        ?>
        <tr class="<?= ($key%2==1)?'even':'odd' ?>">
            <td align="center"><?= ++$key ?></td>
            <td align="center"><?= datetime($data->waktu) ?></td>
            <td align="center"><?= isset($_GET['do'])?$data->id_transaksi:''.$data->id_transaksi.'' ?></td>
            <td><?= $data->transaksi ?></td>
            <td><?= $data->keterangan ?></td>
            <td align="right"><?= rupiah($data->masuk) ?></td>
            <td align="right"><?= rupiah($data->keluar) ?></td>
            <td align="right"><?= rupiah($sisa) ?></td>
        </tr>
        <?php 
        
        $penerimaan = $penerimaan+$data->masuk;
        $pengeluaran= $pengeluaran+$data->keluar;
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <?php
    if (!isset($_GET['do'])) { ?>
    <?php } ?>
<?php die; ?>