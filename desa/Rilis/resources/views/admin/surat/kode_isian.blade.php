@foreach ($surat['kode_isian'] as $label => $item)
    @include('admin.surat.baris_kode_isian', ['groupLabel' => $item, 'label' => $label])
@endforeach
