<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<title>Laporan Statistik</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">

<!-- Print Body -->
<div id="body">

<table>
    <tbody><tr>
        <td align="center" >
            <img src="<?php echo LogoDesa($config['logo']);?>" alt="" style="float: left;">
            <h1>PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper($config['nama_kabupaten'])?> </h1>
            <h1 style="text-transform: uppercase;"></h1>
            <h1><?php echo strtoupper($this->setting->sebutan_kecamatan)?> <?php echo strtoupper($config['nama_kecamatan'])?> </h1>
	    <h1>DESA <?php echo strtoupper($config['nama_desa'])?></h1>
            <h1>LAPORAN STATISTIK <?php echo strtoupper($stat)?></h1>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px 20px;">
		<br>
            <table>
                <tbody><tr>
                    <td class="top" width="60%">
                        <div class="nowrap">
                            <label style="width: 100px;">Laporan. No</label>
                            <label>:</label>
                            <span><?php echo date("d-m-Y")?>-00001</span>
                        </div>
                    </td>
                    <td class="top">
                        <div class="nowrap">
                            <label style="width: 100px;">Sekertariat</label>
                            <label>:</label>
                            <span>-</span>
                        </div>
                        <div class="nowrap">
                            <label style="width: 100px;">Keterangan</label>
                            <label>:</label>
                            <span>-</span>
                        </div>
                    </td>
                </tr>
            </tbody>
			</table>
            <table class="noborder">
                <tbody><tr>
                    <td class="top">
                        <div class="nowrap">
                            <span style="width: 150px;">Melaporkan Data Statistik <?php echo $stat?></span>
                            <span></span>
                            <strong style="font-size: 13px;"></strong>
                        </div>
                        <div class="nowrap">
                            <span style="width: 150px;"></span>
                            <span></span>
                            <strong style="font-size: 14px;font-style:italic"></strong>
                        </div>
                    </td>
                </tr>
            </tbody>
			</table>
            <table class="noborder">
                <tbody><tr>
                    <td class="top">
                        <div class="nowrap">
                            <span>Dengan rincian sebagai berikut</span>
                            <span>:</span>
                        </div>
                    </td>
                </tr>
            </tbody></table>
		<br>
		<table class="border thick data">
		<thead>
            <tr class="thick">
                <th class="thick">No</th>
				<th class="thick">Statistik</th>
				<th class="thick">Jumlah</th>
				<?php  if($lap<20){?>
				<th class="thick" width="60">Laki-laki</th>
				<th class="thick" width="60">Perempuan</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td class="thick" align="center" width="2"><?php echo $data['no']?></td>
          <td class="thick"><?php echo $data['nama']?></td>
          <td class="thick"><?php echo $data['jumlah']?></td>
		  <?php  if($lap<20){?>
		  <td class="thick"><?php echo $data['laki']?></td>
          <td class="thick"><?php echo $data['perempuan']?></td>
		  <?php }?>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
		<br>
            <table class="noborder">
                <tbody><tr>
                    <td class="top">
                        <div class="nowrap">
                            <label>Hasil rekaman statistik data <?php echo strtolower($stat)?> pada tanggal</label>
                            <label>:</label>
                           <strong><?php echo tgl_indo(date("Y m d"))?></strong>
                        </div>
                    </td>
                </tr>
            </tbody></table>
		<br>
            <table class="noborder">
                <tbody><tr>
                    <td class="top" align="center" width="30%">
                        <div class="nowrap"><label></label></div>
                        <div class="nowrap"><label><br></label></div>
                        <div style="height: 70px;"></div>
                        <div class="nowrap"><strong style="text-transform: uppercase;"></strong></div>
                        <div class="nowrap"><label></label></div>
                    </td>
                    <td class="top" align="center" width="40%">
                        <div class="nowrap"><label>&nbsp;</label></div>
                        <div class="nowrap"><label><br></label></div>
                    </td>
                    <td class="top" align="center" width="30%">
                        <div class="nowrap"><label>&nbsp;</label></div>
                        <div class="nowrap"><label>Perangkat desa /<br>Kepala desa</label></div>
                        <div style="height: 50px;"></div>
                        <div class="nowrap"><strong style="text-transform: uppercase;"></strong></div>
                        <div class="nowrap"><label>NIP. </label></div>
                    </td>
                </tr>
            </tbody></table>
            <br>
            <table class="noborder">
                <tbody><tr>
                    <td class="top">
                        <div class="nowrap" style="font-style:italic;">
                            <label>Catatan</label>
                            <label>:</label>
                            <strong>-</strong>
                        </div>
                    </td>
                </tr>
            </tbody></table>

        </td>
    </tr>
</tbody></table>
</div>
   <label>Tanggal cetak : &nbsp; </label><?php echo tgl_indo(date("Y m d"))?>
</div>

</body></html>
