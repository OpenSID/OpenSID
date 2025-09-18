@extends('admin.layouts.index')

@section('title')
    <h1>Panduan Program Bantuan</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('program_bantuan') }}"> Daftar Program Bantuan</a></li>
    <li class="active">Panduan Program Bantuan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => site_url('program_bantuan'), 'label' => 'Daftar Program Bantuan'])
                </div>
                <div class="box-body">
                    <h4>Keterangan</h4>
                    <p><strong>Program Bantuan</strong> adalah modul untuk pengelolaan data aktivitas program kerja
                        dan keterlibatan warga, baik secara personal, keluarga, rumah tangga, maupun
                        kelompok/organisasi.</p>
                    <h4>Panduan</h4>
                    <p>Cara menyimpan/memperbarui data Program Bantuan adalah dengan mengisikan formulir yang
                        terdapat dari menu Tulis Program Bantuan Baru:</p>
                    <p>
                    <ul>
                        <li>Kolom <strong>Sasaran Program</strong>
                            <p>Pilihlah salah satu dari sasaran program, apakah pribadi/perorangan, keluarga/kk,
                                Rumah Tangga, ataupun Organisasi/kelompok warga</p>
                        </li>
                        <li>Kolom <strong>Nama Program</strong>
                            <p>Nama program wajib diisi</p>
                        </li>
                        <li>Kolom <strong>Keterangan Program</strong>
                            <p>Isikan keterangan program ini</p>
                        </li>
                        <li>Kolom <strong>Rentang Waktu</strong>
                            <p>Isikan keterangan waktu masa kerja program akan berlangsung</p>
                        </li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
