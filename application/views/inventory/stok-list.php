<table class="list-data" width="100%">
    <tr>
        <th width="3%">No.</th>
        <th width="30%">Nama Barang</th>
        <th width="5%">Expired</th>
        <th width="5%">Masuk</th>
        <th width="5%">Keluar</th>
        <?php if (post_param('jenis') !== 'History') { ?>
        <th width="5%">Sisa</th>
        <?php } ?>
    </tr>
    <?php foreach ($list_data as $key => $data) { ?>
    <tr class="<?= ($key%2===0)?'odd':'even' ?>">
        <td align="center"><?= ++$key ?></td>
        <td><?= $data->nama_barang ?></td>
        <td align="center"><?= datefmysql($data->ed) ?></td>
        <td align="center"><?= $data->masuk ?></td>
        <td align="center"><?= $data->keluar ?></td>
        <?php if (post_param('jenis') !== 'History') { ?>
        <td align="center"><?= isset($data->sisa)?$data->sisa:NULL ?></td>
        <?php } ?>
    </tr>
    <?php } ?>
</table>