@foreach ($surat['kode_isian'] as $label => $item)
    @include('admin.surat_dinas.cetak.baris_kode_isian', ['groupLabel' => $item, 'label' => $label])
@endforeach
