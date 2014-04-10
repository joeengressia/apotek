<?= $this->load->view('message') ?>
<script type="text/javascript">
    $(function() {
        $('#simpan').button({
            icons: {
                secondary: 'ui-icon-circle-check'
            }
        });
        $('#simpan').click(function() {
            $('<div id=alert>Anda yakin akan menyimpan transaksi ini?</div>').dialog({
                title: 'Konfirmasi Penghapusan',
                autoOpen: true,
                modal: true,
                buttons: {
                    "OK": function() {
                        $('#form').submit();
                    },
                    "Cancel": function() {
                        $(this).dialog('close').remove();
                    }
                }
            });
        });
        $('#form').submit(function() {
            $.ajax({
                url: '<?= base_url('inventory/manage_koreksi_stok/save') ?>',
                data: $('#form').serialize(),
                dataType: 'json',
                type: 'POST',
                success: function(data) {
                    if (data.status === true) {
                        alert_refresh('Transaksi koreksi stok berhasil dilakukan');
                    }
                }
            });
            return false;
        });
    });
    
    function penyesuaian(i) {
        var sisa        = parseFloat($('#sisa'+i).html());
        var kenyataan   = parseFloat($('#kenyataan'+i).val());
        var penyesuaian = kenyataan - sisa;
        $('#penyesuaian'+i).val(isNaN(penyesuaian)?'':penyesuaian);
    }
</script>
<?= form_open('','id=form') ?>
<table class="list-data" width="100%">
    <tr>
        <th width="3%">No.</th>
        <th width="30%">Nama Barang</th>
        <th width="5%">Expired</th>
        <th width="5%">Masuk</th>
        <th width="5%">Keluar</th>
        <th width="5%">Sisa</th>
        <th width="5%">Kenyataan</th>
        <th width="5%">Penyesuaian</th>
        <th width="20%">Alasan</th>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2===0)?'odd':'even' ?>">
        <td align="center"><?= ($key+$auto) ?></td>
        <td><?= $data->nama_barang ?><?= form_hidden('id_barang[]', $data->id_barang, 'id=id_barang'.$key) ?></td>
        <td align="center"><?= datefmysql($data->ed) ?><?= form_hidden('ed[]', $data->ed, 'id=ed'.$key) ?></td>
        <td align="center"><?= $data->masuk ?></td>
        <td align="center"><?= $data->keluar ?></td>
        <td align="center" id="sisa<?= $key ?>"><?= $data->sisa ?></td>
        <td><?= form_input('kenyataan[]', NULL, 'id=kenyataan'.$key.' onblur="penyesuaian('.$key.')" onkeyup=Angka(this);') ?></td>
        <td><?= form_input('penyesuaian[]', NULL, 'id=penyesuaian'.$key.' readonly') ?></td>
        <td><?= form_input('alasan[]', NULL, 'id=alasan'.$key) ?></td>
    </tr>
    <?php } ?>
</table>
<?= form_close() ?>
<?= form_button('', 'Simpan', 'id=simpan style="float: right;"') ?>
<?= $paging ?>
<br/><br/>