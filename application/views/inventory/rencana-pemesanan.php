<script type="text/javascript">
    $(function() {
        $.cookie('session', 'false');
        $.cookie('formbayar', 'false');
        $(document).keydown(function(e) {
            if (e.keyCode === 120) {
                if ($.cookie('session') === 'false') {
                    $('#button').click();
                }
            }
        });
        load_data_rencana_pemesanan();
        $('#button-rencana').button({
            icons: {
                secondary: 'ui-icon-newwin'
            }
        }).click(function() {
            form_add_rencana_pemesanan();
        });
        $('#reset-rencana').button({
            icons: {
                secondary: 'ui-icon-refresh'
            }
        }).click(function() {
            load_data_rencana_pemesanan();
        });

    });
function form_add_rencana_pemesanan_supplier() {
var str = '<div id=form_add_rencana_pemesanan>'+
            '<form action="" method=post id="save_barang">'+
            '<?= form_hidden('id_supplier', NULL, 'id=id_supplier') ?>'+
            '<table width=100% class=data-input>'+
                '<tr><td width=40%>Nama Supplier:</td><td><?= form_input('nama', NULL, 'id=nama size=40') ?></td></tr>'+
                '<tr><td>Alamat:</td><td><?= form_input('alamat', NULL, 'id=alamat size=40') ?></td></tr>'+
                '<tr><td width=40%>Email:</td><td><?= form_input('email', NULL, 'id=email size=40') ?></td></tr>'+
                '<tr><td>No. Telp:</td><td><?= form_input('telp', NULL, 'id=telp size=40') ?></td></tr>'+
            '</table>'+
            '</form>'+
            '</div>';
    $('body').append(str);
    $('#form_add_rencana_pemesanan').dialog({
        title: 'Tambah Supplier',
        autoOpen: true,
        width: 480,
        height: 220,
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
        var cek_id = $('#id_supplier').val();
        $.ajax({
            url: 'models/update-masterdata.php?method=save_supplier',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    if (cek_id === '') {
                        $('#form_add_rencana_pemesanan').dialog('close');
                        $('#id_supplier').val(data.id_supplier);
                        $('#supplier').val(data.nama);
                        $('#barang').focus();
                    }
                }
            }
        });
        return false;
    });
}

function removeMe(el) {
    var parent = el.parentNode.parentNode;
    parent.parentNode.removeChild(parent);
    hitung_estimasi_rencana();
}

function hitung_detail_total(jml, jum, diskon_rupiah, diskon_persen, harga_jual, isi_satuan) {
    if (diskon_persen === undefined) {
        dp = '0';
    } else {
        dp = diskon_persen;
    }
    
    if (diskon_rupiah === undefined) {
        dr = '0';
    } else {
        dr = diskon_rupiah;
    }
    $('#hargajual'+jml).html(numberToCurrency(harga_jual));
    $('#harga_jual'+jml).val(parseInt(harga_jual));
    //alert(jum+' '+harga_jual+' '+isi_satuan);
    var subtotal = (jum*harga_jual*isi_satuan);
    if (isNaN(subtotal)) {
        subtotal = 0;
    }
    $('#subtotal'+jml).html(numberToCurrency(Math.ceil(subtotal)));
    //hitung_total_penjualan();
    hitung_estimasi_rencana();
}

function hitung_estimasi_rencana() {
    var jml_baris = $('.tr_rows').length;
    var estimasi = 0;
    for (i = 1; i <= jml_baris; i++) {
        var subtotal = parseInt(currencyToNumber($('#subtotal'+i).html()));
        if (isNaN(subtotal)) {
            subtotal = 0;
        }
        estimasi = estimasi + subtotal;
    }
    $('#estimasi').html(numberToCurrency(parseInt(estimasi)));
}
function add_new_rows(id_brg, nama_brg, jumlah, id_kemasan) {
    if (id_kemasan === null) {
        alert('Kemasan tidak boleh kosong !');
        return false;
    }
    var jml     = $('.tr_rows').length+1;
    var kemasan = $('#kemasan option:selected').text();
    var str = '<tr class="tr_rows">'+
                '<td align=center>'+jml+'</td>'+
                '<td>&nbsp;'+nama_brg+' <input type=hidden name=id_barang[] value="'+id_brg+'" class=id_barang id=id_barang'+jml+' /></td>'+
                '<td align=center>'+kemasan+'<input type=hidden name=kemasan[] id=kemasan'+jml+' value="'+id_kemasan+'" /></td>'+
                '<td><input type=text name=jumlah[] id=jumlah'+jml+' value="'+jumlah+'" size=10 style="text-align: center;" /></td>'+
                '<td align=right id=subtotal'+jml+'></td>'+
                '<td align=center><input type=hidden id=perundangan'+jml+' /><img onclick=removeMe(this); title="Klik untuk hapus" src="img/icons/delete.png" class=add_kemasan align=left /></td>'+
              '</tr>';
    $('#rencana-pesanan-list tbody').append(str);
    $.ajax({
        url: 'models/autocomplete.php?method=get_detail_harga_barang_pemesanan&id='+id_brg+'&id_kemasan='+id_kemasan,
        dataType: 'json',
        cache: false,
        success: function(data) {
            var subtotal = data.esti*jumlah;
            //alert(subtotal+' '+data.esti+' '+jumlah);
            $('#subtotal'+jml).html(numberToCurrency(parseInt(subtotal)));
            $('#perundangan'+jml).val(data.perundangan);
            hitung_estimasi_rencana();
        }
    });
}

