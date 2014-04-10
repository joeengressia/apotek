<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    $('#awal,#akhir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#search').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        get_result_penerimaan();
    });
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('input[type=text],input[type=hidden]').val('');
        $('#awal,#akhir').val('<?= date("d/m/Y") ?>');
        $('#result-info').html('');
    });
    $('#cetak').button({
        icons: {
            secondary: 'ui-icon-print'
        }
    }).click(function() {
        var awal        = $('#awal').val();
        var akhir       = $('#akhir').val();
        var supplier    = $('#id_supplier').val();
        var faktur      = $('#faktur').val();
        var wWidth = $(window).width();
        var dWidth = wWidth * 1;
        var wHeight= $(window).height();
        var dHeight= wHeight * 1;
        var x = screen.width/2 - dWidth/2;
        var y = screen.height/2 - dHeight/2;
        window.open('<?= base_url('inventory/manage_laporan_pemesanan/cetak') ?>?awal='+awal+'&akhir='+akhir+'&id_supplier='+supplier+'&faktur='+faktur, 'Penerimaan', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
    });
    var lebar = $('#supplier').width();
    $('#supplier').autocomplete("<?= base_url('autocomplete/supplier') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_supplier').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'<br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_supplier').val(data.id);
    });
    $('#faktur').autocomplete("<?= base_url('autocomplete/faktur') ?>",
    {
        parse: function(data){
            var parsed = [];
            for (var i=0; i < data.length; i++) {
                parsed[i] = {
                    data: data[i],
                    value: data[i].nama // nama field yang dicari
                };
            }
            $('#id_supplier').val('');
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.faktur+' - '+data.supplier+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.faktur);
        $('#id_supplier').val(data.id_supplier);
        $('#supplier').val(data.supplier);
    });
});
function paging(page) {
    get_result_penerimaan(page);
}
function get_result_penerimaan(page) {
    var pg   = (page === undefined)?'':page;
    $.ajax({
        url: '<?= base_url('inventory/manage_laporan_pemesanan/list') ?>/'+pg,
        data: $('#form').serialize(),
        cache: false,
        type: 'GET',
        success: function(data) {
            $('#result-info').html(data);
        }
    });
}
</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <?= form_open('', 'id=form') ?>
            <table width="100%" class="inputan">
                <tr><td width="10%">Range Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal size=10') ?> s.d <?= form_input('akhir', date("d/m/Y"), 'id=akhir size=10') ?></td></tr>
                <tr><td>Nama Supplier:</td><td><?= form_input('supplier', NULL, 'id=supplier size=40') ?><?= form_hidden('id_supplier', NULL, 'id=id_supplier') ?></td></tr>
                <tr><td></td><td><?= form_button('','Tampilkan', 'id=search') ?> <?= form_button('','Reset', 'id=reset') ?> <?= form_button('','Cetak', 'id=cetak') ?></td></tr>
            </table>
            <?= form_close() ?>
            <div id="result-info"></div>
        </div>
    </div>
</div>