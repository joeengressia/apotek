<div class="data-list">
    <table cellpadding="0" cellspacing="0" class="list-data" width="100%">
        <thead>
            <tr>
                <th width="3%">No.</th>
                <th width="84%">Nama produk</th>
                <th width="10%">Reimburse %</th>
                <th width="3%">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //$asuransi = asuransi_produk_muat_data();
        if (count($asuransi) == 0) {
            for ($key = 1; $key <= 2; $key++) {
                ?>
                <tr class="<?= ($key % 2) ? "even" : "odd" ?>">
                    <td align="center">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>    
                <?php
            }
        } else {
            foreach ($asuransi as $key => $prov) {
                ?>
                <tr class="<?= ($key % 2) ? "even" : "odd" ?>" ondblclick="edit_produk_asuransi('<?= $prov->id ?>','<?= $prov->nama ?>','<?= $prov->reimbursement ?>')">
                    <td align="center"><?= (++$key + (($page - 1) * $limit)) ?></td>
                    <td><?= $prov->nama ?></td>
                    <td align="center"><?= $prov->reimbursement ?></td>
                    <td class="aksi">
                        <a title="Edit produk asuransi" class="edition" onclick="edit_produk_asuransi('<?= $prov->id ?>','<?= $prov->nama ?>','<?= $prov->reimbursement ?>')"></a>
                        <a title="Hapus produk asuransi" class="deletion" onclick="delete_produk_asuransi('<?= $prov->id ?>')"></a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
    <div id="paging"><?= $paging ?></div>
    <div id="resume">
        <h3>
            Halaman <?= $page ?> dari <?= (ceil($jumlah / $limit) == 0) ? 1 : ceil($jumlah / $limit) ?> (Total <?= $jumlah ?> data )
        </h3>
    </div>
    <br/><br/>
</div>