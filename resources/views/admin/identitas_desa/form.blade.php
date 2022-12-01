@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
<h1>
  {{ SebutanDesa('Identitas [Desa]') }}
  <small>Ubah Data</small>
</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('identitas_desa') }}">{{ SebutanDesa('Identitas [Desa]') }}</a></li>
<li class="active">Ubah Data</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

{!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
  <div class="row">
    <div class="col-md-3">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="profile-user-img img-responsive img-circle" src="{{ gambar_desa($main->logo) }}" alt="Logo">
          <br/>
          <p class="text-center text-bold">Lambang {{ ucwords($setting->sebutan_desa) }}</p>
          <p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
          <br/>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="file_path" >
            <input type="file" class="hidden" id="file" name="logo">
            <input type="hidden" name="old_logo" value="{{ $main->logo }}">
            <span class="input-group-btn">
              <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
      <div class="box box-primary">
        <div class="box-body box-profile">
          <img class="img-responsive" src="{{ gambar_desa($main->kantor_desa, true) }}" alt="Kantor {{ ucwords($setting->sebutan_desa) }}">
          <br/>
          <p class="text-center text-bold">Kantor {{ ucwords($setting->sebutan_desa) }}</p>
          <p class="text-muted text-center text-red">(Kosongkan, jika kantor {{ ucwords($setting->sebutan_desa) }} tidak berubah)</p>
          <br/>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="file_path2" >
            <input type="file" class="hidden" id="file2" name="kantor_desa">
            <input type="hidden" name="old_kantor_desa" value="{{ $main->kantor_desa }}">
            <span class="input-group-btn">
              <button type="button" class="btn btn-info btn-flat" id="file_browser2"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="box box-primary">
        <div class="box-header with-border">
          <a href="{{ route('identitas_desa') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data {{ ucwords($setting->sebutan_desa) }}"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Identitas {{ ucwords($setting->sebutan_desa) }}</a>
        </div>
        <div class="box-body">
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nama">Nama {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-8">
              @if (cek_koneksi_internet())
                <select id="pilih_desa" name="pilih_desa" class="form-control input-sm select-nama-desa" data-placeholder="{{ $main->nama_desa }} - {{ $main->nama_kecamatan }} - {{ $main->nama_kabupaten }} - {{ $main->nama_propinsi }}" data-token="{{ config_item('token_pantau') }}" data-tracker='{{ config_item('server_pantau') }}' style="display: none;"></select>
              @endif
              <input type="hidden" id="nama_desa" class="form-control input-sm nama_terbatas required" minlength="3" maxlength="50" name="nama_desa" value="{{ $main->nama_desa }}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_desa">Kode {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-2">
              <input readonly id="kode_desa" name="kode_desa" class="form-control input-sm {{ jecho(cek_koneksi_internet(), false, 'bilangan') }} required" {{ jecho(cek_koneksi_internet(), false, 'minlength="10" maxlength="10"') }} type="text" onkeyup="tampil_kode_desa()" placeholder="Kode {{ ucwords($setting->sebutan_desa) }}" value="{{ $main->kode_desa }}" ></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_pos">Kode Pos {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-2">
              <input id="kode_pos" name="kode_pos" class="form-control input-sm number" minlength="5" maxlength="5" type="text" placeholder="Kode Pos {{ ucwords($setting->sebutan_desa) }}" value="{{ $main->kode_pos }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="pamong_id">Nama {{ ucwords($setting->sebutan_kepala_desa) }}</label>
            <div class="col-sm-8">
              <select class="form-control input-sm" id="kades" name="pamong_id">
                <option value="">--- Pilih {{ ucwords($setting->sebutan_kepala_desa) }} ---</option>
                @foreach ($pamong as $data)
                  <option value="{{ $data->pamong_id }}" data-nip="{{ $data->pamong_nip }}" {{ selected($data->pamong_id, $main->pamong_id) }}>{{ ($data->id_pend ? $data->penduduk->nama : $data->pamong_nama) . ' (' . ($data->jabatan ?? ' - ') . ')' }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nip_kepala_desa">NIP {{ ucwords($setting->sebutan_kepala_desa) }}</label>
            <div class="col-sm-8">
              <input id="nip_kepala_desa" name="nip_kepala_desa" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP {{ ucwords($setting->sebutan_kepala_desa) }}" value="{{ $main->pamong->pamong_nip }}" readonly></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="alamat_kantor">Alamat Kantor {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-8">
              <textarea id="alamat_kantor" name="alamat_kantor" class="form-control input-sm alamat required" maxlength="100" placeholder="Alamat Kantor {{ ucwords($setting->sebutan_desa) }}" rows="3" style="resize:none;">{{ $main->alamat_kantor }}</textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="email_desa">E-Mail {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-8">
              <input id="email_desa" name="email_desa" class="form-control input-sm email" maxlength="50" type="text" placeholder="E-Mail {{ ucwords($setting->sebutan_desa) }}" value="{{ $main->email_desa }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="telepon">Telpon {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-8">
              <input id="telepon" name="telepon" class="form-control input-sm bilangan" type="text" maxlength="15" placeholder="Telpon {{ ucwords($setting->sebutan_desa) }}" value="{{ $main->telepon }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="website">Website {{ ucwords($setting->sebutan_desa) }}</label>
            <div class="col-sm-8">
              <input id="website" name="website" class="form-control input-sm url" maxlength="50" type="text" placeholder="Website {{ ucwords($setting->sebutan_desa) }}" value="{{ $main->website }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nama_kecamatan">Nama {{ ucwords($setting->sebutan_kecamatan) }}</label>
            <div class="col-sm-8">
              <input readonly id="nama_kecamatan" name="nama_kecamatan" class="form-control input-sm required" type="text" placeholder="Nama {{ ucwords($setting->sebutan_kecamatan) }}" value="{{ $main->nama_kecamatan }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_kecamatan">Kode {{ ucwords($setting->sebutan_kecamatan) }}</label>
            <div class="col-sm-2">
              <input readonly id="kode_kecamatan" name="kode_kecamatan" class="form-control input-sm required" type="text" placeholder="Kode {{ ucwords($setting->sebutan_kecamatan) }}" value="{{ $main->kode_kecamatan }}" ></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nama_kecamatan">Nama {{ ucwords($setting->sebutan_camat) }}</label>
            <div class="col-sm-8">
              <input id="nama_kepala_camat" name="nama_kepala_camat" class="form-control input-sm nama required" maxlength="50" type="text" placeholder="Nama {{ ucwords($setting->sebutan_camat) }}" value="{{ $main->nama_kepala_camat }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nip_kepala_camat">NIP {{ ucwords($setting->sebutan_camat) }}</label>
            <div class="col-sm-4">
              <input id="nip_kepala_camat" name="nip_kepala_camat" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP {{ ucwords($setting->sebutan_camat) }}" value="{{ $main->nip_kepala_camat }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nama_kabupaten">Nama {{ ucwords($setting->sebutan_kabupaten) }}</label>
            <div class="col-sm-8">
              <input readonly id="nama_kabupaten" name="nama_kabupaten" class="form-control input-sm required" type="text" placeholder="Nama {{ ucwords($setting->sebutan_kabupaten) }}" value="{{ $main->nama_kabupaten }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_kabupaten">Kode {{ ucwords($setting->sebutan_kabupaten) }}</label>
            <div class="col-sm-2">
              <input readonly id="kode_kabupaten" name="kode_kabupaten" class="form-control input-sm required" type="text" placeholder="Kode {{ ucwords($setting->sebutan_kabupaten) }}" value="{{ $main->kode_kabupaten }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="nama_propinsi">Nama Provinsi</label>
            <div class="col-sm-8">
              <input readonly id="nama_propinsi" name="nama_propinsi" class="form-control input-sm required" type="text" placeholder="Nama Propinsi" value="{{ $main->nama_propinsi }}"></input>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_propinsi">Kode Provinsi</label>
            <div class="col-sm-2">
              <input readonly id="kode_propinsi" name="kode_propinsi" class="form-control input-sm required" type="text" placeholder="Kode Provinsi" value="{{ $main->kode_propinsi }}"></input>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
          <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection

@push('scripts')
@include('admin.layouts.components.select2_desa')
<script>
  $(document).ready(function() {
    var koneksi = "{{ cek_koneksi_internet() }}";

    tampil_kode_desa();

    if (koneksi) {
      $("#nama_desa").attr('type', 'hidden');

      var server_pantau = "{{ config_item('server_pantau') }}";
      var token_pantau = "{{ config_item('token_pantau') }}";

      // Ambil Nama dan Kode Wilayah dari Pantau > Wilayah
      $('[name="pilih_desa"]').change(function(){
        $.ajax({
          type: 'GET',
          url: server_pantau + '/index.php/api/wilayah/ambildesa?token=' + token_pantau + '&id_desa=' + $(this).val(),
          dataType: 'json',
          success: function(data) {
            $('[name="nama_desa"]').val(data.KODE_WILAYAH[0].nama_desa);
            $('[name="kode_desa"]').val(data.KODE_WILAYAH[0].kode_desa);
            $('[name="nama_kecamatan"]').val(data.KODE_WILAYAH[0].nama_kec);
            $('[name="kode_kecamatan"]').val(data.KODE_WILAYAH[0].kode_kec);
            $('[name="nama_kabupaten"]').val(hapus_kab_kota(huruf_awal_besar(data.KODE_WILAYAH[0].nama_kab)));
            $('[name="kode_kabupaten"]').val(data.KODE_WILAYAH[0].kode_kab);
            $('[name="nama_propinsi"]').val(huruf_awal_besar(data.KODE_WILAYAH[0].nama_prov));
            $('[name="kode_propinsi"]').val(data.KODE_WILAYAH[0].kode_prov);
          }
        });
      });

      function hapus_kab_kota(str) {
        return str.replace(/KAB |KOTA /gi, '');
      }
    } else {
      $("#nama_desa").attr('type', 'text');
      $("#kode_desa").removeAttr('readonly');
      $("#nama_kecamatan").removeAttr('readonly');
      $("#nama_kabupaten").removeAttr('readonly');
      $("#nama_propinsi").removeAttr('readonly');
    }

    $('#kades').change(function () {
      var nip = $("#kades option:selected").attr("data-nip");
      $("#nip_kepala_desa").val(nip);
    });
  });

  function tampil_kode_desa() {
    var kode_desa = $('#kode_desa').val();
    $('#kode_kecamatan').val(kode_desa.substr(0, 6));
    $('#kode_kabupaten').val(kode_desa.substr(0, 4));
    $('#kode_propinsi').val(kode_desa.substr(0, 2));
  }
</script>
@endpush
