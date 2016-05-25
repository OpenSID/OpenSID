<?php
$q=$_GET["q"];

$con = mysql_connect('localhost', 'root', '');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("db_nenny", $con);

$sql="SELECT * FROM sub_kategori WHERE id_kategori = '".$q."'";

$result = mysql_query($sql);

?>
		<th>Sub Kategori</th>
		<td><select name='id_sub_kategori'>
		<option value=''>Pilih Sub Kategori</option>
<?while($row = mysql_fetch_array($result))
  {
			echo "<option value=" . $row['id'] . ">" . $row['nama'] . "</option>";
  }
mysql_close($con);
?> 
		</select>
		</td>
