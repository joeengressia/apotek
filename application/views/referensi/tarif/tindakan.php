<?php $this->load->view('message') ?>
<title><?= $title ?></title>
<div class="kegiatan">
    <script type="text/javascript">
        var request;
        $(function() {        
            $('input[type=submit]').each(function(){ $(this).replaceWith('<button type="' + $(this).attr('type') + '" name="'+$(this).attr('name')+'" id="'+$(this).attr('id')+'">' + $(this).val() + '</button>');});
            $('button[type=submit]').button({icons: {secondary: 'ui-icon-circle-check'}});
            $('#reset,.resetan').button({icons: {secondary: 'ui-icon-refresh'}});
            $('#cari').button({icons:{secondary:'ui-icon-search'}});
            get_tindakan_list(1,'');

            $('#konfirmasi_tindakan').dialog({
                autoOpen: false,title :'Konfirmasi',height: 200,width: 300,
                modal: true,resizable : false,
                buttons: [ 
                    { text: "Ok", click: function() { 
                            save();
                            $( this ).dialog( "close" ); 
                        } 
                    }, 
                    { text: "Batal", click: function() { 
                            $( this ).dialog( "close" ); 
                        } 
                    } 
                ]
            });
            $('#formbhp').submit(function(){
            return false;
        });
        $('#load-packing-barang').dialog({
                title: 'Tambah Data BHP',
                autoOpen: false,
                modal: true,
                width: 800,
                height: $(window).height(),
                buttons: [
                { text: "Ok", 
                    click: function() { 
                        $(this).dialog('close');
                        var total_bhp = 0;
                        $("input[name^=subtotal_bhp]").each(function () {
                           total_bhp += parseInt($(this).val());
                        });

                        $('#bhp').val(numberToCurrency(total_bhp)).focus();   
                    } 
                },{ text: "Reset", 
                    click: function() { 
                        $('#dinamic tbody').empty();
                        $('.rows').remove();
                    } 
                }]
            });
            $('#packing').autocomplete("<?= base_url('autocomplete/barang') ?>",
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
                    var str = data.nama_barang;
                    return str;
                },
                width: 300, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
                dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
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
                $('#kemasan').focus();
            });
            $('#qty').on('keydown', function(e) {
                if (e.keyCode === 13) {
                    $('#pilih').focus();
                }
            });
            $('#pilih').button({icons: {secondary: 'ui-icon-circle-plus'}});
            $('#reset').click(function(){
                location.reload();
            });
            $('#formtindakan').submit(function(){ 
                var Url = '<?= base_url("referensi/manage_tindakan") ?>/cek/1';
                var tipe = $('input[name=id]').val();
                if($('input[name=id_layanan]').val()===''){
                    custom_message('Peringatan','Layanan tidak boleh kosong !','#layanan');
                    return false;
                }

                if($('#js').val()===''){
                    custom_message('Peringatan','Jasa Sarana tidak boleh kosong !','#js');
                    return false;
                }
                if($('#js_nakes').val()===''){
                    custom_message('Peringatan','Jasa Nakes tidak boleh kosong !','#js_nakes');
                    return false;
                }
                if($('#js_rs').val()===''){
                    custom_message('Peringatan','Jasa Tindakan RS tidak boleh kosong !','#js_rs');
                    return false;
                }
                if($('#bhp').val()===''){
                    custom_message('Peringatan','B.H.P tidak boleh kosong !','#bhp');
                    return false;
                }
                if($('#bia_adm').val()===''){
                    custom_message('Peringatan','Biaya administrasi tidak boleh kosong !','#bia_adm');
                    return false;
                }

                if($('#margin').val()===''){
                    custom_message('Peringatan','Margin tidak boleh kosong !','#margin');
                    return false;
                }
                $.ajax({
                    type : 'GET',
                    url: Url,               
                    data: $('#formtindakan').serialize(),
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (tipe === ''){
                            if (data.status === false){
                                $('#text_konfirmasi_tindakan').html('Tarif sudah ada<br/> Apakah anda yakin akan menambahkannya lagi?');            
                                $('#konfirmasi_tindakan').dialog("open");
                            } else {
                                save();
                            }                        
                        }else{
                             save();
                        }
                            
                    }
                });        
                
                return false;
            });
            
            $('#jurusan').autocomplete("<?= base_url('kepegawaian/get_jurusan') ?>",
            {
                parse: function(data)
                {
                    var parsed = [];
                    for (var i=0; i < data.length; i++)
                    {
                        parsed[i] =
                            {
                            data: data[i],
                            value: data[i].nama // nama field yang dicari
                        };
                    }
                    $('input[name=id_jurusan]').val('');
                    return parsed;
                },
                formatItem: function(data,i,max)
                {
                    var str = '<div class=result>'+data.nama+'<br/>'+data.jenis +'</div>';
                    return str;
                },
                width: 270, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
                dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
            }).result(
            function(event,data,formated){
                $(this).attr('value',data.nama);
                $('input[name=id_jurusan]').val(data.id);
            });
            
            $('#layanan').autocomplete("<?= base_url('inv_autocomplete/get_layanan') ?>/tindakan/",
            {
                parse: function(data){
                    var parsed = [];
                    for (var i=0; i < data.length; i++) {
                        parsed[i] = {
                            data: data[i],
                            value: data[i].nama // nama field yang dicari
                        };
                    }
                    $('input[name=id_layanan]').val('');
                    return parsed;
                },
                formatItem: function(data,i,max){
                    var str = '<div class=result>'+data.nama+((data.bobot == null)?'':' - '+data.bobot)+((data.kelas == null)?'':' - '+data.bobot)+'</div>';
                    return str;
                },
                width: 350, // panjang tampilan pencarian autocomplete yang akan muncul di bawah textbox pencarian
                dataType: 'json' // tipe data yang diterima oleh library ini disetup sebagai JSON
            }).result(
            function(event,data,formated){
                $(this).val(data.nama);
                $('input[name=id_layanan]').val(data.id);
            });
            
        });
        
        function save(){
            var tipe = $('input[name=id_hide]').val();
            var Url = '';

            if(tipe == ''){
                Url = '<?= base_url("referensi/manage_tindakan") ?>/add/1';
            }else{
                Url = '<?= base_url("referensi/manage_tindakan") ?>/edit/1';
            }
            var last = $('#id').val();
            
             if(!request) {
                    request = $.ajax({
                    type : 'POST',
                    url: Url,               
                    data: $('#formtindakan').serialize()+"&"+$('#formbhp').serialize(),
                    cache: false,
                    success: function(data) {
                        $('#dinamic tbody').empty();
                        $('.rows').remove();
                        request = null;
                        $('#tindakan_list').html(data);                            
                        if(tipe === '') {
                            alert_refresh('Tambah data tarif success dilakukan');
                            $('input[name=id]').val(parseInt(last));
                        } else {
                            alert_refresh('Edit data tarif success dilakukan');
                        }
                    }
                });
            }
        }
        
        
        
        function get_tindakan_list(p){
            $.ajax({
                type : 'GET',
                url: '<?= base_url('referensi/manage_tindakan') ?>/list/'+p, 
                data : $('#formtindakan').serialize()+"&nama_unit="+$('select[name=unit] option:selected').text(),
                cache: false,
                success: function(data) {
                    $('#tindakan_list').html(data);
                }
            });
        }
        
        function delete_tindakan(id){
            $('<div></div>')
              .html("Anda yakin akan menghapus data ini ?")
              .dialog({
                 title : "Hapus Data",
                 modal: true,
                 buttons: [ 
                    { 
                        text: "Ok", 
                        click: function() { 
                            $.ajax({
                                type : 'GET',
                                url: '<?= base_url('referensi/manage_tindakan') ?>/delete/'+$('.noblock').html(),
                                data :'id='+id,
                                cache: false,
                                success: function(data) {
                                    get_tindakan_list($('.noblock').html());
                                    alert_delete();
                                }
                            });
                            $(this).dialog("close"); 
                        } 
                    }, 
                    { text: "Batal", click: function() { $( this ).dialog( "close" );}} 
                ]
            });
        }
        
        function edit_tindakan(id){
             $.ajax({
                type : 'GET',
                url: '<?= base_url("referensi/manage_tindakan") ?>/detail/1', 
                cache: false,
                data : 'id='+id,
                dataType : 'json',
                success: function(data) {
                    fill_tindakan(data);
                    fill_bhp(data.id);
                    $('#layanan').focus();
                }
            });
            
        }

        function reset_all(){
           $('#loaddata').load($.cookie('url'));
            //$('.wrap').html('');
        }

        function fill_tindakan(data){
            $('input[name=id]').val(data.id);
            $('#id,input[name=id_hide]').val(data.id);
            $('#layanan').val(data.layanan);
            $('input[name=id_layanan]').val(data.id_layanan);
            $('#profesi').val(data.id_profesi);
            $('#jurusan').val(data.jurusan);
            $('input[name=id_jurusan]').val(data.id_jurusan);
            $('#jenis_layan').val(data.jenis_pelayanan_kunjungan);
            $('#unit').val(data.id_unit);
            $('#bobot').val(data.bobot);
            $('#kelas').val(data.kelas);
            $('#js').val(numberToCurrency(data.jasa_sarana));
            $('#js_nakes').val(numberToCurrency(data.jasa_nakes));
            $('#js_rs').val(numberToCurrency(data.jasa_tindakan_rs));
            $('#bhp').val(numberToCurrency(data.bhp));
            $('#bia_adm').val(numberToCurrency(data.biaya_administrasi));
            $('#total').html(numberToCurrency(data.total));
            $('input[name=total]').val(data.total);
            $('#margin').val(data.persentase_profit);
            $('#nominal_akhir').html(numberToCurrency(data.nominal));
            $('input[name=nominal_akhir]').val(numberToCurrency(data.nominal));
            
        }

        function subtotal(){
            $('#js').val(numberToCurrency($('#js').val()));
            $('#js_rs').val(numberToCurrency($('#js_rs').val()));
            $('#js_nakes').val(numberToCurrency($('#js_nakes').val()));
            $('#bhp').val(numberToCurrency($('#bhp').val()));
            $('#bia_adm').val(numberToCurrency($('#bia_adm').val()));
            
            var js = currencyToNumber($('#js').val());
            var js_rs = currencyToNumber($('#js_rs').val());
            var jp = currencyToNumber($('#js_nakes').val());
            var bhp= currencyToNumber($('#bhp').val());
            var bia = currencyToNumber($('#bia_adm').val());
            if (isNaN(js)) { js = 0;}
            if (isNaN(js_rs)) { js_rs = 0; }
            if (isNaN(jp)) { jp = 0; }
            if (isNaN(bhp)) { bhp = 0; }
            if (isNaN(bia)) { bia = 0; }
            var val = js+js_rs+jp+bhp+bia;
            $('#total').html(numberToCurrency(val));
            $('input[name=total]').val(val);

            var margin = $('#margin').val()/100;

            var nominal= val+(margin*val);
            //alert(val+ '-' +margin+ '-' +nominal);
            $('#nominal_akhir').html(numberToCurrency(nominal));
            $('input[name=nominal_akhir]').val(numberToCurrency(Math.ceil(nominal)));
        } 
        
        function get_margin(nominal){
            var total = currencyToNumber($('#total').html());
            var margin = currencyToNumber(nominal) - total;
            var persentase = (margin / total) * 100;
            $('#margin').val(persentase);
        }
        
    </script>
    <?= form_open('', 'id = formtindakan') ?>
        <table width="100%" cellpadding="0" cellspacing="0"><tr valign="top"><td width="50%">
        <table width="100%" class="inputan">
            <tr><td>ID.:</td><td><?= form_hidden('id_hide',isset($edit)?$edit->id:null) ?>
            <?= form_input('id', isset($edit)?$edit->id:get_last_id('tarif', 'id'), 'id=id size=40') ?></td></tr>
            <tr><td>Layanan:</td><td><?= form_input('layanan',isset($edit)?$edit->layanan:null,'id=layanan size=40')?>
            <?= form_hidden('id_layanan',isset($edit)?$edit->id_layanan:null) ?></td></tr>
            <tr><td>Profesi:</td><td><?= form_dropdown('profesi',$profesi, isset($edit)?$edit->id_profesi:null,'id=profesi')?></td></tr>
            <tr><td>Kualifikasi Pend.:</td><td><?= form_input('jurusan',isset($edit)?$edit->jurusan:null,'id=jurusan size=40') ?></td></tr>

            <?= form_hidden('id_jurusan',isset($edit)?$edit->id_jurusan:null)?>
            <tr><td>Jenis Pelayanan:</td><td><?= form_dropdown('jenis_layan',$jenis_layan,isset($edit)?$edit->jenis_pelayanan_kunjungan:null,'id=jenis_layan') ?></td></tr>
            <tr><td>Unit:</td><td><?= form_dropdown('unit',$unit, isset($edit)?$edit->id_unit:null, 'id=unit')?></td></tr>
            <tr><td>Bobot:</td><td><?= form_dropdown('bobot',$bobot,isset($edit)?$edit->bobot:null,'id=bobot') ?></td></tr>
            <tr><td>Kelas:</td><td><?= form_dropdown('kelas', $kelas, isset($edit)?$edit->kelas:null, 'id=kelas')?></td></tr>
            <tr><td></td><td>
                <?= form_submit('simpan', "Simpan", 'id=simpan') ?>
                <?= form_button('cari', 'Cari', 'id=cari onclick=get_tindakan_list(1)') ?>
                <?= form_button('reset', 'Reset', 'id=reset') ?></td></tr>
        </table></td><td width="50%">
        <table width="100%" class="inputan">
            <tr><td style="width: 120px;">Jasa Sarana:</td><td><?= form_input('js',isset($edit)?rupiah($edit->jasa_sarana):'0','id=js onblur=subtotal()')?></td></tr>
            <tr><td>Jasa Nakes:</td><td><?= form_input('js_nakes',isset($edit)?rupiah($edit->jasa_nakes):'0','id=js_nakes onblur=subtotal()') ?></td></tr>
            <tr><td>Jasa RS:</td><td><?= form_input('js_rs',isset($edit)?rupiah($edit->jasa_tindakan_rs):'0','id=js_rs onblur=subtotal()') ?></td></tr>
            <tr><td>Barang Habis Pakai:</td><td><?= form_input('bhp',isset($edit)?rupiah($edit->bhp):'0','id=bhp onblur=subtotal()')?> <div class="add_pdd search-dialog" id="bt_cari" onclick="load_packing_barang();" title="Klik untuk tambah detail BHP"></div></td></tr>
            <tr><td>Bia. Adm:</td><td><?= form_input('bia_adm',isset($edit)?rupiah($edit->biaya_administrasi):'0','id=bia_adm onblur=subtotal()') ?></td></tr>
            <tr><td>Total: </td><td id="total" class="wrap"><?= isset($edit)?rupiah($edit->total):''?><?= form_hidden('total',isset($edit)?rupiah($edit->total):'') ?></td></tr>
            <tr><td>Margin (%):</td><td><?= form_input('margin',isset($edit)?rupiah($edit->persentase_profit):'0','id=margin size=5 onblur=subtotal()') ?></td></tr>
            <tr><td>Nominal Akhir:</td><td><?= form_input('nominal_akhir',isset($edit)?rupiah($edit->nominal):'0','onkeyup="FormNum(this)" onblur=get_margin(this.value)') ?></td></tr>
        </table></td></tr>
        </table>


    <?= form_close() ?>
    <div id="tindakan_list"></div>

    <div id="konfirmasi_tindakan" style="display: none; padding: 20px;">
        <div id="text_konfirmasi_tindakan"></div>
    </div>


