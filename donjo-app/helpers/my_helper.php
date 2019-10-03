<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	// Helper For print_r
	function pr($var = '')
	{
		echo "<pre>";
		   print_r($var);	
		echo "</pre>";
	    // die();	
	}

	//Helper For base_url()
	function bs($value = '')
	{
		// public $url = ""
		echo base_url($value);
	}

	//Helper for $this->load->view()
	function view($value='', $data=array(), $output = false)
	{
		$CI =& get_instance();
		$CI->load->view($value,$data,$output);
	}

	//Helper For thsi->input->post()
	function post($value='')
	{
		$CI =& get_instance();
	    return $CI->input->post($value);
	}

	//helper for var_dump
    function dd($value='')
	{
		echo "<pre>";
		   var_dump($value);	
		echo "</pre>";
		die();
	}

	//Helper for last_query()
	function vd()
	{
		$CI =& get_instance();
		return $CI->db->last_query();
	}
	
        function group_priviliges($value='')
	{
		$CI =& get_instance();

		$gp_id = $CI->session->userdata("group_id");

		$gp_result = $CI->ion_auth_model->user_gp_privilegs($gp_id);

		$gp_data = array();
		   
      	foreach($gp_result as $value)
        {
           //add all data to session
           $gp_data[] = $value->id;

        }

	    return $gp_data;
	}
       
       function group_priviliges_create($value='')
	{
		$CI =& get_instance();

		$gp_id = $CI->session->userdata("group_id");

		$gp_result = $CI->ion_auth_model->user_gp_privilegs_create($gp_id);

		$gp_data = array();
		   
      	foreach($gp_result as $value)
        {
           //add all data to session
           $gp_data[] = $value->id;

        }

	    return $gp_data;
	}

        function group_priviliges_update($value='')
	{
		$CI =& get_instance();

		$gp_id = $CI->session->userdata("group_id");

		$gp_result = $CI->ion_auth_model->user_gp_privilegs_update($gp_id);

		$gp_data = array();
		   
      	foreach($gp_result as $value)
        {
           //add all data to session
           $gp_data[] = $value->id;

        }

	    return $gp_data;
	}

        function group_priviliges_delete($value='')
	{
		$CI =& get_instance();

		$gp_id = $CI->session->userdata("group_id");

		$gp_result = $CI->ion_auth_model->user_gp_privilegs_delete($gp_id);

		$gp_data = array();
		   
      	foreach($gp_result as $value)
        {
           //add all data to session
           $gp_data[] = $value->id;

        }

	    return $gp_data;
	}

        function group_priviliges_print($value='')
	{
		$CI =& get_instance();

		$gp_id = $CI->session->userdata("group_id");

		$gp_result = $CI->ion_auth_model->user_gp_privilegs_print($gp_id);

		$gp_data = array();
		   
      	foreach($gp_result as $value)
        {
           //add all data to session
           $gp_data[] = $value->id;

        }

	    return $gp_data;
	}


        function gp_read()
	{
		$read_permissions = group_priviliges();
                $read_arr = array();
                foreach ($read_permissions as $key => $value) 
	        {
		$read_arr[$value] = $value;
	        }
                return $read_arr;
	}

        function gp_create()
	{
		$create_permissions = group_priviliges_create();
	$create_arr = array();
        foreach ($create_permissions as $key => $value) 
	{
		$create_arr[$value] = $value;
	}
                return $create_arr;
	}


        function gp_update()
	{
		$update_permissions = group_priviliges_update();
        $update_arr = array();
        foreach ($update_permissions as $key => $value) 
	{
		$update_arr[$value] = $value;
	} 
                return $update_arr;
	}

	function gp_delete()
	{
		$delete_permissions = group_priviliges_delete();
        $delete_arr = array();
        foreach ($delete_permissions as $key => $value) 
	{
		$delete_arr[$value] = $value;
	}
                return $delete_arr;
	}

        function gp_print()
	{
		$print_permissions = group_priviliges_print();
        $print_arr = array();
        foreach ($print_permissions as $key => $value) 
	{
		$print_arr[$value] = $value;
	} 
                return $print_arr;
	}


        
        function has($val)
	{
		if ($val) 
		{
			return true;
		}
		return false;
	}
	


	/**
	 * Slugify Helper
	 *
	 * Outputs the given string as a web safe filename
	 */
	if ( ! function_exists('slugify'))
	{
		function slugify($string, $replace = array(), $delimiter = '-', $locale = 'en_US.UTF-8', $encoding = 'UTF-8') {
			if (!extension_loaded('iconv')) {
				throw new Exception('iconv module not loaded');
			}
			// Save the old locale and set the new locale
			$oldLocale = setlocale(LC_ALL, '0');
			setlocale(LC_ALL, $locale);
			$clean = iconv($encoding, 'ASCII//TRANSLIT', $string);
			if (!empty($replace)) {
				$clean = str_replace((array) $replace, ' ', $clean);
			}
			$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
			$clean = strtolower($clean);
			$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
			$clean = trim($clean, $delimiter);
			// Revert back to the old locale
			// setlocale(LC_ALL, $oldLocale);
			return $clean;
		}
	}


/* End of file custom_helpers.php */
/* Location: ./application/helpers/custom_helpers.php */
