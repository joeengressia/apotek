<div class="data-list">
    <table width="100%" class="list-data">
        <thead>
            <tr>
                <th width="3%">No.</th>
                <th width="60%">Nama Barang</th>
                <th width="5%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($list_data as $key => $data) { 
                ?>
            <tr class="<?= ($key%2===0)?'odd':'even' ?>">
                <td align="center"><?= ++$key ?></td>
                <td><?= $data->nama_barang ?></td>
                <td align="center"><?= $data->jumlah ?></td>
            </tr>
            <?php 
            
            } ?>
        </tbody>
    </table>
</div>