<script type="text/javascript">
    $(function() {
        $('.double-scroll').doubleScroll();
        $('#tabs').tabs();
        $('#reset').click(function() {
            var url = '<?= base_url('laporan/rekap_resep') ?>';
            $('#loaddata').empty().load(url);
        });
        $('#reset').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        });
        $('#search').button({
            icons: {
                secondary: 'ui-icon-circle-check'
            }
        }).click(function() {
            $('#forminforesep').submit();
        });
        $('.tanggal').datepicker({
            changeYear: true,
            changeMonth: true
        });
        $('#forminforesep').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?= base_url('laporan/rekap_resep_load') ?>',
                type: 'GET',
                data: $(this).serialize(),
                success: function(data) {
                    $('#result').html(data);
                }
            });
            
        });
        $('#csr').click(function() {
            var awal = $('#awal').val();
            var akhir= $('#akhir').val();
            var hambatan = $('#hambatan').val();
            var url = '<?= base_url('laporan/statistika_resep') ?>?awal='+awal+'&akhir='+akhir;
            $.get(url, function(data) {
                $('#result_detail').html(data);
                $('#result_detail').dialog({
                    autoOpen: true,
                    height: 500,
                    width: 900,
                    modal: true
                });
            });
            return false;
            //window.open('<?= base_url('laporan/statistika_resep') ?>?awal='+awal+'&akhir='+akhir+'&hambatan='+hambatan,'mywindow','location=1,status=1,scrollbars=1,width=840.48px,height=500px');
        });
        $('#closehambatan').click(function() {
            $('.csr').fadeOut('fast');
        });
        $('#pmr_open').click(function() {
            var pasien = $('input[name=id_pasien]').val();
            var nama   = $('input[name=pasien]').val();
            if (pasien === '') {
                custom_message('Peringatan','Silahkan isikan data pasien terlebih dahulu!');
                $('#pasien').focus();
            } else {
                location.href='<?= base_url('pelayanan/cetak_pmr') ?>?id_pasien='+pasien+'&nama='+nama;
            }
        });
        $('.noresep').click(function() {
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#result_detail').html(data);
                $('#result_detail').dialog({
                    autoOpen: true,
                    height: 500,
                    width: 900,
                    modal: true
                });
            });
            return false;
        });
        $('.salinresep').click(function() {
            var url = $(this).attr('href');
            $('#loaddata').load(url+'?_'+Math.random());
            return false;
        });
        $('#apoteker').autocomplete("<?= base_url('inv_autocomplete/load_data_penduduk_apoteker') ?>",
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
                var str = '<div class=result>'+data.nama+' - '+data.sip_no+'</div>';
                return str;
            },
            width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
        }).result(
        function(event,data,formated){
            $(this).val(data.nama+' - '+data.sip_no);
            $('input[name=id_apoteker]').val(data.id);
            
        });
        $('#pasien').autocomplete("<?= base_url('inv_autocomplete/load_data_penduduk_pasien') ?>",
        {
            parse: function(data){
                var parsed = [];
                for (var i=0; i < data.length; i++) {
                    parsed[i] = {
                        data: data[i],
                        value: data[i].nama // nama field yang dicari
                    };
                }
                $('input[name=id_pasien]').val('');
                return parsed;
            },
            formatItem: function(data,i,max){
                var str = '<div class=result>'+data.nama+' - '+data.no_rm+' <br/> '+data.alamat+'</div>';
                return str;
            },
            width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
            cacheLength: 0
        }).result(
        function(event,data,formated){
            $(this).val(data.nama+' - '+data.no_rm);
            $('input[name=id_pasien]').val(data.penduduk_id);
            
        });
        $('#dokter').autocomplete("<?= base_url('inv_autocomplete/load_data_penduduk_dokter') ?>",
        {
            parse: function(data){
                var parsed = [];
                for (var i=0; i < data.length; i++) {
                    parsed[i] = {
                        data: data[i],
                        value: data[i].nama // nama field yang dicari
                    };
                }
                $('#id_dokter').val('');
                return parsed;
            },
            formatItem: function(data,i,max){
                var str = '<div class=result>'+data.nama+' - '+data.kerja_izin_surat_no+'</div>';
                return str;
            },
            width: 320, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
            dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
        }).result(
        function(event,data,formated){
            $(this).val(data.nama+' - '+data.kerja_izin_surat_no);
            $('input[name=id_dokter]').val(data.penduduk_id);
        });
        $('#awal, #akhir').datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
function cetak_copy_resep(id_resep) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.3;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('pelayanan/manage_resep/cetak_copy') ?>?id='+id_resep,'Resep Cetak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}
</script>
<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<div id="result_detail" style="display: none"></div>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
        <?= form_open('laporan/rekap_resep', 'id=forminforesep') ?>
        <table width="100%" class="inputan">
                <tr><td>Range Tanggal:</td><td> <?= form_input('awal',date("d/m/Y"),'size=10 id=awal') ?> s.d <?= form_input('akhir',date("d/m/Y"),'size=10 id=akhir') ?>
                <tr><td>Nama Pasien:</td><td><?= form_input('pasien',NULL,'size=40 id=pasien') ?> <?= form_hidden('id_pasien', NULL, 'id=id_pasien') ?>
                <tr><td>Nama Dokter:</td><td><?= form_input('dokter', NULL, 'id=dokter') ?><?= form_hidden('id_dokter', NULL, 'id=id_dokter') ?></td></tr>
                <tr><td></td><td>
                <?= form_button(null, 'Cari', 'id=search') ?> 
                <?= form_button('Reset', 'Reset','id=reset') ?>
        </table>
        <?= form_close() ?>
        <div id="result"></div>
        </div>
    </div>
</div>
