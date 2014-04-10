<link rel="stylesheet" href="<?= base_url('assets/css/print-struk.css') ?>" />
<script type="text/javascript">
window.onunload = refreshParent;
function refreshParent() {
    //window.opener.location.reload();
}
function cetak() {  		
    window.print();
    setTimeout(function(){ window.close();},300);
}
</script>
<body onload="cetak();" class="default-printing">
<?= header_surat() ?>
<?php
$label = get_bottom_label();
$apa   = get_apa_from_karyawan();
foreach ($data['list_data'] as $rows);
?>
<h2 style="text-align: center">SALINAN RESEP</h2>
<table width="100%" style="border-bottom: 1px solid #000;">
    <tr><td>No.: </td><td colspan="3" align="left"><?= $rows->id ?></td> </tr>
    <tr><td>Dokter: </td><td colspan="3"><?= $rows->dokter ?></td> </tr>
    <tr><td>Tanggal: </td><td colspan="3" align="left"><?= datetimefmysql($rows->waktu) ?></td> </tr>
    <tr><td>Pro: </td><td colspan="3"><?= $rows->pasien ?></td> </tr>
    <tr><td>Usia:</td><td colspan="3"><?= ($rows->tanggal_lahir=='0000-00-00')?'':hitungUmur($rows->tanggal_lahir) ?></td> </tr>
</table>
<table width="100%" style="border-bottom: 1px solid #000;">
<?php
    foreach ($detail as $key => $data) { 
        $then = NULL;
        if (($data->resep_r_jumlah - $data->tebus_r_jumlah) === 0) {
            $then = "Detur Originale";
        }
        else if (($data->resep_r_jumlah - $data->tebus_r_jumlah) == $data->resep_r_jumlah) {
            $then = "Nedet";
        }
        else if (($data->resep_r_jumlah - $data->tebus_r_jumlah) > 0) {
            $then = "Det ".$data->tebus_r_jumlah;
        }
         ?>
        <tr>
            <td><h1 style="font-size: 16px; margin-bottom: 0;">R /: <?= $data->r_no ?></h1></td>
        </tr>
        <?php 
        $obat = $this->m_pelayanan->get_list_data_resep_detail($_GET['id'], $data->r_no)->result();
        foreach ($obat as $val) { ?>
            <tr>
                <td style="padding-left: 20px"><?= $val->nama_barang ?></td>
            </tr>
        <?php } ?>
        <tr>
            <td style="padding-left: 20px"><?= $data->aturan ?> x <?= $data->pakai ?></td>
        </tr>
        <tr>
            <td style="padding-left: 20px">No. <?= $data->resep_r_jumlah ?> <?= $then ?><br/></td>
        </tr>
        
    <?php
    } ?>
</table>
<table width="100%" align="right">
    <tr><td align="right">Yogyakarta, <?= indo_tgl(date("Y-m-d")) ?></td></tr>
    <tr><td colspan="4" align="right">PCC</td> </tr>
    <!--<tr><td colspan="4" align="right">APA</td> </tr>-->
    <tr><td align="right">&nbsp;</td></tr>
    <tr><td align="right">&nbsp;</td></tr>
    <tr><td align="right"><?= isset($apa->nama)?$apa->nama:NULL ?></td></tr>
    <tr><td align="right"><?= isset($apa->no_sipa)?$apa->no_sipa:NULL ?></td></tr>
</table>
</body>