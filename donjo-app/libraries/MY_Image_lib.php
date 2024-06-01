<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

class MY_Image_lib extends CI_Image_lib
{
    /**
     * Override initialize method
     * 
	 * initialize image preferences
	 *
	 * @param	array
	 * @return	bool
	 */
	public function initialize($props = array())
	{
		// Convert array elements into class variables
		if (count($props) > 0)
		{
			foreach ($props as $key => $val)
			{
				if (property_exists($this, $key))
				{
					if (in_array($key, array('wm_font_color', 'wm_shadow_color'), TRUE))
					{
						if (preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $val, $matches))
						{
							/* $matches[1] contains our hex color value, but it might be
							 * both in the full 6-length format or the shortened 3-length
							 * value.
							 * We'll later need the full version, so we keep it if it's
							 * already there and if not - we'll convert to it. We can
							 * access string characters by their index as in an array,
							 * so we'll do that and use concatenation to form the final
							 * value:
							 */
							$val = (strlen($matches[1]) === 6)
								? '#'.$matches[1]
								: '#'.$matches[1][0].$matches[1][0].$matches[1][1].$matches[1][1].$matches[1][2].$matches[1][2];
						}
						else
						{
							continue;
						}
					}
					elseif (in_array($key, array('width', 'height'), TRUE) && ! ctype_digit((string) $val))
					{
						continue;
					}

					$this->$key = $val;
				}
			}
		}

		// Is there a source image? If not, there's no reason to continue
		if ($this->source_image === '')
		{
			$this->set_error('imglib_source_image_required');
			return FALSE;
		}

		/* Is getimagesize() available?
		 *
		 * We use it to determine the image properties (width/height).
		 * Note: We need to figure out how to determine image
		 * properties using ImageMagick and NetPBM
		 */
		if ( ! function_exists('getimagesize'))
		{
			$this->set_error('imglib_gd_required_for_props');
			return FALSE;
		}

		$this->image_library = strtolower($this->image_library);

		/* Set the full server path
		 *
		 * The source image may or may not contain a path.
		 * Either way, we'll try use realpath to generate the
		 * full server path in order to more reliably read it.
		 */
		if (($full_source_path = realpath($this->source_image)) !== FALSE)
		{
			$full_source_path = str_replace('\\', '/', $full_source_path);
		}
		else
		{
			$full_source_path = $this->source_image;
		}

		$x = explode('/', $full_source_path);
		$this->source_image = end($x);
		$this->source_folder = str_replace($this->source_image, '', $full_source_path);

		// Set the Image Properties
		if ( ! $this->get_image_properties($this->source_folder.$this->source_image))
		{
			return FALSE;
		}

		/*
		 * Assign the "new" image name/path
		 *
		 * If the user has set a "new_image" name it means
		 * we are making a copy of the source image. If not
		 * it means we are altering the original. We'll
		 * set the destination filename and path accordingly.
		 */
		if ($this->new_image === '')
		{
			$this->dest_image  = $this->source_image;
			$this->dest_folder = $this->source_folder;
		}
		elseif (strpos($this->new_image, '/') === FALSE && strpos($this->new_image, '\\') === FALSE)
		{
			$this->dest_image  = $this->new_image;
			$this->dest_folder = $this->source_folder;
		}
		else
		{
			// Is there a file name?
			if ( ! preg_match('#\.(jpg|jpeg|gif|png|webp)$#i', $this->new_image))
			{
				$this->dest_image  = $this->source_image;
				$this->dest_folder = $this->new_image;
			}
			else
			{
				$x = explode('/', str_replace('\\', '/', $this->new_image));
				$this->dest_image  = end($x);
				$this->dest_folder = str_replace($this->dest_image, '', $this->new_image);
			}

			$this->dest_folder = realpath($this->dest_folder).'/';
		}

		/* Compile the finalized filenames/paths
		 *
		 * We'll create two master strings containing the
		 * full server path to the source image and the
		 * full server path to the destination image.
		 * We'll also split the destination image name
		 * so we can insert the thumbnail marker if needed.
		 */
		if ($this->create_thumb === FALSE OR $this->thumb_marker === '')
		{
			$this->thumb_marker = '';
		}

		$xp = $this->explode_name($this->dest_image);

		$filename = $xp['name'];
		$file_ext = $xp['ext'];

		$this->full_src_path = $this->source_folder.$this->source_image;
		$this->full_dst_path = $this->dest_folder.$filename.$this->thumb_marker.$file_ext;

