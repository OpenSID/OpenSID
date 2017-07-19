<?php

/********************************
 * The following commented code can be uncommented if you wish
 * to quickly find information about your system's MIME setup.
 *
 * Simply remove the "REMOVE ME TO TEST" lines below to have
 * the code run when this file runs.
 *
 * In addition to these tests, you'll find some commented code
 * at the bottom of the file that can be used to test the
 * function.
 *
 * Run the code with this command:
 * php mime_type_lib.php
 ********************************/


/* REMOVE ME TO TEST
echo "magicfile: " . ini_get( 'mime_magic.magicfile' ) . "\n";

if ( function_exists( 'finfo_open' ) )
	echo "Found finfo_open function\n";
else
	echo "Did not find finfo_open function\n";

if ( function_exists( 'mime_content_type' ) )
	echo "Found mime_content_type function\n";
else
	echo "Did not find mime_content_type function\n";
REMOVE ME TO TEST */

$mime_types = array(
	'ai'      => 'application/postscript',
	'aif'     => 'audio/x-aiff',
	'aifc'    => 'audio/x-aiff',
	'aiff'    => 'audio/x-aiff',
	'asc'     => 'text/plain',
	'asf'     => 'video/x-ms-asf',
	'asx'     => 'video/x-ms-asf',
	'au'      => 'audio/basic',
	'avi'     => 'video/x-msvideo',
	'bcpio'   => 'application/x-bcpio',
	'bin'     => 'application/octet-stream',
	'bmp'     => 'image/bmp',
	'bz2'     => 'application/x-bzip2',
	'cdf'     => 'application/x-netcdf',
	'chrt'    => 'application/x-kchart',
	'class'   => 'application/octet-stream',
	'cpio'    => 'application/x-cpio',
	'cpt'     => 'application/mac-compactpro',
	'csh'     => 'application/x-csh',
	'css'     => 'text/css',
	'dcr'     => 'application/x-director',
	'dir'     => 'application/x-director',
	'djv'     => 'image/vnd.djvu',
	'djvu'    => 'image/vnd.djvu',
	'dll'     => 'application/octet-stream',
	'dms'     => 'application/octet-stream',
	'dvi'     => 'application/x-dvi',
	'dwg'     => 'image/vnd.dwg',
	'dxr'     => 'application/x-director',
	'eps'     => 'application/postscript',
	'etx'     => 'text/x-setext',
	'exe'     => 'application/octet-stream',
	'ez'      => 'application/andrew-inset',
	'flv'     => 'video/x-flv',
	'gif'     => 'image/gif',
	'gtar'    => 'application/x-gtar',
	'gz'      => 'application/x-gzip',
	'hdf'     => 'application/x-hdf',
	'hqx'     => 'application/mac-binhex40',
	'htm'     => 'text/html',
	'html'    => 'text/html',
	'ice'     => 'x-conference/x-cooltalk',
	'ief'     => 'image/ief',
	'iges'    => 'model/iges',
	'igs'     => 'model/iges',
	'img'     => 'application/octet-stream',
	'iso'     => 'application/octet-stream',
	'jad'     => 'text/vnd.sun.j2me.app-descriptor',
	'jar'     => 'application/x-java-archive',
	'jnlp'    => 'application/x-java-jnlp-file',
	'jpe'     => 'image/jpeg',
	'jpeg'    => 'image/jpeg',
	'jpg'     => 'image/jpeg',
	'js'      => 'application/x-javascript',
	'kar'     => 'audio/midi',
	'kil'     => 'application/x-killustrator',
	'kpr'     => 'application/x-kpresenter',
	'kpt'     => 'application/x-kpresenter',
	'ksp'     => 'application/x-kspread',
	'kwd'     => 'application/x-kword',
	'kwt'     => 'application/x-kword',
    'kml'     => 'application/vnd.google-earth.kml+xml',
    'kmz'     => 'application/vnd.google-earth.kmz',
	'latex'   => 'application/x-latex',
	'lha'     => 'application/octet-stream',
	'lzh'     => 'application/octet-stream',
	'm3u'     => 'audio/x-mpegurl',
	'man'     => 'application/x-troff-man',
	'me'      => 'application/x-troff-me',
	'mesh'    => 'model/mesh',
	'mid'     => 'audio/midi',
	'midi'    => 'audio/midi',
	'mif'     => 'application/vnd.mif',
	'mov'     => 'video/quicktime',
	'movie'   => 'video/x-sgi-movie',
	'mp2'     => 'audio/mpeg',
	'mp3'     => 'audio/mpeg',
	'mp4'     => 'video/mp4',
	'mpe'     => 'video/mpeg',
	'mpg'     => 'video/mpeg',
	'mpeg'    => 'video/mpeg',
	'ms'      => 'application/x-troff-ms',
	'msh'     => 'model/mesh',
	'mxu'     => 'video/vnd.mpegurl',
	'nc'      => 'application/x-netcdf',
	'odb'     => 'application/vnd.oasis.opendocument.database',
	'odc'     => 'application/vnd.oasis.opendocument.chart',
	'odf'     => 'application/vnd.oasis.opendocument.formula',
	'odg'     => 'application/vnd.oasis.opendocument.graphics',
	'odi'     => 'application/vnd.oasis.opendocument.image',
	'odm'     => 'application/vnd.oasis.opendocument.text-master',
	'odp'     => 'application/vnd.oasis.opendocument.presentation',
	'ods'     => 'application/vnd.oasis.opendocument.spreadsheet',
	'odt'     => 'application/vnd.oasis.opendocument.text',
	'ogg'     => 'application/ogg',
	'otg'     => 'application/vnd.oasis.opendocument.graphics-template',
	'oth'     => 'application/vnd.oasis.opendocument.text-web',
	'otp'     => 'application/vnd.oasis.opendocument.presentation-template',
	'ots'     => 'application/vnd.oasis.opendocument.spreadsheet-template',
	'ott'     => 'application/vnd.oasis.opendocument.text-template',
	'pbm'     => 'image/x-portable-bitmap',
	'pdb'     => 'chemical/x-pdb',
	'pdf'     => 'application/pdf',
	'pgm'     => 'image/x-portable-graymap',
	'pgn'     => 'application/x-chess-pgn',
	'php'     => 'text/x-php',
	'png'     => 'image/png',
	'pnm'     => 'image/x-portable-anymap',
	'ppm'     => 'image/x-portable-pixmap',
	'ppt'     => 'application/vnd.ms-powerpoint',
	'ps'      => 'application/postscript',
	'qt'      => 'video/quicktime',
	'ra'      => 'audio/x-realaudio',
	'ram'     => 'audio/x-pn-realaudio',
	'ras'     => 'image/x-cmu-raster',
	'rgb'     => 'image/x-rgb',
	'rm'      => 'audio/x-pn-realaudio',
	'roff'    => 'application/x-troff',
	'rpm'     => 'application/x-rpm',
	'rtf'     => 'text/rtf',
	'rtx'     => 'text/richtext',
	'sgm'     => 'text/sgml',
	'sgml'    => 'text/sgml',
	'sh'      => 'application/x-sh',
	'shar'    => 'application/x-shar',
	'silo'    => 'model/mesh',
	'sis'     => 'application/vnd.symbian.install',
	'sit'     => 'application/x-stuffit',
	'skd'     => 'application/x-koan',
	'skm'     => 'application/x-koan',
	'skp'     => 'application/x-koan',
	'skt'     => 'application/x-koan',
	'smi'     => 'application/smil',
	'smil'    => 'application/smil',
	'snd'     => 'audio/basic',
	'svg'     => 'image/svg+xml',
	'so'      => 'application/octet-stream',
	'spl'     => 'application/x-futuresplash',
	'src'     => 'application/x-wais-source',
	'stc'     => 'application/vnd.sun.xml.calc.template',
	'std'     => 'application/vnd.sun.xml.draw.template',
	'sti'     => 'application/vnd.sun.xml.impress.template',
	'stw'     => 'application/vnd.sun.xml.writer.template',
	'sv4cpio' => 'application/x-sv4cpio',
	'sv4crc'  => 'application/x-sv4crc',
	'swf'     => 'application/x-shockwave-flash',
	'sxc'     => 'application/vnd.sun.xml.calc',
	'sxd'     => 'application/vnd.sun.xml.draw',
	'sxg'     => 'application/vnd.sun.xml.writer.global',
	'sxi'     => 'application/vnd.sun.xml.impress',
	'sxm'     => 'application/vnd.sun.xml.math',
	'sxw'     => 'application/vnd.sun.xml.writer',
	't'       => 'application/x-troff',
	'tar'     => 'application/x-tar',
	'tcl'     => 'application/x-tcl',
	'tex'     => 'application/x-tex',
	'texi'    => 'application/x-texinfo',
	'texinfo' => 'application/x-texinfo',
	'tgz'     => 'application/x-gzip',
	'tif'     => 'image/tiff',
	'tiff'    => 'image/tiff',
	'torrent' => 'application/x-bittorrent',
	'tr'      => 'application/x-troff',
	'tsv'     => 'text/tab-separated-values',
	'txt'     => 'text/plain',
	'ustar'   => 'application/x-ustar',
	'vcd'     => 'application/x-cdlink',
	'vrml'    => 'model/vrml',
	'wav'     => 'audio/x-wav',
	'wax'     => 'audio/x-ms-wax',
	'wbmp'    => 'image/vnd.wap.wbmp',
	'wbxml'   => 'application/vnd.wap.wbxml',
	'wm'      => 'video/x-ms-wm',
	'wma'     => 'audio/x-ms-wma',
	'wml'     => 'text/vnd.wap.wml',
	'wmlc'    => 'application/vnd.wap.wmlc',
	'wmls'    => 'text/vnd.wap.wmlscript',
	'wmlsc'   => 'application/vnd.wap.wmlscriptc',
	'wmv'     => 'video/x-ms-wmv',
	'wmx'     => 'video/x-ms-wmx',
	'wrl'     => 'model/vrml',
	'wvx'     => 'video/x-ms-wvx',
	'xbm'     => 'image/x-xbitmap',
	'xht'     => 'application/xhtml+xml',
	'xhtml'   => 'application/xhtml+xml',
	'xml'     => 'text/xml',
	'xml'     => 'application/xml',
	'xpm'     => 'image/x-xpixmap',
	'xsl'     => 'text/xsl',
	'xwd'     => 'image/x-xwindowdump',
	'xyz'     => 'chemical/x-xyz',
	'zip'     => 'application/zip',
	'doc'      => 'application/msword',
	'docx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'dotx'     => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'docm'     => 'application/vnd.ms-word.document.macroEnabled.12',
	'xls'      => 'application/vnd.ms-excel',
	'xlsx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xltx'     => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xlsm'     => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xltm'     => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam'     => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'xlsb'     => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'ppt'      => 'application/vnd.ms-powerpoint',
	'pptx'     => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'potx'     => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'ppsx'     => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppam'     => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'pptm'     => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'potm'     => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppsm'     => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
);

