<?php
foreach ($list_data as $key => $data) {
?>
<tr class="tr_rows">
    <td align=center><?= ++$key ?></td>
    <td>&nbsp;<?= $data->nama_barang ?> <input type=hidden name=id_barang[] value="<?= $data->id_barang ?>" class=id_barang id=id_barang<?= $key ?> /></td>
    <td align=center><input type=hidden name=harga_jual[] id="harga_jual<?= $key ?>" /> <input type=hidden name=isi_satuan[] id="isi_satuan<?= $key ?>" /> <select name=kemasan[] id="kemasans<?= $key ?>"></select></td>
    <td><input type=text name=jumlah[] id=jumlah<?= $key ?> value="<?= $data->jumlah ?>" size=10 style="text-align: center;" /></td>
    <td align=right id=subtotal<?= $key ?>></td>
    <td align=center><input type=hidden id=perundangan<?= $key ?> /><img onclick=removeMe(this); title="Klik untuk hapus" src="<?= base_url('assets/images/delete.png') ?>" class=add_kemasan align=left /></td>
</tr>
<script>
$.getJSON('<?= base_url('inventory/get_kemasan_barang') ?>?id='+<?= $data->id_barang ?>, function(data){
    if (data === null) {
        alert('Kemasan tidak barang tidak tersedia !');
    } else {
        $.each(data, function (index, value) {
            $('#kemasans'+<?= $key ?>).append("<option value='"+value.id_kemasan+"'>"+value.nama+"</option>");
        });
    }
});
$.ajax({
    url: '<?= base_url('autocomplete/get_detail_harga_barang_resep') ?>?id='+<?= $data->id_barang ?>+'&jumlah='+<?= $data->jumlah ?>,
    dataType: 'json',
    cache: false,
    success: function(data) {
        hitung_detail_total('<?= $key ?>', '<?= $data->jumlah ?>', data.diskon_rupiah, data.diskon_persen, Math.ceil(data.harga_jual_nr), data.isi_satuan);
        $('#isi_satuan'+<?= $key ?>).val(data.isi_satuan);
    }
});
$('#kemasans'+<?= $key ?>).change(function() {
    var id  = $(this).val();
    var jum = $('#jumlah'+<?= $key ?>).val();
    $.ajax({
        url: '<?= base_url('autocomplete/get_detail_harga_barang') ?>?id='+$('#id_barang'+<?= $key ?>).val()+'&kemasan='+id+'&jumlah='+jum,
        dataType: 'json',
        cache: false,
        success: function(data) {
            $('#isi_satuan'+<?= $key ?>).val(data.isi_satuan);
            hitung_detail_total(<?= $key ?>, jum, data.diskon_rupiah, data.diskon_persen, Math.ceil(data.harga_jual), data.isi_satuan);
            hitung_estimasi();
        }
    });
});

$('#jumlah'+<?= $key ?>).blur(function() {
    var jumlah      = $('#jumlah'+<?= $key ?>).val();
    var hrg_jual    = parseInt(currencyToNumber($('#harga_jual'+<?= $key ?>).val()));
    var isi_satuan  = parseInt($('#isi_satuan'+<?= $key ?>).val());
    
    var subtotal    = (hrg_jual*jumlah*isi_satuan);
    $('#subtotal'+<?= $key ?>).html(numberToCurrency(parseInt(subtotal)));
    hitung_estimasi();
});

</script>
<?php } ?>