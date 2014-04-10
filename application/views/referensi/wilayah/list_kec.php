<div class="data-list">

<div id="pencarian">
    <h3>
        <?php if (isset($kabupaten) && ($kabupaten!="")): ?>
        Kabupaten "<?= $kabupaten ?>"
        <?php endif; ?>

         <?php if (isset($nama) && ($nama!="")): ?>
        <br/>Kecamatan "<?= $nama ?>"
        <?php endif; ?>

        <?php if (isset($kode) && ($kode!="")): ?>
            <br/>Kode "<?= $kode ?>"   
        <?php endif; ?>
    </h3>
</div>
<table cellpadding="0" cellspacing="0" class="list-data" width="100%">
    <thead>
        <tr>
            <th width="3%">No.</th>
            <th width="50%">Nama</th>
            <th width="34%">Kabupaten</th>
            <th width="10%">Kode</th>
            <th width="3%">Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($kecamatan != null): ?>
        <?php foreach ($kecamatan as $key => $kab): ?>
            <tr class="<?php echo ($key % 2) ? "even" : "odd" ?>" ondblclick="edit_kecamatan('<?= $kab->id ?>','<?= $kab->nama ?>','<?= $kab->kabupaten_id ?>','<?= $kab->kabupaten ?>','<?= $kab->kode ?>')">
                <td align="center"><?= (++$key + (($page - 1) * $limit)) ?></td>
                <td><?php echo $kab->nama ?></td>
                <td><?php echo $kab->kabupaten ?></td>
                <td><?php echo $kab->kode ?></td>
                <td class="aksi" align="center"> 
                    <a title="Edit kecamatan" class="edition" onclick="edit_kecamatan('<?= $kab->id ?>','<?= $kab->nama ?>','<?= $kab->kabupaten_id ?>','<?= $kab->kabupaten ?>','<?= $kab->kode ?>')"></a>
                    <a title="Hapus kecamatan" class="deletion" onclick="delete_kecamatan('<?= $kab->id ?>')"></a>
                </td> 
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<div id="paging"><?= $paging ?></div>
<div id="resume">
    <h3>
        Halaman <?= $page ?> dari <?= (ceil($jumlah / $limit)==0)?1:ceil($jumlah / $limit)?> (Total <?= $jumlah ?> data )
    </h3>
</div>
<br/><br/>
</div>