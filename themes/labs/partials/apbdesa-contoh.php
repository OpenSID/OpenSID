<?php
//Anggaran Pendapatan Tahun Ini
$add19          = 760071000;      		//Ketik Anggaran ADD
$dd19           = 848172000;      		//Ketik Anggaran Dana Desa
$pbh19          = 93472500+26678200;	//Ketik Anggaran PBH
$pad19          = 9867952;				//Ketik Anggaran PAD
$dll19          = 100000000;			//Ketik Anggaran DLL
$bp19			= 200000000;			//Ketik Anggaran Bantuan Provinsi
$pembiayaan19   = 329324456;			//Ketik Silpa Tahun Sebelumnya

//Realisasi Pendapatan Tahun Sebelumnya
$add        = 682242000;      			//Ketik Besaran ADD
$dd         = 737089000;      			//Ketik Besaran DD
$pbh        = 44174000+46217200;		//Ketik Besaran PBH
$pad        = 100000000;        		//Ketik Besaran PAD
$dll        = 8617690;        			//Ketik Besaran DLL
$bp         = 100000000;        		//Ketik Besaran Bantuan Provinsi
$pembiayaan = 254380746;      			//Ketik Besaran SiLPA

//Realisasi Belanja Tahun Sebelumnya
$satu       = 502790673;      			//Ketik Realisasi Bidang 1
$dua        = 720427689;      			//Ketik Realisasi Bidang 2
$tiga       = 52724000;       			//Ketik Realisasi Bidang 3
$empat      = 167453818;      			//Ketik Realisasi Bidang 4
$lima       = 0;              			//Ketik Realisasi Bidang 5

// menghitung total pendapatan
$total19 = $add19 + $dd19 + $pbh19 + $pad19 + $dll19 + $bp19 + $pembiayaan19;

// menghitung prosentase
$prosenADD19 = number_format($add19/$total19 * 100,2);
$prosenDD19 = number_format($dd19/$total19 * 100,2);
$prosenPBH19 = number_format($pbh19/$total19 * 100,2);
$prosenPAD19 = number_format($pad19/$total19 * 100,2);
$prosenDLL19 = number_format($dll19/$total19 * 100,2);
$prosenBP19 = number_format($bp19/$total19 * 100,2);
$prosenPB19 = number_format(100,2);

// menentukan panjang grafik batang berdasarkan prosentase
$panjangADD19 = $prosenADD19 * 100 / 100;
$panjangDD19 = $prosenDD19 * 100 / 100;
$panjangPBH19 = $prosenPBH19 * 100 / 100;
$panjangPAD19 = $prosenPAD19 * 100 / 100;
$panjangDLL19 = $prosenDLL19 * 100 / 100;
$panjangBP19 = $prosenBP19 * 100 / 100;
$panjangPB19 = $prosenPB19 * 100 / 100;

// menghitung total pendapatan
$total = $add + $dd + $pbh + $pad + $dll + $bp + $pembiayaan;

// menghitung prosentase
$prosenADD = number_format($add/694538200 * 100,2);		//Anggaran ADD sebagai pembagi
$prosenDD = number_format($dd/737089000 * 100,2);		//Anggaran DD sebagai pembagi
$prosenPBH = number_format($pbh/113052900 * 100,2);		//Anggaran PBH sebagai pembagi
$prosenPAD = number_format($pad/100000000 * 100,2);		//Anggaran DLL sebagai pembagi
$prosenDLL = number_format($dll/11790415 * 100,2);		//Anggaran DLL sebagai pembagi
$prosenBP = number_format($bp/100000000 * 100,2);		//Anggaran Provinsi sebagai pembagi
$prosenPB = number_format(100,2);

// menentukan panjang grafik batang berdasarkan prosentase
$panjangADD = $prosenADD * 100 / 100;
$panjangDD = $prosenDD * 100 / 100;
$panjangPBH = $prosenPBH * 100 / 100;
$panjangPAD = $prosenPAD * 100 / 100;
$panjangDLL = $prosenDLL * 100 / 100;
$panjangBP = $prosenBP * 100 / 100;
$panjangPB = $prosenPB * 100 / 100;

// menghitung total pengeluaran
$total2 = $satu + $dua + $tiga + $empat + $lima;

// menghitung prosentase
$prosenSatu = number_format($satu/579231390 * 100,2);
$prosenDua = number_format($dua/928153096 * 100,2);
$prosenTiga = number_format($tiga/55668000 * 100,2);
$prosenEmpat = number_format($empat/249632015 * 100,2);
$prosenLima = number_format($lima/2047410 * 100,2);

// menentukan panjang grafik batang berdasarkan prosentase
$panjangSatu = $prosenSatu * 100 / 100;
$panjangDua = $prosenDua * 100 / 100;
$panjangTiga = $prosenTiga * 100 / 100;
$panjangEmpat = $prosenEmpat * 100 / 100;
if ($prosenLima == 0) { $panjangLima = 0.2; }
else { $panjangLima = $prosenLima * 100 / 100; } ?>

