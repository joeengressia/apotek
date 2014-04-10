<?php $this->load->view('message') ?>
<script type="text/javascript">
var request;
$(function() {
        // initial
        $('#tabs').tabs();
        $('#jasa_apoteker').focus();
        get_jasa_apoteker_list(1);
        $('#simpan').button({
            icons: {
                secondary: 'ui-icon-circle-check'
            }
        }).click(function() {
            form_add_submit();
        });
        $('#formsave').submit(function(){
            form_add_submit();
            return false;
        });

        $('#reset').button({icons: {secondary: 'ui-icon-refresh'}}).click(function(){
            $('#loaddata').empty();
            $('#loaddata').load($.cookie('url'));
        });
    });

function form_add_submit(){
    var tipe = $('input[name=id]').val();
    
    if($('#jasa_apoteker').val() !== ''){
        if(!request) {
            request = $.ajax({
                type : 'POST',
                url: '<?= base_url('referensi/manage_jasa_apoteker/save') ?>',               
                data: $('#formsave').serialize(),
                cache: false,
                dataType : 'json',
                success: function(data) {
                    $('input[name=id]').val(data.id);
                    var id = data.id;
                    pesan('ok',tipe);
                    get_jasa_apoteker_list(1);
                    request = null;                            
                },
                error : function(){
                    pesan('fail',tipe);
                }
            });
        }
    }else{
        custom_message('Peringatan', 'Nama jasa_apoteker tidak boleh kosong !', '#jasa_apoteker');
    }

}

function pesan(status,tipe){
    if (status === 'ok') {
        if(tipe === ''){
            alert_tambah();                                    
        }else{
            alert_edit();
        }
    }else{
        if(tipe === ''){
            alert_tambah_failed();                                    
        }else{
            alert_edit_failed();
        }
    }
}

function get_jasa_apoteker_list(p){
    $.ajax({
        type : 'GET',
        url: '<?= base_url('referensi/manage_jasa_apoteker/list') ?>/'+p, 
        cache: false,
        success: function(data) {
            $('#jasa_apoteker_list').html(data);
        }
    });
}

function delete_jasa_apoteker(id){
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
                        url: '<?= base_url('referensi/manage_jasa_apoteker/delete') ?>/'+$('.noblock').html(),
                        data :'id='+id,
                        dataType: 'json',
                        success: function(data) {
                            get_jasa_apoteker_list($('.noblock').html());
                            alert_delete();
                        }
                    });
                    $( this ).dialog( "close" ); 
                } 
            }, 
            { text: "Batal", click: function() { $( this ).dialog( "close" );}} 
        ]
    });         
}

function edit_jasa_apoteker(str){
    var arr = str.split('#');
    $('#jasa_apoteker').val(arr[1]);
    $('input[name=id]').val(arr[0]);
    $('#nominal').val(numberToCurrency(arr[2]));

}

function paging(page, tab,search){
    get_jasa_apoteker_list(page);
}   

</script>
<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <?= form_open('', 'id = formsave') ?>
            <?= form_hidden('id',NULL,'id=id') ?>
            <table class="inputan" width="100%">
                <tr><td>Nama Jasa Apoteker:</td><td><?= form_input('jasa_apoteker', null, 'id=jasa_apoteker size=30') ?></td></tr>
                <tr><td>Nominal:</td><td><?= form_input('nominal', NULL, 'id=nominal onkeyup="FormNum(this);"') ?></td></tr>
                <tr><td></td><td><?= form_button('', 'Simpan', 'id=simpan') ?><?= form_button(null, 'Reset', 'id=reset') ?></td></tr>
            </table>
            <?= form_close() ?>
            <div class="data-list">
                <div id="jasa_apoteker_list"></div>
            </div>
        </div>
    </div>
</div>
