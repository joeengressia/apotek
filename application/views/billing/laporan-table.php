<script type="text/javascript">
$("table").tablesorter();
</script>
<?php if (isset($_GET['do'])) { ?>
<script>
    window.print();    
    setTimeout(function(){ window.close();},300);
</script>
<link rel="stylesheet" href="<?= base_url('assets/css/print-A4-landscape.css') ?>" />
<style>
    .list-data { border-top: 1px solid #000; border-left: 1px solid #000; border-spacing: 0; }
    .list-data th { border-right: 1px solid #000; border-bottom: 1px solid #000; }
    .list-data td { border-right: 1px solid #000; border-bottom: 1px solid #000; }
</style>
<h3>Rekap Billing Harian <br/><?= $_GET['awal'] ?> s.d <?= $_GET['akhir'] ?></h3>
<?php } ?>
<div class="data-list">
    <table class="list-data" id="table" width="100%">
        <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="5%">Tanggal</th>
            <th width="5%">No. RM</th>
            <th width="20%">Nama Pasien</th>
            <th width="30%">Alamat</th>
            <th width="7%">Total <br/> Tagihan</th>
            <th width="7%">No.<br/> Pembayaran</th>
            <th width="7%">Jumlah<br/> Bayar</th>
            <th width="10%">Sisa</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        $sisa = 0;
        foreach ($list_data as $key => $data) {
            $tb = $this->m_billing->data_kunjungan_muat_data_total_barang($data->no_daftar);
            $tj = $this->m_billing->data_kunjungan_muat_data_total_jasa($data->no_daftar);
            ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><span class="link_button" onclick="pembayaran(<?= $data->no_daftar ?>)"><?= $data->no_daftar ?></span></td>
            <td align="center"><?= datetimefmysql($data->tgl_daftar) ?></td>
            <td align="center"><?= ($data->no_rm != '-')?str_pad($data->no_rm, 6,"0",STR_PAD_LEFT):$data->no_rm ?></td>
            <td><?= $data->pasien ?></td>
            <td><?= $data->alamat ?></td>
            <td align="right"><?= rupiah($tj->total_jasa+$tb->total_barang) ?></td>
            <td align="center"><?= $data->no_pembayaran ?></td>
            <td align="right"><?= ($data->bayar != null)?rupiah($data->bayar):rupiah(0) ?></td>
            <td align="right"><?= ($data->sisa == NULL)?rupiah($tj->total_jasa+$tb->total_barang):rupiah($data->sisa) ?></td>
        </tr>
        <?php 
        $total = $total + $data->bayar;
        $sisa  = $sisa + ($tj->total_jasa+$tb->total_barang);
        } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" align="right">TOTAL</td>
                <td align="right"><?= rupiah($total) ?></td>
                <td align="right"></td>
            </tr>
        </tfoot>
    </table>
</div>
<br/><br/>
<?php die; ?>