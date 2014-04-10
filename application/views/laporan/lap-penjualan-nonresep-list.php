<table cellspacing="0" width="100%" class="list-data">
<thead>
    <tr class="italic">
        <th width="3%">No.</th>
        <th width="5%">Tanggal</th>
        <th width="5%">Diskon <br/>Rp.</th>
        <th width="5%">Diskon <br/>%</th>
        <th width="5%">PPN %</th>
        <th width="5%">Tuslah <br/>RP.</th>
        <th width="5%">Embalage<br/> RP.</th>
        <th width="10%">Total</th>
        <th width="25%">Nama Barang</th>
        <th width="5%">Kemasan</th>
        <th width="5%">Jumlah</th>
        <th width="5%">Harga</th>
        <th width="7%">Subtotal</th>
        
    </tr>
</thead>
<tbody>
    <?php
    $id = "";
    $no = 1;
    $alert = "";
    $total_nota = 0;
    foreach ($list_data as $key => $data) { 
        //$str = $data->id.'#'.$data->id_resep.'#'.$data->customer.'#'.$data->id_customer;
        ?>
        <tr id="<?= $data->id ?>" class="detail <?= ($id !== $data->id)?'odd':NULL ?>">
            <td align="center"><?= ($id !== $data->id)?($no):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?datetimefmysql($data->waktu):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->diskon_rupiah):NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->diskon_persen:NULL ?></td>
            <td align="center"><?= ($id !== $data->id)?$data->ppn:NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->tuslah):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->embalage):NULL ?></td>
            <td align="right"><?= ($id !== $data->id)?rupiah($data->total):NULL ?></td>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->kemasan ?></td>
            <td align="center"><?= $data->qty ?></td>
            <td align="right"><?= rupiah($data->harga_jual) ?></td>
            <td align="right"><?= rupiah($data->subtotal) ?></td>
            
            
        </tr>
    <?php 
    if ($id !== $data->id) {
        $no++;
        $total_nota = $total_nota+$data->total;
    }
    $id = $data->id;
    }
    ?>
        <tr>
            <td colspan="12" align="right">TOTAL</td><td align="right"><b><?= rupiah($total_nota) ?></b></td>
        </tr>
</tbody>
</table>