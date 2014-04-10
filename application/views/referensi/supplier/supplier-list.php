<table cellspacing="0" width="100%" class="list-data">
<thead>
<tr class="italic">
    <th width="5%">No.</th>
    <th width="15%">Nama Supplier</th>
    <th width="45%">Alamat</th>
    <th width="15%">Email</th>
    <th width="15%">Telp</th>
    <th width="3%">#</th>
</tr>
</thead>
<tbody>
    <?php 
    foreach ($list_data as $key => $data) { 
        $str = $data->id.'#'.$data->nama.'#'.$data->alamat.'#'.$data->email.'#'.$data->telp;
        ?>
    <tr class="<?= ($key%2==0)?'even':'odd' ?>">
        <td align="center"><?= ($key+$auto) ?></td>
        <td><?= $data->nama ?></td>
        <td><?= $data->alamat ?></td>
        <td><?= $data->email ?></td>
        <td><?= $data->telp ?></td>
        <td class='aksi' align='center'>
            <a class='edition' onclick="edit_supplier('<?= $str ?>');" title="Klik untuk edit supplier">&nbsp;</a>
            <a class='deletion' onclick="delete_supplier('<?= $data->id ?>','<?= $page ?>');" title="Klik untuk hapus supplier">&nbsp;</a>
        </td>
    </tr>
    <?php } ?>
</tbody>
</table>
<?= $paging ?>
<br/><br/>