<?php
/*
 * apis.php
 * 
 * Model API
 * 
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

class Apis extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	public function view($page = 'indeks'){

	if ( ! file_exists(APPPATH.'/views/apis/'.$page.'.php')){
		
		// Whoops, we don't have a page for that!
		echo $page;
	}
}

?>
