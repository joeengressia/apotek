<?php $this->load->view('message') ?>
<title><?= $title ?></title>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <script type="text/javascript">
        $(function() {
            $('#tabs').tabs();
            $('button[id=reset]').click(function() {
                var url = '<?= base_url('billing/laporan') ?>';
                $('#loaddata').load(url);
            });
            $('#shows').each(function(){
                $(this).replaceWith('<button type="' + $(this).attr('type') + '" name="'+$(this).attr('name')+'" id="'+$(this).attr('id')+'">' + $(this).val() + '</button>');
            });
            $('#shows').button({
                icons: {
                    secondary: 'ui-icon-circle-check'
                }
            });
            $('button[id=reset]').button({
                icons: {
                    secondary: 'ui-icon-refresh'
                }
            });
            
            $('#cetak').button({
                icons: {
                    secondary: 'ui-icon-print'
                }
            }).click(function() {
                cetak_rekap();
            });
        
            $('#shows').focus();
            $('#awal').datetimepicker({
                changeYear : true,
                changeMonth : true 
            });
            $('#akhir').datetimepicker({
                changeYear : true,
                changeMonth : true
            });
            $('#tidak').click(function() {
                $('#awal,#akhir').val('').attr('disabled','disabled');
            });
            $('#lunas,#belum').click(function() {
                $('#awal,#akhir').removeAttr('disabled');
            });
            $('#shows').click(function() {
                paging(1);
            });
        });
        
        function cetak_rekap() {
            var wWidth = $(window).width();
            var dWidth = wWidth * 1;
            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            var x = screen.width/2 - dWidth/2;
            var y = screen.height/2 - dHeight/2;
            var awal = $('#awal').val();
            var akhir= $('#akhir').val();
            var pembayaran = '';
            if ($('#tidak').is(':checked')) { pembayaran = 'tidak'; }
            if ($('#lunas').is(':checked')) { pembayaran = 'lunas'; }
            if ($('#belum').is(':checked')) { pembayaran = 'belum'; }
            window.open('<?= base_url('billing/laporan_load_data') ?>?do=print&awal='+awal+'&akhir='+akhir+'&pembayaran='+pembayaran,'Resep Cetak','width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
        }

        function paging(page, tab, cari){
            var awal = $('#awal').val();
            var akhir= $('#akhir').val();
            var pembayaran = '';
            if ($('#tidak').is(':checked')) { pembayaran = 'tidak'; }
            if ($('#lunas').is(':checked')) { pembayaran = 'lunas'; }
            if ($('#belum').is(':checked')) { pembayaran = 'belum'; }
            $.ajax({
                url: '<?= base_url('billing/laporan_load_data/') ?>/'+page+'?awal='+awal+'&akhir='+akhir+'&pembayaran='+pembayaran,
                cache: false,
                success: function(data) {
                    $('#results').html(data);
                }
            });
        }

        function pembayaran(no_daftar){
            $.ajax({
                url: '<?= base_url("billing/pembayaran") ?>/'+no_daftar,
                cache: false,
                success: function(data) {
                    $('#loaddata').html(data);
                }
            });
        }
    </script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Laporan</a></li>
    </ul>
    <div id="tabs-1">
        <table width="100%" class="inputan">
            <tr><td>Waktu:</td><td><?= form_input('awal', isset($_GET['awal']) ? $_GET['awal'] : date("d/m/Y H:i"), 'id=awal style="width: 90px;"') ?>  s.d  <?= form_input('akhir', isset($_GET['awal']) ? $_GET['awal'] : date("d/m/Y H:i"), 'id=akhir style="width: 90px;"') ?></td></tr>
            <tr><td>Pembayaran:</td><td>
                <?= form_radio('bayar', 'tidak', '', 'id=tidak') ?> Belum Bayar</span>
                <?= form_radio('bayar', 'lunas', '', 'id=lunas') ?> Lunas</span>
                <?= form_radio('bayar', 'belum', true, 'id=belum') ?> Belum Lunas</span>
            </td></tr>
            <tr><td></td><td><?= form_submit('tampilkan', 'Tampilkan', 'id=shows') ?> <?= form_button(NULL, 'Reset', 'id=reset') ?> <?= form_button(NULL, 'Cetak', 'id=cetak') ?></td></tr>
        </table>
        <div id="results"></div>
    </div>
</div>
</div>
<?php die; ?>