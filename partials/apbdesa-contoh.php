<?php
//Anggaran Pendapatan Tahun 2020
$pad20          = 10556000;		//Ketik Anggaran PAD 2020
$dd20           = 870918000;	//Ketik Anggaran DD 2020
$pbh20          = 144654900;	//Ketik Anggaran PBH 2020
$add20          = 751947200;	//Ketik Anggaran ADD 2020
$dll20          = 100000;		//Ketik Anggaran DLL 2020
$pembiayaan20   = 456812456;	//Ketik Anggaran SiLPA Tahun 2019
$total20 = $add20 + $dd20 + $pbh20 + $pad20;

//Anggaran Pendapatan Tahun 2019
$add19          = 793077100;	//Ketik Anggaran ADD 2019
$dd19           = 848172000;	//Ketik Anggaran DD 2019
$pbh19          = 126461900;	//Ketik Anggaran PBH 2019
$dll19          = 27425000;		//Ketik Anggaran DLL 2019
$pembiayaan19   = 329324456;	//Ketik Anggaran SiLPA Tahun 2018
$total19 = $add19 + $dd19 + $pbh19 + $dll19;

//Realisasi Pendapatan Tahun 2019
$pad        	= 27425000;		//Ketik Besaran PAD 2019
$dd         	= 848172000;	//Ketik Besaran DD 2019
$pbh        	= 78649500;		//Ketik Besaran PBH 2019
$add        	= 753261100;	//Ketik Besaran ADD 2019
$dll 			= 100000;		//Ketik Besaran DLL 2019
$total = $add + $dd + $pbh + $pad + $dll;

//Anggaran Belanja Tahun 2019
$bidangsatu       	= 672144901;	//Ketik Anggaran Bidang 1
$bidangdua        	= 1167016950;	//Ketik Anggaran Bidang 2
$bidangtiga       	= 154376207;	//Ketik Anggaran Bidang 3
$bidangempat      	= 68894815;		//Ketik Anggaran Bidang 4
$bidanglima       	= 62027583;		//Ketik Anggaran Bidang 5

//Realisasi Belanja Tahun 2019
$satu       	= 617036418;	//Ketik Realisasi Bidang 1
$dua        	= 765935770;	//Ketik Realisasi Bidang 2
$tiga       	= 146683712;	//Ketik Realisasi Bidang 3
$empat      	= 50463700;		//Ketik Realisasi Bidang 4
$lima       	= 0;			//Ketik Realisasi Bidang 5
$total2 = $satu + $dua + $tiga + $empat + $lima;

// menentukan panjang grafik batang berdasarkan prosentase
$panjangADD19 	= number_format($add19/$total19 * 100,2);
$panjangDD19 	= number_format($dd19/$total19 * 100,2);
$panjangPBH19 	= number_format($pbh19/$total19 * 100,2);
$panjangDLL19 	= number_format($dll19/$total19 * 100,2);
$panjangPB19 	= number_format(100,2);

// menghitung prosentase
$panjangADD 	= number_format($add/$add19 * 100,2);
$panjangDD 		= number_format($dd/$dd19 * 100,2);
$panjangPBH 	= number_format($pbh/$pbh19 * 100,2);
$panjangDLL 	= number_format($pad/$dll19 * 100,2);
$panjangPB 		= number_format(100,2);

// menghitung prosentase
$panjangSatu = number_format($satu/$bidangsatu * 100,2);
$panjangDua = number_format($dua/$bidangdua * 100,2);
$panjangTiga = number_format($tiga/$bidangtiga * 100,2);
$panjangEmpat = number_format($empat/$bidangempat * 100,2);
$panjangLima = number_format($lima/$bidanglima * 100,2); ?>

<div class="container">
<div class="col-md-4">
    <div align="center"><h2>Anggaran Tahun 2020</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Anggaran Pendapatan TA 2020<br>
        <b>Rp. <?= number_format($total20+$pembiayaan20); ?></b>
    </div>
    <div class="progress-group">
        Pendapatan Asli Desa (PAD) + DLL<br>
		<b>Rp. <?= number_format($pad20); ?></b> + <b>Rp. <?= number_format($dll20); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= number_format($pad20/$total20 * 100,2); ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangDLL19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Dana Desa (DD)<br>
		<b>Rp. <?= number_format($dd20); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= number_format($dd20/$total20 * 100,2); ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDD19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bagi Hasil Pajak & Retribusi Daerah (PBH)<br>
		<b>Rp. <?= number_format($pbh20); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= number_format($pbh20/$total20 * 100,2); ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangPBH19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Alokasi Dana Desa (ADD)<br>
		<b>Rp. <?= number_format($add20); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= number_format($add20/$total20 * 100,2); ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangADD19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        SiLPA Tahun Sebelumnya<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pembiayaan20); ?></b></span>
            <small class="pull-right"><b><?= number_format(100,2); ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangPB; ?>%"></div>
        </div>
    </div><hr>
</div>
<div class="col-md-4">
    <div align="center"><h2>Pendapatan Tahun 2019</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Realisasi Pendapatan Desa TA 2019<br>
        <span class="pull-left"><b>Rp. <?= number_format($total+$pembiayaan19); ?></b></span>
        <span class="pull-right"><b><?php $persennya = number_format(($total+$pembiayaan19)/($total19+$pembiayaan19) * 100,2); ?><?= $persennya; ?>%&nbsp;</b></span>
    </div><br>
    <div class="progress-group">
        Pendapatan Asli Desa (PAD) + DLL<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pad); ?> + <b>Rp. <?= number_format($dll); ?></b></b></span>
            <small class="pull-right"><b><?= $panjangDLL; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangDLL; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Dana Desa (DD)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($dd); ?></b></span>
            <small class="pull-right"><b><?= $panjangDD; ?>%&nbsp;</b></small>
        </div>
		<div class="progress sm" align="right">
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDD; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bagi Hasil Pajak & Retribusi Daerah (PBH)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pbh); ?></b></span>
            <small class="pull-right"><b><?= $panjangPBH; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangPBH; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Alokasi Dana Desa (ADD)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($add); ?></b></span>
            <small class="pull-right"><b><?= $panjangADD; ?>%&nbsp;</b></small>
        </div>
		<div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangADD; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        SiLPA Tahun Sebelumnya<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pembiayaan19); ?></b></span>
            <small class="pull-right"><b><?= $panjangPB; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangPB; ?>%"></div>
        </div>
    </div><hr>
</div>
<div class="col-md-4">
    <div align="center"><h2>Belanja Tahun 2019</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Realisasi Belanja Desa TA 2019<br>
        <span class="pull-left"><b>Rp. <?= number_format($total2); ?></b></span>
        <span class="pull-right"><b><?php $persennya2 = number_format($total2/($total+$pembiayaan19) * 100,2); ?><?= $persennya2; ?>%&nbsp;</b></span>
    </div><br>
    <div class="progress-group">
        Penyelenggaraan Pemerintahan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($satu); ?></b></span>
            <small class="pull-right"><b><?= $panjangSatu; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangSatu; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pembangunan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($dua); ?></b></span>
            <small class="pull-right"><b><?= $panjangDua; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDua; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pembinaan Kemasyarakatan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($tiga); ?></b></span>
            <small class="pull-right"><b><?= $panjangTiga; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangTiga; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pemberdayaan Masyarakat Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($empat); ?></b></span>
            <small class="pull-right"><b><?= $panjangEmpat; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangEmpat; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bidang Penanggulangan Bencana Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($lima); ?></b></span>
            <small class="pull-right"><b><?= $panjangLima; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangLima; ?>%"></div>
        </div>
    </div><hr>
</div>
</div>