<title><?= $title ?></title>
<script type="text/javascript">
$(function() {
    load_data_neraca();
    $('#tampil').click();
    $('#tabs').tabs();
    $('#awal,#akhir').datepicker({
        changeYear: true,
        changeMonth: true
    });
    $('#tampil').button({
        icons: {
            secondary: 'ui-icon-search'
        }
    });
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
    $('#reset').button({
        icons: {
            secondary: 'ui-icon-refresh'
        }
    }).click(function() {
        $('#loaddata').empty().load('<?= base_url('laporan/neraca') ?>');
    });
});

function load_data_neraca() {
    $.ajax({
        url: '<?= base_url('laporan/neraca_load_data') ?>',
        cache: false,
        beforeSend: function() {
            $('#loading').show();
        },
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