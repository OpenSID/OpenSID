<div class="btn-group btn-group-vertical">
    <a class="btn btn-social {{ $type }} btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="{{ $icon }}"></i> {{ $judul }}
    </a>
    <ul class="dropdown-menu" role="menu">
        @foreach ($list as $key => $value)
            @if (! isset($value['can']) || $value['can'])
                <li>
                    <a href="{{ site_url($value['url']) }}" @if (isset($value['id'])) id="{{ $value['id'] }}" @endif
                        class="btn btn-social btn-block btn-sm{{ isset($value['data']['class']) ? ' ' . $value['data']['class'] : '' }}"
                        title="{{ $value['judul'] }}" 
                        @if (isset($value['data']['id'])) id="{{ $value['data']['id'] }}" @endif
                        @if (isset($value['data']['onclick'])) onclick="{{ $value['data']['onclick'] }}" @endif
                        @if (isset($value['target']) && $value['target']) target="_blank" @endif
                        @if ($value['modal']) 
                            @if (isset($value['data']))
                                @php
                                    $hasModalAttributes = false;
                                    foreach($value['data'] as $key => $val) {
                                        if (str_starts_with($key, 'data-') && in_array($key, ['data-remote', 'data-toggle', 'data-target', 'data-title'])) {
                                            $hasModalAttributes = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @foreach($value['data'] as $dataKey => $dataValue)
                                    @if (!in_array($dataKey, ['id', 'onclick', 'class']))
                                        {{ $dataKey }}="{{ $dataValue }}"
                                    @endif
                                @endforeach
                                @if (!$hasModalAttributes)
                                    data-remote="false" data-toggle="modal" data-target="{{ isset($value['target']) ? $value['target'] : '#modalBox' }}" data-title="{{ $value['judul'] }}"
                                @endif
                            @else
                                data-remote="false" data-toggle="modal" data-target="{{ isset($value['target']) ? $value['target'] : '#modalBox' }}" data-title="{{ $value['judul'] }}"
                            @endif
                        @endif>
                        <i class="{{ $value['icon'] }}"></i> {{ $value['judul'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
