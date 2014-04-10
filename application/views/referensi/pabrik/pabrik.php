<?= $this->load->view('message') ?>
<script type="text/javascript">
$(function() {
    load_data_pabrik(1);
    $('#tabs').tabs();
    $('#search').keyup(function() {
        var value = $(this).val();
        load_data_pabrik(1,value,'');
    });
});
function form_add() {
var str = '<div id=form_add>'+
            '<form action="" method=post id="save_barang">'+
            '<?= form_hidden('id_pabrik', NULL, 'id=id_pabrik') ?>'+
            '<table width=100% class=data-input>'+
                '<tr><td width=40%>Nama Supplier:</td><td><?= form_input('nama', NULL, 'id=nama size=40') ?></td></tr>'+
                '<tr><td>Alamat:</td><td><?= form_input('alamat', NULL, 'id=alamat size=40') ?></td></tr>'+
                '<tr><td width=40%>Email:</td><td><?= form_input('email', NULL, 'id=email size=40') ?></td></tr>'+
                '<tr><td>No. Telp:</td><td><?= form_input('telp', NULL, 'id=telp size=40') ?></td></tr>'+
            '</table>'+
            '</form>'+
            '</div>';
    $('body').append(str);
    $('#form_add').dialog({
        title: 'Tambah Supplier',
        autoOpen: true,
        width: 480,
        height: 240,
        modal: true,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan": function() {
                $('#save_barang').submit();
            }, "Cancel": function() {
                $(this).dialog('close').remove();
            }
        }, close: function() {
            $(this).dialog('close').remove();
        }
    });
    
    
    $('#save_barang').submit(function() {
        if ($('#nama').val() === '') {
            alert('Nama barang tidak boleh kosong !');
            $('#nama').focus(); return false;
        }
        var cek_id = $('#id_pabrik').val();
        $.ajax({
            url: '<?= base_url('referensi/manage_pabrik/save') ?>',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        alert_tambah();
                        $('input').val('');
                        load_data_pabrik('1','',data.id_pabrik);
                    } else {
                        alert_edit();
                        $('#form_add').dialog('close').remove();
                        load_data_pabrik('1','',data.id_pabrik);
                    }
                    
                }
            }
        });
        return false;
    });
}
$('#button').button({
    icons: {
        primary: 'ui-icon-newwin'
    }
});
$('#button').click(function() {
    form_add();
});
$('#reset').button({
    icons: {
        primary: 'ui-icon-refresh'
    }
}).click(function() {
    load_data_pabrik(1);
    $('#search').val('');
});
function load_data_pabrik(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_primary = ''; }
    $.ajax({
        url: '<?= base_url('referensi/manage_pabrik/list') ?>/'+pg,
        cache: false,
        data: 'key='+src+'&id='+id_primary,
        success: function(data) {
            $('#result-pabrik').html(data);
        }
    });
}

function paging(page, tab, search) {
    load_data_pabrik(page, search);
}

function edit_pabrik(str) {
    var arr = str.split('#');
    form_add();
    $('#form_add').dialog({ title: 'Edit Supplier' });
    $('#id_pabrik').val(arr[0]);
    $('#nama').val(arr[1]);
    $('#alamat').val(arr[2]);
    $('#email').val(arr[3]);
    $('#telp').val(arr[4]);
}

function delete_pabrik(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: '<?= base_url('referensi/manage_pabrik/delete') ?>?id='+id,
                    cache: false,
                    success: function() {
                        load_data_pabrik(page);
                        $('#alert').dialog('close').remove();
                    }
                });
            },
            "Cancel": function() {
                $(this).dialog('close').remove();
            }
        }
    });
}
</script>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Data Utama</a></li>
        </ul>
        <div id="tabs-1">
            <button id="button">Tambah Data</button>
            <button id="reset">Reset</button>
            <?= form_input('search', NULL, 'id=search placeholder="Search ..." class=search') ?>
            <div id="result-pabrik">

            </div>
        </div>
    </div>
</div>