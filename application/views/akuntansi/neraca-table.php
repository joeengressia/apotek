<?php
$border = "";
if (isset($_GET['do'])) { 
    $border = "border=0";
    
    echo "<center>LAPORAN NERACA</center>";
    echo "<link rel='stylesheet' href='".  base_url('assets/css/print-A4-landscape.css')."' />";
    echo "<script>
        window.print();    
        setTimeout(function(){ window.close();},300);
    </script>";
    echo "<script type='text/javascript'>
        $(function() {
            var tabel1 = $('#table_rekening tbody tr').length;
            var tabel2 = $('#table_rekening2 tbody tr').length;
            var selisih= Math.abs(tabel1-tabel2);
            if (tabel1 > tabel2) {
                for (i = 1; i <= selisih; i++) {
                    var str = '<tr><td colspan=4>&nbsp;</td></tr>';
                    $('#table_rekening2').append(str);
                }
            } else {
                for (i = 1; i <= selisih; i++) {
                    var str = '<tr><td colspan=4>&nbsp;</td></tr>';
                    $('#table_rekening').append(str);
                }
            }
        });
    </script>";
}
?>

<script type="text/javascript">
    $(function() {
        var tabel1 = $('#table_rekening tbody tr').length;
        var tabel2 = $('#table_rekening2 tbody tr').length;
        var selisih= Math.abs(tabel1-tabel2);
        if (tabel1 > tabel2) {
            for (i = 1; i <= selisih; i++) {
                var str = '<tr><td colspan=4>&nbsp;</td></tr>';
                $('#table_rekening2').append(str);
            }
        } else {
            for (i = 1; i <= selisih; i++) {
                var str = '<tr><td colspan=4>&nbsp;</td></tr>';
                $('#table_rekening').append(str);
            }
        }
    });