<div class="col-md-12">
<div class="col-md-1"></div>
<div class="col-md-4">
    <div align="center"><h2>Anggaran Tahun 2019</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Anggaran Pendapatan TA 2019<br>
        <b>Rp. <?= number_format($total19); ?></b>
    </div>
    <div class="progress-group">
        Alokasi Dana Desa (ADD)<br>
		<b>Rp. <?= number_format($add19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenADD19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangADD19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Dana Desa (DD)<br>
		<b>Rp. <?= number_format($dd19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenDD19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDD19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bagi Hasil Pajak & Retribusi Daerah (PBH)<br>
		<b>Rp. <?= number_format($pbh19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenPBH19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangPBH19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pendapatan Asli Desa (PAD)<br>
		<b>Rp. <?= number_format($pad19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenPAD19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangPAD19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pendapatan Lain-Lain (DLL)<br>
		<b>Rp. <?= number_format($dll19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenDLL19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangDLL19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bantuan Provinsi<br>
		<b>Rp. <?= number_format($bp19); ?></b>
        <div class="progress progress-sm active" align="right"><small><b><?= $prosenBP19; ?>%</b></small>&nbsp;
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangBP19; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Silpa Tahun Sebelumnya<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pembiayaan19); ?></b></span>
            <small class="pull-right"><b><?= $prosenPB19; ?>%&nbsp;</b></small>
        </div>
		<div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangPB19; ?>%"></div>
        </div>
    </div><hr>
</div>
<div class="col-md-3">
    <div align="center"><h2>Pendapatan Tahun 2018</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Realisasi Pendapatan Desa TA 2018<br>
        <span class="pull-left"><b>Rp. <?php $persennya = number_format($total/2010851261 * 100,2); echo number_format($total); ?></b></span>
        <span class="pull-right"><b><?= $persennya; ?>%&nbsp;</b></span>
    </div><br>
    <div class="progress-group">
        Alokasi Dana Desa (ADD)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($add); ?></b></span>
            <small class="pull-right"><b><?= $prosenADD; ?>%&nbsp;</b></small>
        </div>
		<div class="progress sm" align="right">
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangADD; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Dana Desa (DD)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($dd); ?></b></span>
            <small class="pull-right"><b><?= $prosenDD; ?>%&nbsp;</b></small>
        </div>
		<div class="progress sm" align="right">
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDD; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bagi Hasil Pajak & Retribusi Daerah (PBH)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pbh); ?></b></span>
            <small class="pull-right"><b><?= $prosenPBH; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangPBH; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pendapatan Asli Desa (PAD)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pad); ?></b></span>
            <small class="pull-right"><b><?= $prosenPAD; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangPAD; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pendapatan Lain-Lain (DLL)<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($dll); ?></b></span>
            <small class="pull-right"><b><?= $prosenDLL; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangDLL; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bantuan Provinsi<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($bp); ?></b></span>
            <small class="pull-right"><b><?= $prosenBP; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangBP; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Silpa Tahun Sebelumnya<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($pembiayaan); ?></b></span>
            <small class="pull-right"><b><?= $prosenPB; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangPB; ?>%"></div>
        </div>
    </div><hr>
</div>
<div class="col-md-3">
    <div align="center"><h2>Belanja Tahun 2018</h2></div><hr>
    <div class="progress-group" style="margin-bottom:15px;">
        Realisasi Belanja Desa TA 2018<br>
        <span class="pull-left"><b>Rp. <?php $persennya2 = number_format($total2/1814731911 * 100,2); echo number_format($total2); ?></b></span>
        <span class="pull-right"><b><?= $persennya2; ?>%&nbsp;</b></span>
    </div><br>
    <div class="progress-group">
        Penyelenggaraan Pemerintahan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($satu); ?></b></span>
            <small class="pull-right"><b><?= $prosenSatu; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $panjangSatu; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pembangunan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($dua); ?></b></span>
            <small class="pull-right"><b><?= $prosenDua; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" style="width: <?= $panjangDua; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pembinaan Kemasyarakatan Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($tiga); ?></b></span>
            <small class="pull-right"><b><?= $prosenTiga; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style="width: <?= $panjangTiga; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Pemberdayaan Masyarakat Desa<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($empat); ?></b></span>
            <small class="pull-right"><b><?= $prosenEmpat; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: <?= $panjangEmpat; ?>%"></div>
        </div>
    </div>
    <div class="progress-group">
        Bidang Tak Terduga<br>
        <div class="clearfix">
            <span class="pull-left"><b>Rp. <?= number_format($lima); ?></b></span>
            <small class="pull-right"><b><?= $prosenLima; ?>%&nbsp;</b></small>
        </div>
        <div class="progress sm" align="right">
            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: <?= $panjangLima; ?>%"></div>
        </div>
    </div><hr>
</div>
<div class="col-md-1"></div>
</div>