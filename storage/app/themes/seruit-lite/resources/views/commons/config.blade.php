@php
    $kode_desa_enkripsi = base64_encode($desa['kode_desa']);
@endphp
<script type="text/javascript">
    var seruitConfig = {
        kodeDesa: "{{ $kode_desa_enkripsi }}",
        siteUrl: "{{ site_url() }}"
    };
</script>