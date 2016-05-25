<?php
$q=$_GET["q"];

$str = exec("ping -n 1 -w 1 $q", $input, $result);


?>
		<th>Status</th>
		<td>
		<? if ($result == 0){
			echo "On";
			}else{
			echo "Off";
		} ?>
		</td>
