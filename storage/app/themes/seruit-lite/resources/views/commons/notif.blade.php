@if (session('success'))
    <div class="alert alert-success text-sm mb-4">
        <p class="font-bold"><i class="fas fa-check-circle mr-2"></i>Berhasil</p>
        {!! session('success') !!}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger text-sm mb-4">
        <p class="font-bold"><i class="fas fa-times-circle mr-2"></i>Gagal</p>
        {!! is_array(session('error')) ? implode(', ', session('error')) : session('error') !!}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger text-sm mb-4">
        <p class="font-bold"><i class="fas fa-times-circle mr-2"></i>Terjadi Kesalahan</p>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning text-sm mb-4">
        <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Peringatan</p>
        {!! session('warning') !!}
    </div>
@endif

@if (session('information'))
    <div class="alert alert-info text-sm mb-4">
        <p class="font-bold"><i class="fas fa-info-circle mr-2"></i>Informasi</p>
        {!! session('information') !!}
    </div>
@endif