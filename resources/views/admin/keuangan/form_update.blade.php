@extends('admin.layouts.index')

@section('title')
    <h1>
        Keuangan
        <small>Ubah Anggaran / Realisasi</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('keuangan_manual') }}">Keuangan</a></li>
    <li class="active">Ubah Anggaran / Realisasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route("keuangan_manual?jenis_anggaran={$keuangan?->template?->parent?->parent?->uuid}&tahun_anggaran={$keuangan->tahun}") }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                    class="fa fa-arrow-circle-left"
                ></i> Kembali Ke Daftar Keuangan</a>
        </div>
        {!! form_open(ci_route("keuangan_manual.update.{$keuangan->id}"), 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_mulai">Tahun</label>
                <div class="col-sm-7">
                    <input readonly type="number" class="form-control input-sm required" placeholder="Tahun" name="tahun" value="{{ $keuangan->tahun }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_akhir">Kode Rekening</label>
                <div class="col-sm-7">
                    <input readonly class="form-control input-sm required" placeholder="Jenis Anggaran" value="{{ "{$keuangan?->template?->parent?->parent?->uuid} {$keuangan?->template?->parent?->parent?->uraian}" }}">
                    <input type="hidden" name="1_template_uuid" value="{{ $keuangan?->template?->parent?->parent?->uuid }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_akhir">Sub Kode Rekening</label>
                <div class="col-sm-7">
                    <input readonly class="form-control input-sm required" placeholder="Jenis Anggaran" value="{{ "{$keuangan?->template?->parent?->uuid} {$keuangan?->template?->parent?->uraian}" }}">
                    <input type="hidden" name="2_template_uuid" value="{{ $keuangan?->template?->parent?->uuid }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_akhir">Sub Kode Rekening</label>
                <div class="col-sm-7">
                    <input readonly class="form-control input-sm required" placeholder="Jenis Anggaran" value="{{ "{$keuangan?->template?->uuid} {$keuangan?->template?->uraian}" }}">
                    <input type="hidden" name="3_template_uuid" value="{{ $keuangan?->template?->uuid }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_akhir">Nilai Anggaran</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class="input-group-addon input-sm">Rp.</span>
                        <input
                            name="nilai_anggaran"
                            class="form-control input-sm number required"
                            type="number"
                            placeholder="Nilai Anggaran"
                            style="text-align:right;"
                            min="0"
                            max="1000000000000"
                            step="1"
                            value="{{ old('nilai_anggaran', $keuangan->anggaran) }}"
                        />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jam_akhir">Nilai Realisasi</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class="input-group-addon input-sm">Rp.</span>
                        <input
                            name="nilai_realisasi"
                            class="form-control input-sm number required"
                            type="number"
                            placeholder="Nilai Realisasi"
                            style="text-align:right;"
                            min="0"
                            max="1000000000000"
                            step="1"
                            value="{{ old('nilai_realisasi', $keuangan->realisasi) }}"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
