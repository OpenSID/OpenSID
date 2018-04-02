<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $id; ?></title>
	<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=utf-8">
	<meta name="ProgId" content="Arcapada.Notepad.HTML.Editor" />
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
	<link rel="stylesheet" type="text/css" href="/css/siteman_styles.css" />
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" />
	<link rel="stylesheet" href="/css/siteman_print.css" type="text/css" media="print" />
</head>
<body>
<TABLE FRAME=VOID CELLSPACING=0 COLS=11 RULES=NONE BORDER=0>
	<COLGROUP><COL WIDTH=29><COL WIDTH=24><COL WIDTH=79><COL WIDTH=77><COL WIDTH=77><COL WIDTH=77><COL WIDTH=79><COL WIDTH=77><COL WIDTH=77><COL WIDTH=77><COL WIDTH=29></COLGROUP>
		<?php echo $content;?>
	<TBODY>
		<TR>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3" WIDTH=29 HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=24 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=79 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=79 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3" WIDTH=77 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" WIDTH=29 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=22 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD COLSPAN=9 ALIGN=CENTER><B><FONT FACE="Times New Roman" SIZE=3>KARTU KELUARGA</FONT></B></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD COLSPAN=9 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>NO. </FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Nama Kepala Keluarga</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1><?php echo ucwords($this->setting->sebutan_kecamatan)?></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Alamat</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Kabupaten / Kota</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Rt / Rw</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Kode pos</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Kelurahan / Desa</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT VALIGN=MIDDLE><FONT SIZE=1>Propinsi</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>:</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>No.</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Nama Lengkap</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>NIK/NKS</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Jenis Kelamin</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Tempat Lahir</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Tanggal Lahir</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Agama</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Pendidikan</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Pekerjaan</FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="1" SDNUM="1033;"><FONT SIZE=1>1</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="2" SDNUM="1033;"><FONT SIZE=1>2</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="3" SDNUM="1033;"><FONT SIZE=1>3</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="4" SDNUM="1033;"><FONT SIZE=1>4</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="5" SDNUM="1033;"><FONT SIZE=1>5</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="6" SDNUM="1033;"><FONT SIZE=1>6</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="7" SDNUM="1033;"><FONT SIZE=1>7</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="8" SDNUM="1033;"><FONT SIZE=1>8</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="9" SDNUM="1033;"><FONT SIZE=1>9</FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>NO.</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Status Perkawinan</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Status Hubungan dalam keluarga</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Kewarganegaraan</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Dokumen Imigrasi</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1>Nama Orang tua</FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>No. Paspor</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>No. KITAS / KITAP</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Ayah</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1>Ibu</FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="10" SDNUM="1033;"><FONT SIZE=1>10</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE SDVAL="11" SDNUM="1033;"><FONT SIZE=1>11</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="12" SDNUM="1033;"><FONT SIZE=1>12</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="13" SDNUM="1033;"><FONT SIZE=1>13</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="14" SDNUM="1033;"><FONT SIZE=1>14</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="15" SDNUM="1033;"><FONT SIZE=1>15</FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER SDVAL="16" SDNUM="1033;"><FONT SIZE=1>16</FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><BR></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-top: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><BR></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=CENTER><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=18 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD COLSPAN=2 ALIGN=CENTER VALIGN=MIDDLE>........,........200..</TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT>Dikeluarkan Tanggal:</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT>Kepala Keluarga,</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><?php echo ucwords($this->setting->sebutan_camat)?> / lurah /kepala desa</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>LEMBAR :</FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>I. KEPADA KELUARGA</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>ii. RT</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>iii. Desa / Kelurahan</FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1><BR></FONT></TD>
			<TD ALIGN=LEFT><FONT SIZE=1>iv. <?php echo ucwords($this->setting->sebutan_kecamatan)?></FONT></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT>Tanda tangan/Cap Jempol</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD ALIGN=LEFT>NIP.</TD>
			<TD ALIGN=LEFT><BR></TD>
			<TD STYLE="border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
		<TR>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-left: 1px solid #D3D3D3" HEIGHT=17 ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
			<TD STYLE="border-bottom: 1px solid #D3D3D3; border-right: 1px solid #D3D3D3" ALIGN=LEFT><BR></TD>
		</TR>
	</TBODY>
</TABLE>
</body>
</html>
