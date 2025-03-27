  <!-- Print Body -->
  <div id="body">
      <div align="center">
          <h3>KARTU KELUARGA</h3>
          <h4>SALINAN</h4>
          <h5>No. {{ get_nokk($kepala_kk['keluarga']['no_kk']) }} </h4>
      </div>
      <br>
      <table width="100%" cellpadding="3" cellspacing="4">
          <tr>
              <td width="100">Nama KK</td>
              <td width="600">: {{ strtoupper($kepala_kk['nama']) }}</td>
              <td width="160">{{ ucwords(setting('sebutan_kecamatan')) }}</td>
              <td width="150">: {{ strtoupper($desa['nama_kecamatan']) }}</td>
          </tr>
          <tr>
              <td>Alamat</td>
              <td>: {{ strtoupper($kepala_kk['alamat_wilayah_kartu_keluarga']) }} </td>
              <td>Kabupaten/Kota</td>
              <td>: {{ $desa['nama_kabupaten'] }}</td>
          </tr>
          <tr>
              <td>RT / RW</td>
              <td>: {{ $kepala_kk['keluarga']['wilayah']['rt'] }} / {{ $kepala_kk['keluarga']['wilayah']['rw'] }}</td>
              <td>Kode Pos</td>
              <td>: {{ strtoupper($desa['kode_pos']) }}</td>
          </tr>
          <tr>
              <td>Kelurahan/{{ ucwords(setting('sebutan_desa')) }}</td>
              <td>: {{ strtoupper($desa['nama_desa']) }}</td>
              <td>Provinsi</td>
              <td>: {{ strtoupper($desa['nama_propinsi']) }}</td>
          </tr>
      </table>

      <br>

      <table class="border thick ">
          <thead>
              <tr class="border thick">
                  <th class="text-center" width="7">No</th>
                  <th class="text-center" width='180'>Nama</th>
                  <th class="text-center" width='100'>NIK</th>
                  <th class="text-center" width='100'>Jenis Kelamin</th>
                  <th class="text-center" width='100'>Tempat Lahir</th>
                  <th class="text-center" width='120'>Tanggal Lahir</th>
                  <th class="text-center" width='100'>Agama</th>
                  <th class="text-center" width='100'>Pendidikan</th>
                  <th class="text-center" width='100'>Pekerjaan</th>
                  <th class="text-center" width='70'>Golongan darah</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($main as $key => $data)
                  <tr class="data">
                      <td class="text-center" width="2">{{ $key + 1 }}</td>
                      <td>{{ strtoupper($data['nama']) }}</td>
                      <td>{{ get_nik($data['nik']) }}</td>
                      <td>{{ $data->jenisKelamin->nama ?? '' }}</td>
                      <td>{{ $data['tempatlahir'] }}</td>
                      <td>{{ tgl_indo_out($data['tanggallahir']) }}</td>
                      <td>{{ $data->agama->nama ?? '' }}</td>
                      <td>{{ $data->pendidikanKK->nama ?? '' }}</td>
                      <td>{{ $data->pekerjaan->nama ?? '' }}</td>
                      <td align="center">{{ $data->golonganDarah->nama ?? '' }}</td>
                  </tr>
              @endforeach
          </tbody>
      </table>

      <br>

      <table class="border thick ">
          <thead>
              <tr class="border thick">
                  <th class="text-center" width="7">No</th>
                  <th class="text-center" width='150'>Status Perkawinan</th>
                  <th class="text-center" width='150'>Tanggal Perkawinan</th>
                  <th class="text-center" width="130">Tanggal Perceraian</th>
                  <th class="text-center" width='240'>Status Hubungan dalam Keluarga</th>
                  <th class="text-center" width='140'>Kewarganegaraan</th>
                  <th class="text-center" width='100'>No. Paspor</th>
                  <th class="text-center" width='100'>No. KITAS / KITAP</th>
                  <th class="text-center" width='170'>Nama Ayah</th>
                  <th class="text-center" width='170'>Nama Ibu</th>
              </tr>
          </thead>
          <tbody>
              @foreach ($main as $key => $data)
                  <tr class="data">
                      <td class="text-center" width="2">{{ $key + 1 }}</td>
                      <td>{{ $data->statusPerkawinan ?? '' }}</td>
                      <td class="text-center">{{ tgl_indo_out($data['tanggalperkawinan']) }}</td>
                      <td class="text-center">{{ tgl_indo_out($data['tanggalperceraian']) }}</td>
                      <td>{{ App\Enums\SHDKEnum::valueOf($data['kk_level']) }}</td>
                      <td>{{ $data->wargaNegara->nama ?? '' }}</td>
                      <td>{{ $data['dokumen_pasport'] }}</td>
                      <td>{{ $data['dokumen_kitas'] }}</td>
                      <td>{{ strtoupper($data['nama_ayah']) }}</td>
                      <td>{{ strtoupper($data['nama_ibu']) }}</td>
                  </tr>
              @endforeach
          </tbody>
      </table>

      <br>

      <table width="100%" cellpadding="3" cellspacing="4">
          <tr>
              <td width="25%"></td>
              <td width="50%"></td>
              <td width="25%" align="center">{{ $desa['nama_desa'] }}, {{ tgl_indo(date('Y m d')) }}</td>
          </tr>
          <td width="25%" align="center">KEPALA KELUARGA</td>
          <td width="50%"></td>
          <td align="center" width="150">
              {{ strtoupper(setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa']) }}</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
          </tr>
          <tr>
              <td>&nbsp;</td>
          </tr>
          <tr>
              <td width="25%" align="center">{{ strtoupper($kepala_kk['nama']) }}</td>
              <td width="50%"></td>
              <td width="25%" align="center" width="150">{{ strtoupper($desa['nama_kepala_desa']) }}</td>
          </tr>
      </table>
  </div>
