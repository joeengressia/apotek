<script type="text/javascript">
    function cetak() {
        setTimeout(function(){ window.close();},300);
        window.print();    
    }
</script>
<link rel="stylesheet" href="<?= base_url('assets/css/print-A4-landscape.css') ?>" media="print" />
<style type="text/css">
    .list-data { border-spacing: 0; }
    .list-data th,.list-data td { border-right: 1px solid #000; border-bottom: 1px solid #000; height: 20px; }
</style>
<body style="height: 27cm;" onload="cetak();">
    <table width="100%" style="color: #000; border-bottom: 1px solid #000;">
        <tr>
            <td rowspan="3" style="width: 70px"><img src="<?= base_url('assets/images/company/' . $apt->logo_file_nama) ?>" width="70px" height="70px" /></td>    
            <td colspan="3" align="center"><b><?= strtoupper($apt->nama) ?></b></td> <td rowspan="3" style="width: 70px">&nbsp;</td>
        </tr>
        <tr><td colspan="3" align="center"><b><?= strtoupper($apt->alamat) ?> <?= strtoupper($apt->kelurahan) ?></b></td> </tr>
        <tr><td colspan="3" align="center"><b>TELP. <?= $apt->telp ?>,  FAX. <?= $apt->fax ?>, EMAIL <?= $apt->email ?></b></td> </tr>
    </table>
<table class="list-data" width="100%">
    <tr>
        <th width="5%">ID. Kunj.</th>
        <th width="7%">No. RM</th>
        <th width="20%">Nakes</th>
        <th width="10%">Waktu</th>
        <th width="40%">Nama Tarif</th>
        <th width="5%">Nominal</th>
        <th width="5%">Freq</th>
        <th width="5%">Sub Total</th>
    </tr>
    <?php if (isset($_GET['awal'])) {
        $total = 0;
        $no = "";
        foreach ($list_data as $key => $data) {?>
    <tr class="<?= ($key%2==1)?'even':'odd' ?>">
        <td align="center"><?= ($no !== $data->no_daftar)?$data->no_daftar:NULL ?></td>
        <td align="center"><?= $data->no_rm ?></td>
        <td><?= $data->pegawai ?></td>
        <td align="center"><?= datetime($data->waktu) ?></td>
        <td><?= $data->layanan ?></td>
        <td align="right"><?= rupiah($data->jasa_nakes) ?></td>
        <td align="center"><?= $data->frekuensi ?></td>
        <td align="right"><?= rupiah($data->jasa_nakes*$data->frekuensi) ?></td>
    </tr>
    <?php 
        $no = $data->no_daftar;
        $total = $total + ($data->jasa_nakes*$data->frekuensi);
        } ?>
    <tr>
        <td colspan="7" align="right"><b>Total Jasa Nakes<b/></td><td align="right"><b><?= rupiah($total) ?></b></td>
    </tr>
    <?php
    } else { ?>
    <?php for($i = 0; $i <= 1; $i++)  { ?>
        <tr class="<?= ($i%2==1)?'even':'odd' ?>">
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php } 
    }?>
</table>
</body>