		/* Should we maintain image proportions?
		 *
		 * When creating thumbs or copies, the target width/height
		 * might not be in correct proportion with the source
		 * image's width/height. We'll recalculate it here.
		 */
		if ($this->maintain_ratio === TRUE && ($this->width !== 0 OR $this->height !== 0))
		{
			$this->image_reproportion();
		}

		/* Was a width and height specified?
		 *
		 * If the destination width/height was not submitted we
		 * will use the values from the actual file
		 */
		if ($this->width === '')
		{
			$this->width = $this->orig_width;
		}

		if ($this->height === '')
		{
			$this->height = $this->orig_height;
		}

		// Set the quality
		$this->quality = trim(str_replace('%', '', $this->quality));

		if ($this->quality === '' OR $this->quality === 0 OR ! ctype_digit($this->quality))
		{
			$this->quality = 90;
		}

		// Set the x/y coordinates
		is_numeric($this->x_axis) OR $this->x_axis = 0;
		is_numeric($this->y_axis) OR $this->y_axis = 0;

		// Watermark-related Stuff...
		if ($this->wm_overlay_path !== '')
		{
			$this->wm_overlay_path = str_replace('\\', '/', realpath($this->wm_overlay_path));
		}

		if ($this->wm_shadow_color !== '')
		{
			$this->wm_use_drop_shadow = TRUE;
		}
		elseif ($this->wm_use_drop_shadow === TRUE && $this->wm_shadow_color === '')
		{
			$this->wm_use_drop_shadow = FALSE;
		}

		if ($this->wm_font_path !== '')
		{
			$this->wm_use_truetype = TRUE;
		}

