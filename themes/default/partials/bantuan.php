<div class="artikel">
  <?php if(!empty($daftar_bantuan)){
    echo '<table  class="table table-bordered">';
    echo '<caption><h3>Daftar Bantuan Yang Diterima (Sasaran Perorangan)</h3></caption>';
    echo '<thead>
      <tr>
        <th>Nama</th>
        <th>Awal</th>
        <th>Akhir</th>
        <th>ID KARTU</th>
      </tr>
    </thead>';
    echo '<tbody>';
    foreach ($daftar_bantuan as $bantuan) {
      echo '<tr>
        <td>'.$bantuan['nama'].'</td>
        <td>'.tgl_indo($bantuan['sdate']).'</td>
        <td>'.tgl_indo($bantuan['edate']).'</td>
        <td>'.$bantuan['no_id_kartu'].'</td>
      </tr>';
    }
    echo '</tbody>';
    echo '</table>';
  }else{
    echo 'Anda tidak terdaftar dalam program bantuan apapun';
  } ?>

</div>
