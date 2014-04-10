<style type="text/css">
 .tbl{
    padding: 2px; font-size: 13px;color:black;
}
</style>

<div class="data-list">
    
    <table class="inputan" width="100%">
        <tr><td>No. Pendaftaran: </td><td><?= $no_daftar ?></td></tr>
        <tr><td>Nama Pasien: </td><td><?= $nama ?></td></tr>
    </table>
    <br/>
    <table class="list-data" width="100%">
        <tr>
            <td colspan="5" style="text-align: left; background: #f4f4f4;">Pendaftaran Kunjungan Pasien</td>
        </tr>
       
        <tr>
            <td align="center" width="3%">No.</td>
            <td align="center" width="67%">Nama Layanan</td>
            <td align="center" width="10%">Nominal tarif</td>
            <td align="center" width="10%">Frekuensi</td>
            <td align="center" width="10%">Subtotal</td>
        </tr>
        
        <?php
            $total_kunjungan = 0;
            foreach ($list_kunjungan as $key => $data) { 
        ?>
        <tr>
            <td align="center"><?= ++$key ?></td>
            <td><?= $data->layanan ?></td>
            <td align="right"><?= rupiah($data->nominal) ?></td>
            <td align="right"><?= $data->frekuensi ?></td>
            <td align="right">
                <?php 
                    echo rupiah($data->nominal*$data->frekuensi); 
                    $total_kunjungan += ($data->nominal*$data->frekuensi);
                ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="4" align="right">SUM(Subtotal)</td>
            <td align="right"><?= rupiah($total_kunjungan)?></td>
        </tr>

       
         <?php $total_jasa = 0; if ($list_lain != null):?>
        
        <?php
            $arr_jenis = array();
            foreach ($list_lain as $key => $data) {
                if (!in_array($data->jenis_layanan, $arr_jenis)) {
                   $arr_jenis[] = $data->jenis_layanan;
                }
                
            }          
        ?>

        <?php 
        
        foreach ($arr_jenis as $key => $jenis_layanan): ?>
            <?php $total_sementara = 0; ?>
            <tr><td align="center" colspan="5" width="10%">&nbsp;</td></tr>
            <tr>
                <td colspan="5" style="text-align: left; background: #f4f4f4;"><?= $jenis_layanan ?></td>
            </tr>
            <tr>
                <td align="center" width="10%">No.</td>
                <td align="center" width="20%">Nama Layanan</td>
                <td align="center" width="10%">Nominal tarif</td>
                <td align="center" width="10%">Frekuensi</td>
                <td align="center" width="10%">Subtotal</td>
            </tr>
            <?php 
            $no = 1;
            foreach ($list_lain as $num => $data): ?>
                <?php 
                
                if ($data->jenis_layanan == $jenis_layanan): ?>
                <tr>
                    <td align="center"><?= $no++ ?></td>
                    <td><?= $data->layanan ?></td>
                    <td align="right"><?= rupiah($data->nominal) ?></td>
                    <td align="right"><?= $data->frekuensi ?></td>
                    <td align="right">
                        <?php 
                            echo rupiah($data->nominal*$data->frekuensi); 
                            $total_sementara += ($data->nominal*$data->frekuensi);
                            $total_jasa += ($data->nominal*$data->frekuensi);
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right">SUM(Subtotal)</td>
                <td align="right"><?= rupiah($total_sementara)?></td>
            </tr>
        <?php endforeach; ?>
            
      
         <?php endif; ?>

        <tr><td align="center" colspan="5" width="10%">&nbsp;</td></tr>
        <tr>
            <td colspan="5" style="text-align: left; background: #f4f4f4;">Akomodasi Kamar Inap</td>
        </tr>
         <tr>
            <td align="center" width="10%">No.</td>
            <td align="center" width="20%">Nama Layanan</td>
            <td align="center" width="10%">Nominal tarif</td>
            <td align="center" width="10%">Frekuensi</td>
            <td align="center" width="10%">Subtotal</td>
        </tr>
        <?php
            $total_inap = 0;
            foreach ($list_inap as $key => $data) {
        ?>
        <tr>
            <td align="center"><?= ++$key ?></td>
            <td><?= $data->layanan ?></td>
            <td align="right"><?= rupiah($data->nominal) ?></td>
            <td align="right">
                <?php
                        
                        if ($data->keluar_waktu != null) {
                            $durasi['day'] = $data->frekuensi;
                             echo $durasi['day'];
                        } else {
                            $durasi = get_duration($data->masuk_waktu, date('Y-m-d H:i:s'));
                            if ($durasi['day'] == 0) {
                            $durasi['day']++;
                            } else if ($durasi['hour'] > 0) {
                                $durasi['day']++;
                            }
                            echo $durasi['day'];
                        }
                ?>
            </td>
            <td align="right">
                <?php 
                    if($data->keluar_waktu == ''){
                        echo rupiah($durasi['day']*$data->nominal);
                        $total_inap += ($durasi['day']*$data->nominal);
                    }else{
                        echo rupiah($data->nominal*$data->frekuensi); 
                        $total_inap += ($data->nominal*$data->frekuensi);
                    }                    
                ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="4" align="right">SUM(Subtotal)</td>
            <td align="right"><?= rupiah($total_inap)?></td>
        </tr>
        

        <tr>
            <td align="center" colspan="5" width="10%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: left; background: #f4f4f4;">Pemakaian Obat / Barang</td>
        </tr>

        <tr>
            <td align="center" width="10%">No.</td>
            <td align="center" width="60%">Kemasan</td>
            <td align="center" wudth="10%">Harga</td>
            <td align="center" width="10%">Jumlah</td>
            <td align="center" width="10%">Subtotal</td>
        </tr>
        <?php 
            $total_barang = 0;
            foreach ($list_data as $key => $data) {
            $retur = $this->db->query("select dr.qty as masuk from penjualan p join retur_penjualan rp on (p.id = rp.id_penjualan) join detail_retur_penjualan dr on (rp.id = dr.id_retur_penjualan) where rp.id = '".$data->id_retur."' and dr.id_kemasan = '".$data->id_pb."'")->row();
            $balik = isset($retur->masuk)?$retur->masuk:'0';
            $harga_jual = $data->hna + ($data->hna * $data->margin / 100) - ($data->hna * ($data->diskon / 100));
            //$harga_jual = $harga_kotor + ($harga_kotor*$data->ppn_jual/100);
            $subtotal =  ($data->keluar-$balik)*$harga_jual;
        ?>
        <tr>
            <td align="center"><?= ++$key ?></td>
            <td>
                <?= $data->barang ?> <?= ($data->kekuatan == '1') ? '' : $data->kekuatan ?> <?= $data->satuan ?> <?= $data->sediaan ?> <?= (($data->generik == 'Non Generik') ? '' : $data->pabrik) ?> <?= (($data->isi == '1') ? '' : $data->isi) ?> <?= $data->satuan_terkecil ?>
            </td>
            <td align="right"><?= rupiah($harga_jual) ?></td>
            <td align="center"><?= $data->keluar-$balik ?></td>
            <td align="right">
                <?php 
                    $total_barang =  $subtotal + $total_barang;
                    echo rupiah($subtotal);
                ?>
            </td>
        </tr>
        <?php } ?>
     
        <tr>
            <td align="right" colspan="4">SUM(Subtotal)</td>
            <td align="right">
                <?php 
                    echo rupiah($total_barang);
                ?>
            </td>
        </tr>
        <tr>
            <td align="center" colspan="5" width="10%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: left; background: #f4f4f4;">Jasa APOTEKER</td>
        </tr>

        <tr>
            <td align="center" width="10%">No.</td>
            <td align="center" width="60%">Keterangan</td>
            <td align="center" wudth="10%"></td>
            <td align="center" width="10%"></td>
            <td align="center" width="10%">Subtotal</td>
        </tr>
        <tr>
            <td align="center">1</td>
            <td>JASA APOTEKER</td>
            <td></td>
            <td></td>
            <td align="right"><?= rupiah($jasa_apoteker->total_jasa_apt) ?></td>
        </tr>
        <tr>
            <td align="right" colspan="4">SUM(Subtotal)</td>
            <td align="right">
                <?php 
                    echo rupiah($jasa_apoteker->total_jasa_apt);
                ?>
            </td>
        </tr>
        <tr>
            <td align="right" colspan="4"><h2>Total Tagihan</h2></td>
            <td width="10%" align="right"><h2><?php
            $total_ta = $total_barang+$total_jasa+$total_inap+$total_kunjungan+$jasa_apoteker->total_jasa_apt;
            echo rupiah($total_ta) ?></h2></td> 
        </tr>
        <tr>
            <td align="right" colspan="4"><h2>Bayar</h2></td>
            <td width="10%" align="right"><h2><?= rupiah($bayar->total_pembayaran) ?></h2></td> 
        </tr>
        <tr>
            <td align="right" colspan="4"><h2>Sisa Tagihan</h2></td>
            <td width="10%" align="right"><h2>
                <?php
                    echo rupiah($total_ta-$bayar->total_pembayaran);
                ?>
         </h2></td> 
        </tr>
    </table>
</div>