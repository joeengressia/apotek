<table cellpadding="0" cellspacing="0" class="list-data" width="100%">
    <thead>
        <tr>
            <th width="3%">No.</th>
            <th width="84%">Nama Jasa</th>
            <th width="10%">Nominal</th>
            <th width="3%">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($list_data != null): ?>
        <?php foreach ($list_data as $key => $prov) : 
            $str = $prov->id.'#'.$prov->nama.'#'.$prov->nominal;
            ?>
            <tr class="<?= ($key % 2) ? "even" : "odd" ?>" ondblclick="edit_jasa_apoteker('<?= $str ?>');">
                <td align="center"><?= (++$key + (($page - 1) * $limit)) ?></td>
                <td><?= $prov->nama ?></td>
                <td align="right"><?= currency($prov->nominal) ?></td>
                <td class="aksi" align="center">
                    <a title="Edit jasa apoteker" class="edition" onclick="edit_jasa_apoteker('<?= $str ?>');"></a>
                    <a title="Hapus jasa apoteker" class="deletion" onclick="delete_jasa_apoteker(<?= $prov->id ?>)"></a>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<?= $paging ?>
<br/><br/>