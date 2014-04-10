<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<script type="text/javascript">
$(function() {
    get_list_data_barang();
    $('#tabs').tabs();
    $('#simpan').button({
        icons: {
            secondary: 'ui-icon-circle-check'
        }
    });
});

function get_list_data_barang(page, search, id) {
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: '<?= base_url('inventory/manage_koreksi_stok/list') ?>/'+page,
        data: 'key='+$('#search').val(),
        success: function(data) {
            $('#result').html(data);
        }
    });
}

function paging(page, tab, search) {
    get_list_data_barang(page, search);
}

</script>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Koreksi Stok</a></li>
        </ul>
        <div id="tabs-1">
            <?= form_input('search', NULL, 'id=search placeholder="Search ..." class=search onkeyup="get_list_data_barang();"') ?>
            <div id="result"></div>
            
        </div>
    </div>
</div>