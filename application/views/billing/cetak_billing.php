<link rel="stylesheet" href="<?= base_url('/assets/css/print-struk.css') ?>" />
<script type="text/javascript">
    function cetak() {
        window.print();    
        setTimeout(function(){ window.close();},300);
    }
</script>
<title><?= $title ?></title>
<body onload="cetak()">
<div class="layout-printer">
<table class="header-printer" style="border-bottom: 1px solid #000;" width="100%">
    <tr><td colspan="4" align="center" style="text-transform: uppercase">Rumah Sakit <?= $apt->nama ?></td> </tr>
    <tr><td colspan="4" align="center"><?= $apt->alamat ?> <?= $apt->kelurahan ?></td> </tr>
    <tr><td colspan="4" align="center">Telp. <?= $apt->telp ?>,  Fax. <?= $apt->fax ?>, Email <?= $apt->email ?></td> </tr>
</table>
    <center><h2>NOTA</h2></center>
<?php
    $selain_kunj_akomodasi_kamar = 0;
    $total_barang = 0;
    $total_akomodasi_kamar = 0;
?>
<?php
foreach ($attribute as $rows);
$detail = $this->m_billing->detail_atribute_penduduk_by_norm($rows->no_rm)->row();
?>
<table width="100%">
    <tr><td width="20%">Waktu:</td><td><?= datefrompg($rows->tgl_layan) ?></td></tr>
    <tr><td>No. RM:</td><td><?= str_pad($rows->no_rm, 6,"0",STR_PAD_LEFT)?></td></tr>
    <tr><td>Nama:</td><td><?= $rows->nama ?></td></tr>
    <tr><td>Alamat:</td><td><?= isset($detail->alamat)?$detail->alamat:'' ?> <?= isset($detail->kelurahan)?$detail->kelurahan:'' ?> <?= isset($detail->kecamatan)?$detail->kecamatan:'' ?> <?= isset($detail->kabupaten)?$detail->kabupaten:'' ?></td></tr>
    
</table>
<table width="100%" border="0">
    <tr>
        <th width="5%">No.</th>
        <th align="left" width="45%">TARIF</th>
        <th width="15%">Harga</th>
        <th width="10%">Jml</th>
        <th align="right" width="10%">Subtotal</th>
    </tr>
    <?php
        $total_kunj = 0;
        foreach ($daftar_kunjungan as $key => $data) { 
    ?>
    <tr valign="top">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->layanan ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="center"><?= $data->frekuensi ?></td>
        <td align="right"><?= rupiah($data->subtotal) ?></td>
    </tr>
    <?php 
    $total_kunj = $total_kunj+$data->subtotal;
    } ?>
    <tr>
        <td colspan="4" align="right"><b>Total</b></td>
        <td align="right"><b><?= rupiah($total_kunj) ?></b></td>
    </tr>
<br/>

<?php if($akomodasi_kamar_inap != null):?>
    <tr>
        <th width="5%">No.</th>
        <th align="left" width="45%">TARIF</th>
        <th width="15%">Harga</th>
        <th width="10%">Jml</th>
        <th align="right" width="10%">Subtotal</th>
    </tr>
    <?php 
        $total_akomodasi_kamar = 0;
        foreach ($akomodasi_kamar_inap as $key => $data) { 
    ?>
    <tr valign="top">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->layanan ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="center"><?= $data->frekuensi ?></td>
        <td align="right"><?= rupiah($data->subtotal) ?></td>
    </tr>
    
        <?php 
        $total_akomodasi_kamar = $total_akomodasi_kamar+$data->subtotal;
        } ?>
   
    <tr>
        <td align="right" colspan="4"><b>Total</b></td>
        <td align="right"><b><?= rupiah($total_akomodasi_kamar) ?></b></td>
    </tr>
<?php endif; ?>
    <br/>


