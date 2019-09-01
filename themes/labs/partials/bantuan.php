<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
function show_kartu_peserta(elem) {
    var id = elem.attr('target');
    var title = elem.attr('title');
    var url = elem.attr('href');
    $('#' + id + '').remove();

    $('body').append('<div id="' + id + '" title="' + title +
        '" style="display:none;position:relative;overflow:scroll;"></div>');

    $('#' + id + '').dialog({
        resizable: true,
        draggable: true,
        width: 500,
        height: 'auto',
        open: function(event, ui) {
            $('#' + id + '').load(url);
        }
    });
    $('#' + id + '').dialog('open');
}
</script>
<div class="block block-themed">
    <div class="block-header block-header-default">
        <h3 class="block-title"> Program Bantuan</h3>
    </div>
    <div class="block-content" data-toggle="slimscroll" data-height="500px" data-color="#ef5350" data-opacity="1"
        data-always-visible="true">
        <?php if(!empty($daftar_bantuan)): ?>
        <table class="table table-bordered table-vcenter">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th>ID KARTU</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_bantuan as $bantuan) : ?>
                <tr>
                    <td><?= $bantuan['nama']?></td>
                    <td><?= tgl_indo($bantuan['sdate'])?></td>
                    <td><?= tgl_indo($bantuan['edate'])?></td>
                    <td>
                        <?php if($bantuan['no_id_kartu']) : ?>
                        <button type="button" target="kartu_peserta" title="Kartu Peserta"
                            href="<?= site_url('first/kartu_peserta/'.$bantuan['id'])?>"
                            onclick="show_kartu_peserta($(this));" class="uibutton special"><span
                                class="fa fa-print">&nbsp;</span><?= $bantuan['no_id_kartu']?></button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <table class="table table-bordered table-vcenter">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Awal</th>
                    <th>Akhir</th>
                    <th>ID KARTU</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> Data Kosong </td>
                    <td> Data Kosong </td>
                    <td> Data Kosong </td>
                    <td> Data Kosong </td>
                    
                </tr>
            </tbody>
        </table>
  <?php endif; ?>
    </div>
</div>