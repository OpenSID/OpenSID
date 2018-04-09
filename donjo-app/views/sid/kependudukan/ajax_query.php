<?php php
// FIXME: Berkas ini sepertinya tidak digunakan lagi.
//			Jadi dibiarkan dulu untuk sementara.
$q=$_GET["q"];

$con = mysql_connect('localhost', 'root', '');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("baru", $con);

$sql="SELECT * FROM tweb_wil_clusterdesa WHERE dusun = '".$q."'";

$result = mysql_query($sql);

echo "<table border='1'>
<tr>
<th>Firstname</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);
?>