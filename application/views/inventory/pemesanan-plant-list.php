<script type="text/javascript">
$(function() {
    //$( document ).tooltip();
});
</script>
<table cellspacing="0" width="100%" class="list-data">
<thead>
    <tr class="italic">
        <th width="5%">No.</th>
        <th width="20%">Supplier</th>
        <th width="60%">Nama Barang</th>
        <th width="5%">Jumlah</th>
        <th width="2%">#</th>
    </tr>
</thead>
<tbody>
    <?php
    $no = 1;
    $sp = "";
    foreach ($list_data as $key => $data) { 
        $rows = $this->m_inventory->get_distributor_by_barang($data->id_barang)->row();
        ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= ($sp !== $data->id)?($key+$auto):NULL ?></td>
            <td><?= isset($rows->nama)?$rows->nama:NULL ?></td>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->jumlah ?></td>
            <td class='aksi' align='center'>
                <!--<a class='edition' onclick="edit_pemesanan_plant('<?= $str ?>');" title="Klik untuk edit pemesanan_plant">&nbsp;</a>-->
                <?php
                if ($sp !== $data->id) { ?>
                    <a class='deletion' onclick="delete_pemesanan_plant('<?= $data->id ?>','<?= $page ?>');" title="Klik untuk hapus pemesanan_plant">&nbsp;</a>
                <?php } ?>
            </td>
        </tr>
    <?php 
    if ($sp !== $data->id) {
        $no++;
    }
    $sp = $data->id;
    }
    ?>
</tbody>
</table>
<?= $paging ?>
<br/>