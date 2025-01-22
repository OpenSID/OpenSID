<div class="btn-group-vertical radius-3">
    <a class="btn btn-social btn-sm bg-navy" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i>
        Impor / Ekspor</a>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a
                href="{{ $impor }}"
                class="btn btn-social btn-block btn-sm"
                data-target="#impor-pengguna"
                data-remote="false"
                data-toggle="modal"
                data-backdrop="false"
                data-keyboard="false"
            ><i class="fa fa-upload"></i> {{ $label ?? 'Impor' }}</a>
        </li>
        <li>
            <a target="_blank" class="btn btn-social btn-block btn-sm aksi-terpilih" title="Ekspor Pengguna" onclick="formAction('mainform', '{{ $ekspor }}'); return false;"><i class="fa fa-download"></i> {{ $label ?? 'Ekspor' }}</a>
        </li>
    </ul>
</div>
