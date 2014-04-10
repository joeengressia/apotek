<title>Nota Penjualan</title>
<link rel="stylesheet" href="<?= base_url('/assets/css/print-struk.css') ?>" />
<script type="text/javascript">
    function cetak() {
        window.print();    
        setTimeout(function(){ window.close();},300);
    }
</script>
<title><?= $title ?></title>
<body onload="cetak();">
<div class="layout-printer">
<table class="header-printer" style="border-bottom: 1px solid #000;" width="100%">
    <tr><td colspan="4" align="center" style="text-transform: uppercase">Rumah Sakit <?= $apt->nama ?></td> </tr>
    <tr><td colspan="4" align="center"><?= $apt->alamat ?> <?= $apt->kelurahan ?></td> </tr>
    <tr><td colspan="4" align="center">Telp. <?= $apt->telp ?>,  Fax. <?= $apt->fax ?>, Email <?= $apt->email ?></td> </tr>
</table>
<?php foreach ($arr['data'] as $data); ?>
<center><h2>NOTA PENJUALAN</h2></center>
<table width="100%" cellspacing="0">
    <tr><td width="20%">No.:</td><td> <?= $data->id ?>.<?= datefmysql($data->tanggal) ?></td></tr>
</table>
<table width="100%" cellspacing="0">
<?php 
$subtotal = 0;
foreach ($arr['data'] as $data) { ?>
    <tr><td colspan="3"><?= $data->nama_barang ?></td></tr>
    <tr><td style="padding-left: 40%;"><?= $data->qty ?></td><td align="right"><?= rupiah($data->harga_jual) ?></td><td align="right"><?= rupiah($data->harga_jual*$data->qty) ?></td></tr>
<?php 
$subtotal = $subtotal + ($data->harga_jual*$data->qty);
} ?>
</table>
<hr/>
<table width="100%">
    <tr><td width="50%">Subtotal:</td><td align="right" width="50%"><?= rupiah($subtotal) ?></td></tr>
    <tr><td width="50%">Tuslah:</td><td align="right" width="50%"><?= rupiah($data->tuslah) ?></td></tr>
    <tr><td width="50%">Embalage:</td><td align="right" width="50%"><?= rupiah($data->embalage) ?></td></tr>
    <?php if ($data->diskon_persen !== '0' and $data->diskon_rupiah !== '0') { ?>
    <tr><td width="50%">Diskon:</td><td align="right" width="50%"><?= rupiah($data->diskon_rupiah) ?></td></tr>
    <?php } ?>
    <tr><td width="50%">Total:</td><td align="right" width="50%"><?= rupiah(($subtotal+$data->tuslah+$data->embalage)-$data->diskon_rupiah) ?></td></tr>
    <tr><td width="50%">Tunai:</td><td align="right" width="50%"><?= rupiah($data->uang_pembayaran) ?></td></tr>
    <tr><td width="50%">Kembali:</td><td align="right" width="50%"><?= rupiah($data->uang_pembayaran-$data->total) ?></td></tr>
</table>
<hr/>
<center>SEMOGA LEKAS SEMBUH</center>