<div id="load-packing-barang" style="display:none;">
    <table width="100%" class=data-input cellspacing="0">
        <tr><td>Nama Barang:</td><td><?= form_input("packing", NULL, "id=packing size=40") ?><input type=hidden name="id_barang" id="id_barang" /></td></tr>
        <tr><td>Kemasan:</td><td><select name="id_kemasan" id="kemasan" style="min-width: 86px;"></select></td></tr>
        <tr><td>Qty:</td><td><?= form_input("qty", NULL, "id=qty") ?></td></tr>
        <tr><td></td><td><?= form_button('', "Pilih", "id=pilih onclick=add_new_row()") ?></td></tr>
    </table>
    <?= form_open('','id=formbhp') ?>
    <div class="data-list">
        <table width="100%" class="list-data" id="dinamic">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Nama Barang</th>
                    <th width="8%">Kemasan</th>
                    <th width="15%">Harga Jual</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Subtotal</th>
                    <th width="2%">#</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <?= form_close() ?>
</div>

<script type="text/javascript">

    function load_packing_barang() {            
        $('#load-packing-barang').dialog('open');
    }

    function add_new_row() {
            var id      = $('#id_barang').val();
            var nama    = $('#packing').val();
            var id_kemasan  = $('#kemasan').val();
            var kemasan     = $('#kemasan option:selected').text();
            var qty     = $('#qty').val();

            if(id === ''){
                custom_message('Peringatan','Barang harus diisi !', '#packing');
            }else if(qty === ''){
                custom_message('Peringatan','Jumlah harus diisi !', '#qty');
            }else{
                $.ajax({
                    url: '<?= base_url("inv_autocomplete/get_harga_barang_penjualan") ?>/'+id+'/'+id_kemasan,
                    cache: false,
                    dataType: 'json',
                    success: function(msg) {
                        var diskon = msg.harga*(msg.diskon/100);
                        var terdiskon = (msg.harga-diskon);
                        //var harga_jual= terdiskon+(terdiskon*(msg.ppn_jual/100));
                        var harga_jual = terdiskon;
                        set_data_bhp('',id, nama, harga_jual, qty, kemasan, id_kemasan, msg.jumlah);
                    }
                });
                
                $('#packing').focus();
                $('#id_packing, #packing, #qty').val('');
            }
            
           
    }

    function set_data_bhp(id,id_barang, nama, hrg, qty, kemasan, id_kemasan,isi){
        var auto = $('.rows').length+1;
        var harga= Math.ceil(hrg*isi);
        var str  = '<tr class="rows">'+
                    '<td align="center">'+auto+'<input type="hidden" name="id_bhp[]" id="id_bh'+auto+'" value="'+id+'"/></td>'+
                    '<td><input type="hidden" name="id_barang[]" id="id_bhp'+auto+'" value="'+id_barang+'"/>'+nama+'</td>'+
                    '<td align=center><input type="hidden" name="id_kemasan[]" id="id_kemasan'+auto+'" value="'+id_kemasan+'" />'+kemasan+'</td>'+
                    '<td align="right"><input type="hidden" name="harga_bhp[]" id="harga_bhp'+auto+'" value="'+harga+'" />'+numberToCurrency(harga)+'</td>'+
                    '<td><input type="text" name="qty[]" id="qty'+auto+'" value="'+qty+'" onkeyup="change_harga(this, '+auto+')" style="text-align: center;" /></td>'+
                    '<td align="right"><input type="hidden" name="subtotal_bhp[]" id="subtotal_bhp'+auto+'" value="'+(harga * qty)+'" /> <span id="sub_bhp'+auto+'">'+numberToCurrency((harga * qty))+'<span></td>'+
                    '<td align="center" class=aksi><a class="deletion" onclick="hapus_bhp(this,\''+id+'\')">&nbsp;</a></td>'+
                '</tr>';
        $('#dinamic tbody').append(str);
    }

    function change_harga(obj,index){ 
        //Angka(obj);       
        var harga = $('#harga_bhp'+index).val();
        var qty = $('#qty'+index).val();

        var sub = harga*qty;
        
        $('#subtotal_bhp'+index).val(sub);
        $('#sub_bhp'+index).html(numberToCurrency(sub));

    }

    function fill_bhp(id_tarif){
        $.ajax({
            url: '<?= base_url("referensi/get_data_bhp_tarif") ?>/'+id_tarif,
            cache: false,
            dataType: 'json',
            success: function(data) {
                $('#dinamic tbody').empty();
                $.each(data, function(i,v){
                    set_data_bhp(v.id, v.id_barang, v.barang, v.harga_jual, v.jumlah, v.kemasan, v.id_satuan, v.jml_isi);
                });
                
            }
        });
    }

    function hapus_bhp(obj, id_bhp){        
        $('<div></div>')
              .html("Proses hapus data ini akan menghapus data di database, anda yakin akan menghapus data ini ?")
              .dialog({
                 title : "Hapus Data",
                 modal: true,
                 buttons: [ 
                    { 
                        text: "Ok", 
                        click: function() { 
                            var parent = obj.parentNode.parentNode;
                            parent.parentNode.removeChild(parent);

                            if (id_bhp !== '') {
                                 $.ajax({
                                    url: '<?= base_url("referensi/delete_data_bhp_tarif") ?>/'+id_bhp,
                                    cache: false,
                                    dataType: 'json',
                                    success: function(data) {
                                        
                                        
                                    }
                                });
                            };
                            $( this ).dialog( "close" ); 
                        } 
                    }, 
                    { text: "Batal", click: function() { $( this ).dialog( "close" );}} 
                ]
            });
    }

</script>

</div>
