<table class="list-data" width="100%">
    <thead>
        <tr class="italic">
            <th width="3%">No.</th>
            <th width="10%">No. Ref</th>
            <th width="10%">No. Faktur</th>
            <th width="5%">Tanggal</th>
            <th width="20%">Supplier</th>
            <th width="7%">Cara Bayar</th>
            <th width="13%">Bank</th>
            <th width="10%">No. Transaksi</th>
            <th width="10%">Keterangan</th>
            <th width="5%">Nominal</th>
            <th width="2%">#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list_data as $key => $data) { ?>
        <tr>
            <td align="center"><?= ++$key ?></td>
            <td><?= $data->no_ref ?></td>
            <td align="center"><?= $data->faktur ?></td>
            <td align="center"><?= datefmysql($data->tanggal) ?></td>
            <td><?= $data->supplier ?></td>
            <td><?= $data->cara_bayar ?></td>
            <td><?= $data->bank ?></td>
            <td><?= ($data->no_transaksi !== '')?$data->no_transaksi:'-' ?></td>
            <td><?= $data->keterangan ?></td>
            <td align="right"><?= rupiah($data->nominal) ?></td>
            <td align="center" class="aksi">
                <a class='deletion' onclick="delete_inkaso('<?= $data->id ?>', '<?= $page ?>');" title="Klik untuk hapus">&nbsp;</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>