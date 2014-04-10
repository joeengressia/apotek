<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
$(function() {
    $('#tabs').tabs();
    get_data_list();
    $('#search').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    }).click(function() {
        get_data_list();
    });
    $('select').change(function() {
        var value = $(this).val();
        if (value === 'History') {
            $('#awal, #akhir').val('<?= date("d/m/Y") ?>').removeAttr('disabled');
        } else {
            $('#awal, #akhir').val('').attr('disabled','disabled');
        }
    });
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#loaddata').load('<?= base_url('inventory/stok') ?>');
    });
    $('#awal, #akhir').datepicker();
    var lebar = $('#barang').width();
    $('#barang').autocomplete("<?= base_url('autocomplete/barang') ?>",
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
            var str = '<div class=result>'+data.nama_barang+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_barang').val(data.id);
    });
});

function get_data_list() {
    $.ajax({
        url: '<?= base_url('inventory/stok_load') ?>',
        data: $('#form').serialize(),
        type: 'POST',
        success: function(data) {
            $('#result').html(data);
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
            <table class="inputan" width="100%">
                <tr><td>Jenis Laporan:</td><td><?= form_dropdown('jenis', array('History' => 'History', 'Sisa' => 'Sisa')) ?></td></tr>
                <tr><td>Tanggal:</td><td><?= form_input('awal', date("d/m/Y"), 'id=awal') ?> s.d <?= form_input('akhir', date("d/m/Y"), 'id=akhir') ?></td></tr>
                <tr><td>Nama Barang:</td><td><?= form_input('', NULL, 'id=barang') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>
                <tr><td></td><td><?= form_button(NULL, 'Tampilkan', 'id=search') ?> <?= form_button(NULL, 'Reset', 'id=reset') ?></td></tr>
            </table>
            <?= form_close() ?>
            <div id="result"></div>
        </div>
    </div>
</div>