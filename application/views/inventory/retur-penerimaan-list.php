<table cellspacing="0" width="100%" class="list-data">
<thead>
    <tr>
        <th width="5%">No.</th>
        <th width="5%">No. Retur</th>
        <th width="25%">Nama Supplier</th>
        <th width="10%">Tanggal</th>
        <th width="25%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Expired</th>
        <th width="5%">Jumlah</th>
        <th width="2%">#</th>
    </tr>
</thead>
<tbody>
    <?php
    $no = 1;
    $nama = "";
    foreach ($list_data as $key => $data) { 
        $str = $data->id_retur_penerimaan.'#'.datefmysql($data->tanggal).'#'.$data->id_supplier.'#'.$data->supplier;
        ?>
        <tr class="<?= ($key%2==0)?'even':'odd' ?>">
            <td align="center"><?= ($data->id_retur_penerimaan !== $nama)?($no):NULL ?></td>
            <td align="center"><?= ($data->id_retur_penerimaan !== $nama)?$data->id_retur_penerimaan:NULL ?></td>
            <td><?= ($data->id_retur_penerimaan !== $nama)?$data->supplier:NULL ?></td>
            <td align="center"><?= ($data->id_retur_penerimaan !== $nama)?datefmysql($data->tanggal):NULL ?></td>
            <td><?= $data->barang.' '.$data->kekuatan.' '.$data->satuan ?></td>
            <td><?= $data->kemasan ?></td>
            <td align="center"><?= datefmysql($data->expired) ?></td>
            <td align="center"><?= $data->jumlah ?></td>
            <td class='aksi' align='center'>
                <a class='edition' onclick="edit_retur_penerimaan('<?= $str ?>');" title="Klik untuk edit retur_penerimaan">&nbsp;</a>
                <a class='deletion' onclick="delete_retur_penerimaan('<?= $data->id_retur_penerimaan ?>','<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
            </td>
        </tr>
    <?php 
    if ($data->id_retur_penerimaan !== $nama) {
        $no++;
    }
    $nama = $data->id_retur_penerimaan;
    }
    ?>
</tbody>
</table>
<?= $paging ?>