<link media="print" rel="stylesheet" href="<?= base_url('assets/css/print-A4-landscape.css') ?>" />
<title>REKAP PEMESANAN</title>
<script type="text/javascript">
function cetak() {
    window.print();
    setTimeout(function(){ window.close();},300);
    //SCETAK.innerHTML = '<br /><input onClick=\'cetak()\' type=\'submit\' name=\'Submit\' value=\'Cetak\' class=\'tombol\'>';
}
</script>
<body onload="cetak();">
<?php    header_surat(); ?>
<h1>
    LAPORAN PEMESANAN BARANG <br /> TANGGAL <?= $_GET['awal'] ?> s . d <?= $_GET['akhir'] ?>
</h1>
<table cellspacing="0" width="100%" class="list-data-print">
<thead>
    <tr class="italic">
        <th width="3%">No.</th>
        <th width="10%">No. SP</th>
        <th width="5%">Tanggal</th>
        <th width="20%">Nama Supplier</th>
        <th width="15%">Karyawan</th>
        <th width="20%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Jumlah</th>
    </tr>
</thead>
<tbody>
    <?php
    
    $no = 1;
    $sp = "";
    foreach ($list_data as $key => $data) { 
        
        ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= ($sp !== $data->id)?($no):NULL ?></td>
            <td><?= ($sp !== $data->id)?$data->id:NULL ?></td>
            <td align="center"><?= ($sp !== $data->id)?datetimefmysql($data->tanggal):NULL ?></td>
            <td><?= ($sp !== $data->id)?$data->supplier:NULL ?></td>
            <td><?= ($sp !== $data->id)?$data->karyawan:NULL ?></td>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= $data->jumlah ?></td>
        </tr>
    <?php 
    if ($sp !== $data->id) {
        $no++;
    }
    $sp = $data->id;
    }
    ?>
</tbody>
</table>