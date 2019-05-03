<?php
class Paging {
	public $page;
	public $per_page;
	public $num_rows;
	public $num_page;
	public $offset;
	public $prev;
	public $next;
	public $start_link;
	public $start;
	public $end;
	public $end_link;
	public $suffix;

	function __construct($props=array()){
		if (count($props) > 0)
			$this->init($props);
	}

	function init($input=array()){

		if(isset($input['page'])) 		$this->page     = $input['page'];
		if(isset($input['per_page'])) 	$this->per_page = $input['per_page'];
		if(isset($input['num_rows']))	$this->num_rows = $input['num_rows'];
		if(isset($input['suffix']))		$this->suffix 	= $input['suffix'];

		//Sanitizing Input
		if((int)$this->page<1) $this->page=1;
		if((int)$this->per_page<1) $this->per_page=50;
		if((int)$this->num_rows<1) $my_num_rows=1;
			else $my_num_rows = (int)$this->num_rows;

		$o=($my_num_rows-1)/$this->per_page;
		$this->num_page=(int)$o+1;

		$o=($this->page-1)*$this->per_page;
		$this->offset=(int)$o;

		$this->prev=$this->page-1;
		$this->next=$this->page+1;
		if($this->next>$this->num_page) $this->next=0;

		//Create Paging Link
		if($this->page < 20){
			$start=1;
			if($this->num_page > 30)
				$end=20;
			else $end=$this->num_page;
		}
		else if($this->page > $this->num_page-20){
			$start=$this->num_page-20;
			$end=$this->num_page;
		}
		else{
			$start=$this->page-9;
			$end=$this->page+10;
		}
		$this->start=1;
		$this->end=$this->num_page;
		$this->start_link=$start;
		$this->end_link=$end;
	}
}

?>