		return TRUE;
	}

    /**
     * Override image_process_netpbm method
     * 
	 * Image Process Using NetPBM
	 *
	 * This function will resize, crop or rotate
	 *
	 * @param	string
	 * @return	bool
	 */
	public function image_process_netpbm($action = 'resize')
	{
		if ($this->library_path === '')
		{
			$this->set_error('imglib_libpath_invalid');
			return FALSE;
		}

		// Build the resizing command
		switch ($this->image_type)
		{
			case 1 :
				$cmd_in		= 'giftopnm';
				$cmd_out	= 'ppmtogif';
				break;
			case 2 :
				$cmd_in		= 'jpegtopnm';
				$cmd_out	= 'ppmtojpeg';
				break;
			case 3 :
				$cmd_in		= 'pngtopnm';
				$cmd_out	= 'ppmtopng';
				break;
			case 18 :
				$cmd_in		= 'webptopnm';
				$cmd_out	= 'ppmtowebp';
				break;
		}

		if ($action === 'crop')
		{
			$cmd_inner = 'pnmcut -left '.$this->x_axis.' -top '.$this->y_axis.' -width '.$this->width.' -height '.$this->height;
		}
		elseif ($action === 'rotate')
		{
			switch ($this->rotation_angle)
			{
				case 90:	$angle = 'r270';
					break;
				case 180:	$angle = 'r180';
					break;
				case 270:	$angle = 'r90';
					break;
				case 'vrt':	$angle = 'tb';
					break;
				case 'hor':	$angle = 'lr';
					break;
			}

			$cmd_inner = 'pnmflip -'.$angle.' ';
		}
		else // Resize
		{
			$cmd_inner = 'pnmscale -xysize '.$this->width.' '.$this->height;
		}

		$cmd = $this->library_path.$cmd_in.' '.escapeshellarg($this->full_src_path).' | '.$cmd_inner.' | '.$cmd_out.' > '.$this->dest_folder.'netpbm.tmp';

		$retval = 1;
		// exec() might be disabled
		if (function_usable('exec'))
		{
			@exec($cmd, $output, $retval);
		}

		// Did it work?
		if ($retval > 0)
		{
			$this->set_error('imglib_image_process_failed');
			return FALSE;
		}

		// With NetPBM we have to create a temporary image.
		// If you try manipulating the original it fails so
		// we have to rename the temp file.
		copy($this->dest_folder.'netpbm.tmp', $this->full_dst_path);
		unlink($this->dest_folder.'netpbm.tmp');
		chmod($this->full_dst_path, $this->file_permissions);

		return TRUE;
	}

    /**
     * Override image_create_gd method
     * 
	 * Create Image - GD
	 *
	 * This simply creates an image resource handle
	 * based on the type of image being processed
	 *
	 * @param	string
	 * @param	string
	 * @return	resource
	 */
	public function image_create_gd($path = '', $image_type = '')
	{
		if ($path === '')
		{
			$path = $this->full_src_path;
		}

		if ($image_type === '')
		{
			$image_type = $this->image_type;
		}

		switch ($image_type)
		{
			case 1:
				if ( ! function_exists('imagecreatefromgif'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
					return FALSE;
				}

				return imagecreatefromgif($path);
			case 2:
				if ( ! function_exists('imagecreatefromjpeg'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
					return FALSE;
				}

				return imagecreatefromjpeg($path);
			case 3:
				if ( ! function_exists('imagecreatefrompng'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
					return FALSE;
				}

				return imagecreatefrompng($path);
			case 18:
				if ( ! function_exists('imagecreatefromwebp'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_webp_not_supported'));
					return FALSE;
				}

				return imagecreatefromwebp($path);
			default:
				$this->set_error(array('imglib_unsupported_imagecreate'));
				return FALSE;
		}
	}

    /**
     * Override image_save_gd method
     * 
	 * Write image file to disk - GD
	 *
	 * Takes an image resource as input and writes the file
	 * to the specified destination
	 *
	 * @param	resource
	 * @return	bool
	 */
	public function image_save_gd($resource)
	{
		switch ($this->image_type)
		{
			case 1:
				if ( ! function_exists('imagegif'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
					return FALSE;
				}

				if ( ! @imagegif($resource, $this->full_dst_path))
				{
					$this->set_error('imglib_save_failed');
					return FALSE;
				}
			break;
			case 2:
				if ( ! function_exists('imagejpeg'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
					return FALSE;
				}

				if ( ! @imagejpeg($resource, $this->full_dst_path, $this->quality))
				{
					$this->set_error('imglib_save_failed');
					return FALSE;
				}
			break;
			case 3:
				if ( ! function_exists('imagepng'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
					return FALSE;
				}

				if ( ! @imagepng($resource, $this->full_dst_path))
				{
					$this->set_error('imglib_save_failed');
					return FALSE;
				}
			break;
			case 18:
				if ( ! function_exists('imagewebp'))
				{
					$this->set_error(array('imglib_unsupported_imagecreate', 'imglib_webp_not_supported'));
					return FALSE;
				}

				if ( ! @imagewebp($resource, $this->full_dst_path, $this->quality))
				{
					$this->set_error('imglib_save_failed');
					return FALSE;
				}
			break;
			default:
				$this->set_error(array('imglib_unsupported_imagecreate'));
				return FALSE;
			break;
		}

		return TRUE;
	}

    /**
     * Override image_display_gd method
     * 
	 * Dynamically outputs an image
	 *
	 * @param	resource
	 * @return	void
	 */
	public function image_display_gd($resource)
	{
		header('Content-Disposition: filename='.$this->source_image.';');
		header('Content-Type: '.$this->mime_type);
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');

		switch ($this->image_type)
		{
			case 1	:	imagegif($resource);
				break;
			case 2	:	imagejpeg($resource, NULL, $this->quality);
				break;
			case 3	:	imagepng($resource);
				break;
			case 18	:	imagewebp($resource);
				break;
			default:	echo 'Unable to display the image';
				break;
		}
	}

    /**
     * Override get_image_properties method
     * 
	 * Get image properties
	 *
	 * A helper function that gets info about the file
	 *
	 * @param	string
	 * @param	bool
	 * @return	mixed
	 */
	public function get_image_properties($path = '', $return = FALSE)
	{
		// For now we require GD but we should
		// find a way to determine this using IM or NetPBM

		if ($path === '')
		{
			$path = $this->full_src_path;
		}

		if ( ! file_exists($path))
		{
			$this->set_error('imglib_invalid_path');
			return FALSE;
		}

		$vals = getimagesize($path);
		if ($vals === FALSE)
		{
			$this->set_error('imglib_invalid_image');
			return FALSE;
		}

		$types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 18 => 'webp');
		$mime = isset($types[$vals[2]]) ? 'image/'.$types[$vals[2]] : 'image/jpg';

		if ($return === TRUE)
		{
			return array(
				'width'      => $vals[0],
				'height'     => $vals[1],
				'image_type' => $vals[2],
				'size_str'   => $vals[3],
				'mime_type'  => $mime
			);
		}

		$this->orig_width  = $vals[0];
		$this->orig_height = $vals[1];
		$this->image_type  = $vals[2];
		$this->size_str    = $vals[3];
		$this->mime_type   = $mime;

		return TRUE;
	}
}