<?php if($selain != null):?>
    <tr>
        <th width="5%">No.</th>
        <th align="left" width="45%">TARIF</th>
        <th width="15%">Harga</th>
        <th width="10%">Jml</th>
        <th align="right" width="10%">Subtotal</th>
    </tr>
    <?php 
        $selain_kunj_akomodasi_kamar = 0;
        foreach ($selain as $key => $data) { 
    ?>
    <tr valign="top">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->layanan ?></td>
        <td align="right"><?= rupiah($data->nominal) ?></td>
        <td align="center"><?= $data->frekuensi ?></td>
        <td align="right"><?= rupiah($data->subtotal) ?></td>
    </tr>
    
    <?php 
        $selain_kunj_akomodasi_kamar = $selain_kunj_akomodasi_kamar+$data->subtotal;
    } ?>
   
    <tr>
        <td align="right" colspan="4"><b>Total</b></td>
        <td align="right"><b><?= rupiah($selain_kunj_akomodasi_kamar) ?></b></td>
    </tr>
<?php endif; ?>
    <br/>

<?php if($barang_list_data !== null):?>
    <tr>
        <th width="5%">No.</th>
        <th align="left" width="45%">Barang</th>
        <th width="15%">Harga</th>
        <th width="10%">Qty</th>
        <th align="right" width="10%">Subtotal</th>
    </tr>
    <?php 
        foreach ($barang_list_data as $key => $data) { 
            $harga_jual = $data->harga_jual;
            //$subtotal = $harga_jual;
    ?>
    <tr valign="top">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->barang ?> <?= ($data->kekuatan == '1') ? '' : $data->kekuatan ?> <?= $data->satuan ?></td>
        <td align="right"><?= rupiah($harga_jual) ?></td>
        <td align="center"><?= $data->keluar ?></td>
        <td align="right">
            <?php 
                $total_barang += $data->subtotal;
                echo rupiah($data->subtotal);
            ?>
        </td>
    </tr>
    <?php 
    } ?>
   <tr valign="top">
        <td align="center"><?= ++$key ?></td>
        <td>JASA APOTEKER</td>
        <td></td>
        <td></td>
        <td align="right"><?= rupiah($jasa_apoteker->total_jasa_apt) ?></td>
    </tr>
    <tr>
        <td align="right" colspan="4"><b>Total</b></td>
        <td align="right"><b><?= rupiah($total_barang+$jasa_apoteker->total_jasa_apt) ?></b></td>
    </tr>
</table>
<?php endif; ?>


<?php
$totallica = $total_kunj+$total_akomodasi_kamar+$selain_kunj_akomodasi_kamar+$total_barang+$jasa_apoteker->total_jasa_apt;
$sisa_tagihan = $totallica - $pembayaran->bayar;
?>
<table align="right">
    <tr>
        <td><b>Total Tagihan:</b></td><td><b><?= rupiah($totallica) ?></b></td>
    </tr>
    <tr>
        <td><b>Bayar:</b></td><td><b><?= rupiah($pembayaran->bayar) ?></b></td>
    </tr>
    <tr>
        <td><b>Sisa Tagihan:</b></td><td><b><?= rupiah(($sisa_tagihan<0)?'0':($totallica - $pembayaran->bayar)) ?></b></td>
    </tr>
</table>
    <!--<tr>
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->barang ?> <?= ($data->kekuatan == '1') ? '' : $data->kekuatan ?> <?= $data->satuan ?> <?= $data->sediaan ?> <?= (($data->generik == 'Non Generik') ? '' : $data->pabrik) ?> @ <?= (($data->isi == '1') ? '' : $data->isi) ?> <?= $data->satuan_terkecil ?></td>
        <td align="right"><?= rupiah($harga_jual) ?></td>
        <td align="center"><?= $data->keluar ?></td>
        <td align="right">
            <?php 
                $total_barang +=  $data->subtotal;
                echo rupiah($data->subtotal);
            ?>
        </td>
    </tr>-->