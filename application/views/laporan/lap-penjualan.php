<title><?= $title ?></title>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    $('#search').button({
        icons: {
            secondary: 'ui-icon-circle-check'
        }
    }).click(function() {
        
    });
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('input[type=text]').val('');
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#result-info').html('');
    });
    $('#cetak').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        
    });
    
    $('#search-nr').button({
        icons: {
            secondary: 'ui-icon-circle-check'
        }
    }).click(function() {
        load_data_penjualan_nr();
    });
    $('#reset-nr').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('input[type=text]').val('');
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#result-info2').html('');
    });
    $('#cetak-nr').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        
    });
    
    $('#awal,#akhir,#awalan,#akhiran').datepicker({
        changeYear: true,
        changeMonth: true
    });
    var lebar = $('#pasien').width();
    $('#pasien').autocomplete("<?= base_url('autocomplete/pasien') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_pasien').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.no_rm+' '+data.nama+'<br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_pasien').val(data.id);
        $('#keterangan').focus().select();
    });
    $('#dokter').autocomplete("<?= base_url('autocomplete/dokter') ?>",
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
            var str = '<div class=result>'+data.nama+'<br/> '+data.str_no+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_dokter').val(data.id);
    });
    $('#search').click(function() {
        load_data_penjualan();
    });
});

function cetak() {
    var awal    = $('#awal').val();
    var akhir   = $('#akhir').val();
    var pasien  = $('#id_pasien').val();
    var dokter  = $('#id_dokter').val();
    var status  = $('input:checked').val();
    var wWidth = $(window).width();
    var dWidth = wWidth * 0.9;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('pages/penjualan-print.php?awal='+awal+'&akhir='+akhir+'&pasien='+pasien+'&dokter='+dokter+'&status='+status, 'cetak penjualan', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}
function load_data_penjualan(page) {
    $.ajax({
        url: '<?= base_url('pelayanan/manage_laporan_penjualan/listresep') ?>/'+page,
        data: $('#jualresep').serialize(),
        success: function(data) {
            $('#result-info').html(data);
        }
    });
}
function load_data_penjualan_nr(page) {
    $.ajax({
        url: '<?= base_url('pelayanan/manage_laporan_penjualan/listnonresep') ?>/'+page,
        data: $('#jualnonresep').serialize(),
        success: function(data) {
            $('#result-info2').html(data);
        }
    });
}
</script>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Resep</a></li>
            <li><a href="#tabs-2">Non Resep</a></li>
        </ul>
        <div id="tabs-1">
            <?= form_open('', 'id=jualresep') ?>
            <table width="100%" class="inputan">
                <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
                <tr><td>Nama Pasien:</td><td><?= form_input('pasien', NULL, 'id=pasien size=40') ?><?= form_hidden('id_pasien', NULL, 'id=id_pasien') ?></td></tr>  
                <tr><td>Dokter:</td><td><?= form_input('dokter', NULL, 'id=dokter size=40') ?><?= form_hidden('id_dokter', NULL, 'id=id_dokter') ?></td></tr>
                <tr><td></td><td><?= form_button('','Tampilkan', 'id=search') ?> <?= form_button('','Reset', 'id=reset') ?></td></tr>
            </table>
            <?= form_close() ?>
            <div id="result-info"></div>
        </div>
        <div id="tabs-2">
            <?= form_open('', 'id=jualnonresep') ?>
            <table width="100%" class="inputan">
                <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awalan size=10 style="width: 100px;"') ?> s . d <?= form_input('akhir', date("d/m/Y"), 'id=akhiran size=10 style="width: 100px;"') ?></td></tr>
                <tr><td></td><td><?= form_button('','Tampilkan', 'id=search-nr') ?> <?= form_button('','Reset', 'id=reset-nr') ?> <?= form_button('','Cetak', 'id=cetak-nr onclick=cetak_jual_nr();') ?></td></tr>
            </table>
            <?= form_close() ?>
            <div id="result-info2"></div>
        </div>
    </div>
</div>