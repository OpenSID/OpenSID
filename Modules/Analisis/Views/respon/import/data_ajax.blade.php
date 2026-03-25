@extends('admin.layouts.components.ajax-cetak-bersama')

@section('fields')
    <div class="col-sm-12">
        <div class="form-group">
            <label>Pilih Jenis File Unduhan</label>
            <div class="input-group col-xs-12">
                <div class="btn-group col-xs-12" data-toggle="buttons" style="padding: 0px;">
                    <label class="btn btn-info btn-flat btn-sm col-xs-6 form-check-label tip"
                        title="Unduh data respon dalam format yang siap diimpor. Gunakan file ini untuk mengisi atau memperbarui data respon secara massal, atau untuk memasukkan data ke aplikasi lain.">
                        <input type="radio" name="tipe" class="form-check-input" value="1" autocomplete="off">
                        Form Excel + Isi Data
                    </label>
                    <label class="btn btn-info btn-flat btn-sm col-xs-6 form-check-label active tip"
                        title="Unduh formulir kosong yang menampilkan daftar kode untuk setiap kolom.">
                        <input type="radio" name="tipe" class="form-check-input" value="2" autocomplete="off" checked>
                        Form Excel + Kode Data
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('.tip').tooltip({
                placement: 'top',
                container: 'body'
            });
        });
    </script>
@endsection