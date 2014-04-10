<script type="text/javascript">
    $(function() {
        $('#tabs').tabs();
        get_list_shu();
        $('#cetak').button({
            icons: {
                secondary: 'ui-icon-print'
            }
        }).click(function() {
            var wWidth = $(window).width();
            var dWidth = wWidth * 1;
            var wHeight= $(window).height();
            var dHeight= wHeight * 1;
            var x = screen.width/2 - dWidth/2;
            var y = screen.height/2 - dHeight/2;
            window.open('<?= base_url('laporan/neraca_load_data') ?>?do=cetak', 'Penerimaan', 'width='+dWidth+', height='+dHeight+', left='+x+',top='+y);
        });
    });
    function get_list_shu() {
        $.ajax({
            url: '<?= base_url('akuntansi/load_shu') ?>',
            data: '',
            success: function(data) {
                $('#result').html(data);
            }
        });
    }
</script>
<div class="titling"><h1><?= $title ?></h1></div>
<div class="kegiatan">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Parameter</a></li>
        </ul>
        <div id="tabs-1">
            <div id="result"></div>
            <center><?= form_button(null, 'Cetak', 'id=cetak') ?></center>
        </div>
    </div>
</div>
