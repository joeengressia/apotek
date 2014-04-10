<link rel="stylesheet" href="<?= base_url('assets/css/printing-A4.css') ?>"/>
<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<style>
    .list-data { border-left: 1px solid #000; border-top: 1px solid #000; border-spacing: 0; }
    .list-data th,.list-data td { border-right: 1px solid #000; border-bottom: 1px solid #000; height: 20px; }
    .list-data tr { vertical-align: text-top; }
</style>
<body onload="cetak();">
    <table width="100%" style="color: #000; border-bottom: 1px solid #000;">
        <tr>
            <td rowspan="3" style="width: 70px"><img src="<?= base_url('assets/img/'.$clinic->logo_file_nama) ?>" width="70px" height="70px" /></td>    
            <td colspan="3" align="center"><b><?= strtoupper($clinic->nama) ?></b></td> <td rowspan="3" style="width: 70px">&nbsp;</td>
        </tr>
        <tr><td colspan="3" align="center"><b><?= strtoupper($clinic->alamat) ?></b></td> </tr>
        <tr><td colspan="3" align="center"><b>TELP. <?= $clinic->telp ?>,  FAX. <?= $clinic->fax ?>, EMAIL <?= $clinic->email ?></b></td> </tr>
    </table>
    <?php foreach ($list_data as $attr); ?>
    <table width="100%"><tr><td align="center"><h2>DISTRIBUSI UNIT</h2></td></tr></table>
    <table width="100%">
        <tr><td width="25%">No. Distribusi:</td><td><?= $attr->id ?></td></tr>
        <tr><td width="15%">Tanggal:</td><td><?= date("d F Y") ?></td></tr>
        <tr><td>Unit Asal:</td><td><?= $attr->asal ?></td></tr>
        <tr><td>Unit Tujuan:</td><td><?= $attr->tujuan ?></td></tr>
    </table>
    <br/>
    <table><tr><td colspan="4">Berikut daftar barang-barang yang di distribusikan:</td></tr></table>
    <table width="100%" class="list-data">
        <tr>
            <th width="3%">No.</th>
            <th width="20%">Nama Barang</th>
            <th width="5%">Kemasan</th>
            <th width="5%">Jumlah</th>
        </tr>
        <?php foreach ($list_data as $key => $data) { ?>
        <tr>
            <td align="center"><?= ++$key ?></td>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= $data->jumlah ?></td>
        </tr>
        <?php } ?>
    </table>
    <table align="right" width="100%">
        <tr><td align="right" colspan="4">Mengetahui, Kepala Unit</td> </tr>
        <tr><td colspan="4">&nbsp;</td> </tr>
        <tr><td colspan="4">&nbsp;</td> </tr>
    <!--    <tr><td align="right" colspan="4"><?= isset($manager->nama)?$manager->nama:NULL ?></td> </tr>
        <tr><td align="right" colspan="4"><?= isset($manager->sip_no)?$manager->sip_no:NULL ?></td> </tr>-->
        <tr><td align="right" colspan="4"><?= $this->session->userdata('nama') ?></td> </tr>
        <tr><td align="right" colspan="4"></td> </tr>
    </table>
</body>