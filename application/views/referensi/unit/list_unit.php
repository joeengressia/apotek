<table cellpadding="0" cellspacing="0" class="list-data" width="100%">
    <thead>
        <tr>
            <th width="3%">ID</th>
            <th width="94%">Unit</th>
            <th width="3%">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($unit != null): ?>
        <?php foreach ($unit as $key => $prov) : ?>
            <tr class="<?= ($key % 2) ? "even" : "odd" ?>" ondblclick="edit_unit(<?= $prov->id ?>,'<?= $prov->nama ?>')">
                <td align="center"><?= (++$key + (($page - 1) * $limit)) ?></td>
                <td><?= $prov->nama ?></td>
                <td class="aksi" align="center">
                    <a title="Edit unit" class="edition" onclick="edit_unit(<?= $prov->id ?>,'<?= $prov->nama ?>')"></a>
                    <a title="Hapus unit" class="deletion" onclick="delete_unit(<?= $prov->id ?>)"></a>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<?= $paging ?>
<br/><br/>