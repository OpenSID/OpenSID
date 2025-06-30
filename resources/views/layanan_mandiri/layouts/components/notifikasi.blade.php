@if (session('success'))
    <div id="notifikasi" class="callout callout-success">
        <h4><i class="icon fa fa-check"></i> Berhasil</h4>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div id="notifikasi" class="callout callout-danger">
        <h4><i class="icon fa fa-alert"></i> Gagal</h4>
        <p>{{ session('error') }}</p>
    </div>
@endif
