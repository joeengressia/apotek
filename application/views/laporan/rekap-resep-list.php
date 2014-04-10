<script type="text/javascript">
$(function() {
    $('.double-scroll').doubleScroll();
});
</script>
<div class="double-scroll" style="width: 100%;">
        <table class="list-data" width="130%">
        <tr>
            <th width="3%">No.</th>
            <th width="7%">No. Resep</th>
            <th width="5%">Tanggal</th>
            <th width="5%">No. RM</th>
            <th width="20%">Pasien</th>
            <th width="20%">Dokter</th>
            <th width="5%">No. R/</th>
            <th width="5%">Aturan Pakai</th>
            <th width="5%">Iterasi</th>
            <th width="20%">Nama Barang</th>
            <th width="5%">Jml</th>
            <th width="5%">#</th>
        </tr>
        <?php
        if (isset($_GET['awal'])) {
        $total = 0;
        $no = "";
        $nor= "";
        $num= 1;
        foreach ($list_data as $key => $data) { 
        //$total = $total + $data->profesi_layanan_tindakan_jasa_total;
        ?>
        <tr class="<?= ($key%2==1)?'even':'odd' ?>">
            <td align="center"><?= ($no !== $data->id)?$num++:NULL ?></td>
            <td align="center"><?= ($no !== $data->id)?$data->id:NULL ?></td>
            <td align="center"><?= ($no !== $data->id)?datetimefmysql($data->waktu):NULL ?></td>
            <td align="center"><?= ($no !== $data->id)?$data->no_rm:NULL ?></td>
            <td><?= ($no !== $data->id)?$data->pasien:NULL ?></td>
            <td><?= ($no !== $data->id)?$data->dokter:NULL ?></td>
            <td align="center"><?= ($no !== $data->id or $nor !== $data->r_no)?$data->r_no:NULL ?></td>
            <td align="center"><?= ($no !== $data->id or $nor !== $data->r_no)?$data->pakai_aturan:NULL ?></td>
            <td align="center"><?= ($no !== $data->id or $nor !== $data->r_no)?$data->iter:NULL ?></td>
            <td><?= $data->nama_barang ?></td>
            <td align="center"><?= $data->resep_r_jumlah ?></td>
            <td align="center"><?= ($no !== $data->id)?'<a class="printing" onclick=cetak_copy_resep("'.$data->id_resep.'"); title="Klik untuk cetak copy resep">&nbsp;</a>':NULL ?></td>
        </tr>
        <?php 
        $no = $data->id;
        $nor= $data->r_no;
            
        } ?>
<!--        <tr>
            <td colspan="6" align="right">Total</td>
            <td align="right"><?= rupiah($total) ?></td>
        </tr>-->
        <?php } else { 
        for ($i = 1; $i <= 2; $i++) {
        ?>
        <tr class="<?= ($i%2==1)?'even':'odd' ?>">
            <td align="center">&nbsp;</td>
            <td align="center"></td>
            <td align="center"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php } }?>
    </table><br/>
    </div>