<?php $this->load->view('message') ?>
<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <script type="text/javascript">
        $(function() {
            $('#tabs').tabs();
            $('button[id=reset]').button({
                icons: {
                    secondary: 'ui-icon-refresh'
                }
            });
            $('button[id=search]').button({
                icons: {
                    secondary: 'ui-icon-circle-check'
                }
            });
            $('button[id=cetak]').button({
                icons: {
                    secondary: 'ui-icon-print'
                }
            });
            $('#reset').click(function() {
                var url = '<?= base_url('laporan/kas') ?>';
                $('#loaddata').load(url);
            })
            $('#cetak').hide();
            $('#cetak').click(function() {
                var awal = $('#awal').val();
                var akhir = $('#akhir').val();
                var jenis = $('#jenis').val();
                var nama = $('#nama').val();
                location.href='<?= base_url('laporan/kas_load_data') ?>?awal='+awal+'&akhir='+akhir+'&jenis='+jenis+'&nama='+nama+'&do=cetak';
            });
            $('#awal,#akhir').datepicker({
                changeYear: true,
                changeMonth: true
            });
            $('#jenis').focus();
            $('#search').click(function() {
                var awal = $('#awal').val();
                var akhir= $('#akhir').val();
                var jenis= $('#jenis').val();
                var nama = $('#nama').val();
                $.ajax({
                    url: '<?= base_url('laporan/kas_load_data') ?>',
                    data: 'awal='+awal+'&akhir='+akhir+'&jenis='+jenis+'&nama='+nama,
                    cache: false,
                    success: function(data) {
                        $('#result').html(data);
                        $('#cetak').fadeIn();
                    }
                });
            });
            $('input[name=pegawai]').autocomplete("<?= base_url('common/autocomplete?opsi=pegawai') ?>",
            {
                parse: function(data){
                    var parsed = [];
                    for (var i=0; i < data.length; i++) {
                        parsed[i] = {
                            data: data[i],
                            value: data[i].nama // nama field yang dicari
                        };
                    }
                    return parsed;
                },
                formatItem: function(data,i,max){
                    var kelurahan = data.kelurahan;
                    if (data.kelurahan != 'null') {
                        var kelurahan = '-';
                    }
                    var str = '<div class=result>'+data.nama+'<br/>'+data.alamat+'</div>';
                    return str;
                },
                width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
                dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
            }).result(
            function(event,data,formated){
                $(this).attr('value',data.nama);
                $('#id_pegawai').val(data.penduduk_id);
            });
        })
    </script>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <table width="100%" class="inputan">
                <tr><td>Tanggal</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> <span class="label">s . d </span><?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?>
                <tr><td>Jenis Transaksi</td><td><?= form_dropdown('jenis', $jenis_transaksi, null, 'id=jenis') ?>
                <tr><td></td><td><?= form_button(null, 'Cari', 'id=search') ?> 
                <?= form_button('Reset', 'Reset', 'id=reset') ?> 
                <?= form_button(null, 'Cetak Excel', 'id=cetak') ?> 
            </table>
            <div id="result">
            <table class="list-data" width="100%">
                <tr>
                    <th>No.</th>
                    <th>Waktu</th>
                    <th>ID</th>
                    <th>Jenis Transaksi</th>
                    <th>Penerimaan</th>
                    <th>Pengeluaran</th>
                    <th>Akhir</th>
                </tr>
                <?php for ($i = 1; $i <= 2; $i++) { ?>
                    <tr class="<?= ($i % 2 == 0) ? 'even' : 'odd' ?>">
                        <td align="center">&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                    </tr>
                <?php }
                ?>
            </table>
            </div>
        </div>
        <!--<?= link_href('inventory/inkaso/', '<u>Inkaso</u>', null) ?> | 
        <?= link_href('inventory/retur-pembelian/', '<u>Retur Pembelian</u>', null) ?> |
        <?= link_href('transaksi/pp-uang/', '<u>Penerimaan dan Pengeluaran Uang</u>', null) ?>-->
    </div>
</div>
