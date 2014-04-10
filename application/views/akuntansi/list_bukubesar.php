<?php $this->load->view('message') ?>
<script type="text/javascript">

$('.delete').click(function() {
    var url = $(this).attr('href');
    $("<div title='Konfirmasi Simpan'>Anda yakin akan menghapus data transaksi ini ?</div>").dialog({
        modal: true,
        autoOpen: true,
        width: 320,
        buttons: { 
            "Ya": function() {
            $(this).dialog('close');
            $.ajax({
                url: url,
                cache: false,
                dataType: 'json',
                success: function(data) {
                    alert_delete();
                    get_list_data();
                }
            });
            },
            "Tidak": function() {
                $(this).dialog('close');
                return false;
            }
        }, close: function() {
            $(this).dialog('close');
            return false;
        }
    });
    return false;
});

//$("table").tablesorter();


</script>

<div class="data-list">
    <table class="list-data" width="100%">
        <thead>
            <tr>
                <th width="3%">No.</th>
                <th width="10%">Tanggal</th>
                <th width="15">Transaksi</th>
                <th width="10%">Kode</th>
                <th width="41%">Nama Rekening</th>
                <th width="7%">Debet</th>
                <th width="7%">Kredit</th>
                <th width="5%">#</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($list_data != null){?>
                <?php foreach ($list_data as $key => $data) {
                    ?>
                <tr class="<?= ($key%2==0)?'even':'odd' ?>">
                    <td align="center"><?= ++$key ?></td>
                    <td align="center" style="white-space: nowrap;"><?= datetime($data->waktu) ?></td>
                    <td><?= $data->transaksi ?></td>
                    <td align="center"><?= $data->kode ?></td>
                    <td><?= $data->rekening ?></td>
                    <td align="right"><?= rupiah($data->debet) ?></td>
                    <td align="right"><?= rupiah($data->kredit) ?></td>
                    <td class="aksi">
                        <!--<a class="edit" onclick="edit_jurnal('<?= $str ?>')"></a>-->
                        <?= anchor('akuntansi/delete_bukubesar/'.$data->id, '&nbsp;', 'class=deletion title=Delete') ?>
                    </td>
                </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php } ?>
        </tbody>            
    </table>
    <br/>
    
    <!--<?= $paging ?>-->
</div>