</script>
<br/><br/>
<div class="data-list">
    <table class="table-neraca" id="table_rekening" style="width: 50%; float: left;">
        <tbody>
        <?php 
        $total_aktiva = 0;
        foreach ($aktiva as $data) { ?>
        <tr>
            <td colspan="4"><?= $data->nama ?></td>
        </tr>
        <?php 
            
            $sub_rekening = $this->m_akuntansi->data_subrekening_load_data(NULL, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) { ?>
                <tr>
                    <td colspan="4" style="padding-left: 20px;"><?= strtoupper($rows->nama) ?></td>
                </tr> 
                <?php
                $total_ss_aktiva = 0;
                $sub_sub_rekening = $this->m_akuntansi->data_subsubrekening_load_data($rows->id)->result();
                    foreach ($sub_sub_rekening as $r3 => $rowx) { 
                        $totallica = $this->m_akuntansi->get_total_jurnal_by_subsub_aset($rowx->id)->row();
                        $penyusutan = $this->m_akuntansi->get_total_penyusutan_by_subsub_aset($rowx->id)->row();
                        $nilai = $totallica->total; 
                        /*$value = $totallica->total; 
                        if (cek_karakter($rowx->nama)) {
                            $nilai = '('.currency(abs($value)).')';
                        } else if (cek_karakter($rowx->nama)) { 
                            $nilai = '('.currency(abs($value)).')';
                        } else {
                            $nilai = currency($value);
                        }*/
                        ?>
                        <tr>
                            <td colspan="3" style="padding-left: 40px;"><?= $rowx->nama ?></td>
                            <td align="right"><?= currency($nilai) ?></td>
                        </tr>
                    <?php    
                        $total_ss_aktiva = $total_ss_aktiva + $nilai;
                        } ?>
                        <tr>
                            <td colspan="3" style="padding-left: 20px;">TOTAL <?= strtoupper($rows->nama) ?></td>
                            <td align="right"><?= currency($total_ss_aktiva) ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                    <?php 
                        $total_aktiva = $total_aktiva + $total_ss_aktiva;
                    }
            }?>
        </tbody>
        <tfoot>
        <tr style="font-weight: bold;">
            <td colspan="3">TOTAL AKTIVA</td>
            <td align="right"><?= currency($total_aktiva) ?></td>
        </tr>
        </tfoot>
    </table>
    <table class="table-neraca" id="table_rekening2" style="width: 50%; float: left;">
        <tbody>
        <?php 
        $total_pasiva = 0;
        foreach ($pasiva as $data) { ?>
        <tr>
            <td colspan="4"><?= $data->nama ?></td>
        </tr>
        <?php 
            $sub_rekening = $this->m_akuntansi->data_subrekening_load_data(NULL, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) { ?>
                <tr>
                    <td colspan="4" style="padding-left: 20px;"><?= strtoupper($rows->nama) ?></td>
                </tr> 
                <?php
                $total_ss_pasiva = 0;
                $sub_sub_rekening = $this->m_akuntansi->data_subsubrekening_load_data($rows->id)->result();
                    foreach ($sub_sub_rekening as $r3 => $rowx) { 
                        $totallica = $this->m_akuntansi->get_total_jurnal_by_subsub_aset($rowx->id)->row();
                        $penyusutan = $this->m_akuntansi->get_total_penyusutan_by_subsub_aset($rowx->id)->row();
                        $total_ss_pasiva = ($total_ss_pasiva+$totallica->total_kredit)-$penyusutan->total;
                        $nilai = $totallica->total_kredit;
                        /*$value = $totallica->total; 
                        if (cek_karakter($rowx->nama)) {
                            $nilai = '('.currency(abs($value)).')';
                        } else if (cek_karakter($rowx->nama)) { 
                            $nilai = '('.currency(abs($value)).')';
                        } else {
                            $nilai = currency(abs($value));
                        }*/
                        ?>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;"><?= $rowx->nama ?></td>
                        <td align="right"><?= currency($nilai) ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3" style="padding-left: 20px;">TOTAL <?= strtoupper($rows->nama) ?></td>
                    <td align="right"><?= currency($total_ss_pasiva) ?></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            <?php 
            $total_pasiva = $total_pasiva + $total_ss_pasiva;
            } ?>
        <?php } ?>
        <!-- EKUITAS Begin Here -->
        <?php
        //BAGIAN INI DIGUNAKAN UNTUK MENAMPILKAN SHU (PENDAPATAN - BEBAN) DIMASUKKAN DI REKENING "Surplus & Defisit Tahun Berjalan"
        //PENDAPATAN
        $total1 = 0;
        foreach ($pendapatan_operasional as $r1 => $data) { ?>

        <?php 
        if (isset($id_sub)) { $id_sub = $id_sub;
        } else if (isset($data->id_sub_rekening)) { $id_sub = $data->id_sub_rekening;
        } else { $id_sub = NULL; }
            $sub_rekening = $this->m_akuntansi->data_subrekening_load_data($id_sub, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) {
                $sub_sub_rekening = $this->m_akuntansi->data_subsubrekening_load_data($rows->id)->result();
                foreach ($sub_sub_rekening as $r3 => $rowx) { 
                    $total_po = $this->m_akuntansi->total_jurnal_by_sub_sub($rowx->id)->row();
                    $total1 = $total1 + $total_po->total_kredit;
                }
            } 
        }
        //BEBAN
        $total2 = 0;
        foreach ($beban_operasional as $r1 => $data) { ?>
        <?php 
        if (isset($id_sub)) { $id_sub = $id_sub;
        } else if (isset($data->id_sub_rekening)) { $id_sub = $data->id_sub_rekening;
        } else { $id_sub = NULL; }
            $sub_rekening = $this->m_akuntansi->data_subrekening_load_data($id_sub, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) { ?>
                <?php 
                $sub_sub_rekening = $this->m_akuntansi->data_subsubrekening_load_data($rows->id)->result();

                foreach ($sub_sub_rekening as $r3 => $rowx) { 
                    $total_bo = $this->m_akuntansi->total_jurnal_by_sub_sub($rowx->id)->row();

                    $total2 = $total2 + $total_bo->total_debet;
                }
            } 
        }
        $jml_laba_bersih = $total1-$total2; // ini hasilnya
        ?>
        <?php 
        $total_ekuitas = 0;
        foreach ($ekuitas as $data) { ?>
        <tr>
            <td colspan="4"><?= $data->nama ?></td>
        </tr>
        <?php 
            $sub_rekening = $this->m_akuntansi->data_subrekening_load_data(NULL, $data->id)->result();
            foreach ($sub_rekening as $r2 => $rows) { ?>
                <tr>
                    <td colspan="4" style="padding-left: 20px;"><?= strtoupper($rows->nama) ?></td>
                </tr> 
                <?php
                $total_ss_ekuitas = 0;
                $sub_sub_rekening = $this->m_akuntansi->data_subsubrekening_load_data($rows->id)->result();
                    foreach ($sub_sub_rekening as $r3 => $rowx) { 
                        $totallica = $this->m_akuntansi->get_total_jurnal_by_subsub_aset($rows->id)->row();
                        $total_ss_ekuitas = $total_ss_ekuitas+$totallica->total;
                        $nilai = $totallica->total; 
                        if ($rowx->nama === 'Surplus & Defisit Tahun Berjalan') {
                            $nilai = $jml_laba_bersih;
                        } else {
                            $nilai = $totallica->total;
                        }
                        /*$value = $totallica->total; 
                        if (cek_karakter($rowx->nama)) {
                            $nilai = '('.currency(abs($value)).')';
                        } else if (cek_karakter($rows->nama)) { 
                            $nilai = '('.currency(abs($value)).')';
                        } else {
                            $nilai = currency(abs($value));
                        }*/
                        ?>
                    <tr>
                        <td colspan="3" style="padding-left: 40px;"><?= $rowx->nama ?></td>
                        <td align="right"><?= currency($nilai) ?></td>
                    </tr>
                    <?php 
                        $total_ss_ekuitas = $total_ss_ekuitas + $nilai;
                        } ?>
                <tr>
                    <td colspan="3" style="padding-left: 20px;">TOTAL <?= strtoupper($rows->nama) ?></td>
                    <td align="right"><?= currency($total_ss_ekuitas) ?></td>
                </tr>
            <?php 
            $total_ekuitas = $total_ekuitas + $total_ss_ekuitas;
            } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="3">TOTAL PASIVA</td>
                <td align="right"><?= currency($total_ekuitas+$jml_laba_bersih+$total_pasiva) ?></td>
            </tr>
        </tfoot>
    </table>
</div>

