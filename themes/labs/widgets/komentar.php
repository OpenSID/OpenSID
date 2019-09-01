<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea block-header-default">
        <h3 class="block-title">
            <i class="si si-bubble"></i>
             Komentar Terkini</h3>
        <div class="block-options">
        <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <marquee
            onmouseover="this.stop()"
            onmouseout="this.start()"
            scrollamount="3"
            direction="up"
            width="100%"
            height="280"
            align="center"
            behavior="”alternate”">
            <ul class="list list-activity" >
                <?php foreach($komen As $data){?>
                <li>
                    <i class="fa fa-comment fa-2x text-danger"></i>
                    <table class="table table-bordered table-striped nowrap">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th>
                                    <i class="fa fa-circle text-success"></i>
                                    <?= $data['owner']?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <font color='green'>
                                        <small><?= tgl_indo2($data['tgl_upload'])?></small>
                                    </font>
                                    <br/><?= $data['komentar']?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </li>
                <?php }?>
                
            </ul>
        </marquee>
    </div>
</div>