if ( ! function_exists('get_extension_from_mime'))
{
	function get_extension_from_mime($mime){
		global $mime_types;
		if(strpos($mime, ';')!==FALSE){
			$mime = substr($mime, 0,strpos($mime, ';'));
		}
		$mime_types_conv = array_flip($mime_types);
		if(isset($mime_types_conv[$mime])){
			return $mime_types_conv[$mime];
		}
		return '';
	}
}

if ( ! function_exists('get_file_mime_type'))
{
	function get_file_mime_type($filename, $debug = false)
	{
		if (function_exists('finfo_open') && function_exists('finfo_file') && function_exists('finfo_close'))
		{
			$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime_type = finfo_file($fileinfo, $filename);
			finfo_close($fileinfo);

			if ( ! empty($mime_type))
			{
				if (true === $debug)
				{
					return array( 'mime_type' => $mime_type, 'method' => 'fileinfo' );
				}

				return $mime_type;
			}
		}

		if (function_exists('mime_content_type'))
		{
			$mime_type = mime_content_type($filename);

			if ( ! empty($mime_type))
			{
				if (true === $debug)
				{
					return array( 'mime_type' => $mime_type, 'method' => 'mime_content_type' );
				}

				return $mime_type;
			}
		}

		global $mime_types;

		$tmp_array = explode('.', $filename);
		$ext = strtolower(array_pop($tmp_array));

		if ( ! empty($mime_types[ $ext ]))
		{
			if (true === $debug)
			{
				return array( 'mime_type' => $mime_types[ $ext ], 'method' => 'from_array' );
			}

			return $mime_types[ $ext ];
		}

		if (true === $debug)
		{
			return array( 'mime_type' => 'application/octet-stream', 'method' => 'last_resort' );
		}

		return 'application/octet-stream';
	}
}


/********************
 * The following code can be used to test the function.
 * First put a plain text file named "test.txt" and a
 * JPEG image file named "image.jpg" in the same folder
 * as this file.
 *
 * Simply remove the "REMOVE ME TO TEST" lines below to have
 * the code run when this file runs.
 *
 * Run the code with this command:
 * php mime_type_lib.php
 ********************/


/* REMOVE ME TO TEST
echo get_file_mime_type( 'test.txt' ) . "\n";
echo print_r( get_file_mime_type( 'image.jpg', true ), true ) . "\n";
REMOVE ME TO TEST */