function cetak_sp_rencana(id_pemesanan) {
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    var x = screen.width/2 - dWidth/2;
    var y = screen.height/2 - dHeight/2;
    window.open('<?= base_url('inventory/cetak_sp') ?>?id='+id_pemesanan+'&perundangan='+perundangan, 'Pemesanan Cetak', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
}

function form_add_rencana_pemesanan() {
    var str = '<div id="form_rencana_pemesanan">'+
            '<form id="save_rencana_pemesanan">'+
            '<table width=100% class=data-input><tr valign=top><td width=50%><table width=100%>'+
                '<tr><td width=15%>No. SP:</td><td width=30%><?= form_input('no_sp', NULL, 'id=no_sp size=10 readonly') ?></td></tr>'+
                '<tr><td>Tanggal Pembuatan SP:</td><td><?= form_input('tanggal', date("d/m/Y"), 'id=tanggal size=10') ?></td></tr>'+
                '<tr><td>Tanggal Diharapkan Datang:</td><td><?= form_input('tanggal_datang', date("d/m/Y"), 'id=tanggal_datang size=10') ?></td></tr>'+
                '<tr><td>Supplier:</td><td width=20%><?= form_input('supplier', NULL, 'id=supplier size=40') ?><?= form_hidden('id_supplier', NULL, 'id=id_supplier') ?> <a class="addition" onclick="form_add_rencana_pemesanan_supplier();" title="Klik untuk tambah supplier, jika supplier belum ada">&nbsp;</a></td></tr>'+
            '</table></td><td width=50%>'+
                '<table><tr><td width=20%>Nama Barang:</td><td width=50%><?= form_input('barang', NULL, 'id=barang size=40') ?><?= form_hidden('id_barang', NULL, 'id=id_barang') ?></td></tr>'+
                '<tr><td>Kemasan:</td><td><select name=id_kemasan id=kemasan style="min-width: 86px;"><option value="">Pilih ...</option><?php foreach ($kemasan as $data) { echo '<option value="'.$data->id.'">'.$data->nama.'</option>'; } ?></select></td></tr>'+
                '<tr><td>Jumlah:</td><td><?= form_input('jumlah', NULL, 'id=jumlah size=10') ?></td></tr>'+
                '<tr><td>Estimasi Harga:</td><td><span>Rp</span> <span id=estimasi style="font-size: 40px;">0</span>, 00</td></tr>'+
            '</table>'+
            '</td></tr></table>'+
            '<table width=100% cellspacing="0" class="list-data-input" id="rencana-pesanan-list"><thead>'+
                '<tr><th width=5%>No.</th><th width=54%>Nama Barang</th><th width=20%>Kemasan</th><th width=10%>Jumlah</th><th width=10%>Harga</th><th width=1%>#</th></tr></thead>'+
                '<tbody></tbody>'+
            '</table>'+
            '</form></div>';
    $('body').append(str);
    $(document).keydown(function(e) {
        if (e.keyCode === 119) {
            $('#save_rencana_pemesanan').submit();
        }
    });
    var lebar = $('#pabrik').width();
    $('#jumlah').keydown(function(e) {
        if (e.keyCode === 13) {
            add_new_rows($('#id_barang').val(), $('#barang').val(), $('#jumlah').val(), $('#kemasan').val());
            $('#id_barang').val('');
            $('#jumlah').val('');
            $('#kemasan').html('').append('<option value="">Pilih ...</option>');
            $('#barang').val('').focus();
            
        }
    });
    $('#barang').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#kemasan').focus();
            //$('#jumlah').val('1').focus().select();
        }
    });
    $('#kemasan').keydown(function(e) {
        if (e.keyCode === 13) {
            //$('#kemasan').focus();
            $('#jumlah').val('1').focus().select();
        }
    });
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
        dataType: 'json', // tipe data yang diterima oleh library ini disetup sebagai JSON
        cacheLength: 0
    }).result(
    function(event,data,formated){
        $(this).val(data.nama_barang);
        $('#id_barang').val(data.id);
        $('#kemasan').html('');
        $.getJSON('<?= base_url('inventory/get_kemasan_barang') ?>?id='+data.id, function(data){
            if (data === null) {
                alert('Kemasan tidak barang tidak tersedia !');
            } else {
                $.each(data, function (index, value) {
                    $('#kemasan').append("<option value='"+value.id_kemasan+"'>"+value.nama+"</option>");
                });
            }
        });
    });
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
            return parsed;
        },
        formatItem: function(data,i,max){
            var str = '<div class=result>'+data.nama+'<br/> '+data.alamat+'</div>';
            return str;
        },
        width: lebar, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
        dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
    }).result(
    function(event,data,formated){
        $(this).val(data.nama);
        $('#id_supplier').val(data.id);
        $('#barang').focus();
    });
    $('#tanggal').datepicker({
        onSelect: function() {
            var tanggal = $(this).val();
            $.ajax({
                url: '<?= base_url('autocomplete/generate_new_sp') ?>',
                dataType: 'json',
                data: 'tanggal='+tanggal,
                success: function(data) {
                    $('#no_sp').val(data.sp);
                }
            });
        }
    });
    $('#tanggal_datang').datepicker({
        changeYear: true,
        changeMonth: true
    });
    var wWidth = $(window).width();
    var dWidth = wWidth * 1;
    
    var wHeight= $(window).height();
    var dHeight= wHeight * 1;
    $('#form_rencana_pemesanan').dialog({
        title: 'Tambah Pemesanan Barang',
        autoOpen: true,
        modal: true,
        width: dWidth,
        height: dHeight,
        hide: 'clip',
        show: 'blind',
        buttons: {
            "Simpan (F8)": function() {
                $('#save_rencana_pemesanan').submit();
            }, 
            "Cancel": function() {    
                $(this).dialog().remove();
                $.cookie('session', 'false');
            }
        }, close: function() {
            $(this).dialog('close').remove();
            $.cookie('session', 'false');
        }, open: function() {
            $('#supplier').focus();
            $.cookie('session', 'true');
            $.ajax({
                url: '<?= base_url('autocomplete/generate_new_sp') ?>',
                dataType: 'json',
                data: 'tanggal='+$('#tanggal').val(),
                success: function(data) {
                    $('#no_sp').val(data.sp);
                }
            });
            $.ajax({
                url: '<?= base_url('inventory/edit_rencana_pemesanan') ?>',
                cache: false,
                success: function(msg) {
                    $('#rencana-pesanan-list tbody').html(msg);
                }
            });
        }
    });
    $('#save_rencana_pemesanan').submit(function() {
        
        var jumlah = $('.tr_rows').length;
        if ($('#id_supplier').val() === '') {
            alert('Nama supplier tidak boleh kosong !');
            $('#supplier').focus(); return false;
        }
        if (jumlah === 0) {
            alert('Pilih salah satu barang!');
            $('#barang').focus(); return false;
        }
        $.ajax({
            url: '<?= base_url('inventory/manage_rencana_pemesanan/save') ?>',
            data: $(this).serialize(),
            dataType: 'json',
            type: 'POST',
            success: function(data) {
                if (data.status === true) {
                    alert_refresh('Pemesanan berhasil dilakukan');
                    $('#supplier, #id_supplier').val('');
                    $('#no_sp').val(data.id_pemesanan);
                    $('#rencana-pesanan-list tbody').html('');
                    $('#estimasi').html('0');
                    load_data_rencana_pemesanan();
                    cetak_sp_rencana(data.id);
                } else {
                    alert_edit();
                }
            }
            
        });
        return false;
    });
}

function load_data_rencana_pemesanan(page, search, id) {
    pg = page; src = search; id_barg = id;
    if (page === undefined) { var pg = ''; }
    if (search === undefined) { var src = ''; }
    if (id === undefined) { var id_barg = ''; }
    $.ajax({
        url: '<?= base_url('inventory/manage_rencana_pemesanan') ?>/list/'+page,
        cache: false,
        data: 'search='+src,
        success: function(data) {
            $('#result-rencana-pemesanan').html(data);
        }
    });
}
function delete_pemesanan_plant(id, page) {
    $('<div id=alert>Anda yakin akan menghapus data ini?</div>').dialog({
        title: 'Konfirmasi Penghapusan',
        autoOpen: true,
        modal: true,
        buttons: {
            "OK": function() {
                
                $.ajax({
                    url: 'models/update-transaksi.php?method=delete_pemesanan_plant&id='+id,
                    cache: false,
                    success: function() {
                        load_data_rencana_pemesanan(page);
                        $('#alert').dialog().remove();
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
<button id="button-rencana"> Tambah (F9)</button>
<button id="reset-rencana">Reset</button>
<div id="result-rencana-pemesanan"></div>
