@php
    $filesManager = app(\OpenSID\LaravelFilemanager\Services\FilesystemManager::class);
    $imageExts = array_flip(config('filemanager.extensions.image', []));
    $archiveExts = array_flip(config('filemanager.extensions.archive', []));
    $videoExts = array_flip(config('filemanager.extensions.video', []));
    $audioExts = array_flip(config('filemanager.extensions.audio', []));
    $iconTheme = config('filemanager.icon_theme', 'ico');
    $thumbsDisk = $filesManager->thumbsDisk();
    $baseFolder = trim((string) config('filemanager.base_folder', ''), '/');
    $isAtBaseFolder = $subdir === '' || $subdir === $baseFolder;
    $parentDir = \Illuminate\Support\Str::beforeLast($subdir, '/') === $subdir ? $baseFolder : \Illuminate\Support\Str::beforeLast($subdir, '/');
    $knownIconExts = array_flip([
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico', 'webp',
        'doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx',
        'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', 'flv', 'webm',
        'mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav',
        'zip', 'rar', 'gz', 'tar', 'iso', 'dmg',
        'txt', 'log', 'xml', 'html', 'css', 'htm', 'js', 'json', 'sql',
    ]);
@endphp

@if (! $isAtBaseFolder)
    <li class="back no-selector">
        <figure data-name=".." class="back-directory" data-type="">
            <a class="folder-link" href="?{{ http_build_query($linkParams + ['fldr' => $parentDir]) }}">
                <div class="img-precontainer">
                    <div class="img-container directory"><span></span>
                        <img class="directory-img" src="{{ filemanager_asset('img/' . $iconTheme . '/folder_back.png') }}" data-src="{{ filemanager_asset('img/' . $iconTheme . '/folder_back.png') }}">
                    </div>
                </div>
                <div class="img-precontainer-mini directory">
                    <div class="img-container-mini"><span></span>
                        <img class="directory-img" src="{{ filemanager_asset('img/' . $iconTheme . '/folder_back.png') }}" data-src="{{ filemanager_asset('img/' . $iconTheme . '/folder_back.png') }}">
                    </div>
                </div>
                <div class="box no-effect">
                    <h4 class="ellipsis"><a class="folder-link" data-file=".." href="?{{ http_build_query($linkParams + ['fldr' => $parentDir]) }}">{{ __('filemanager::filemanager.Back') }}</a></h4>
                </div>
            </a>
        </figure>
    </li>
@endif

@foreach ($entries as $entry)
    @if ($entry['is_dir'])
        <li data-name="{{ $entry['name'] }}" class="dir {{ $canDelete ? '' : 'no-selector' }}">
            <figure data-name="{{ $entry['name'] }}" data-path="{{ $entry['path'] }}" class="directory" data-type="dir">
                @if ($canDelete)
                    <div class="selector">
                        <label class="cont">
                            <input type="checkbox" class="selection" name="selection[]" value="{{ $entry['name'] }}">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                @endif
                <a class="folder-link" href="?{{ http_build_query($linkParams + ['fldr' => $entry['path']]) }}">
                    <div class="img-precontainer">
                        <div class="img-container directory"><span></span>
                            <img class="directory-img" src="{{ filemanager_asset('img/' . $iconTheme . '/folder.png') }}" data-src="{{ filemanager_asset('img/' . $iconTheme . '/folder.png') }}">
                        </div>
                    </div>
                    <div class="img-precontainer-mini directory">
                        <div class="img-container-mini"><span></span>
                            <img class="directory-img" src="{{ filemanager_asset('img/' . $iconTheme . '/folder.png') }}" data-src="{{ filemanager_asset('img/' . $iconTheme . '/folder.png') }}">
                        </div>
                    </div>
                    <div class="box">
                        <h4 class="ellipsis"><a class="folder-link" data-file="{{ $entry['name'] }}" href="?{{ http_build_query($linkParams + ['fldr' => $entry['path']]) }}">{{ $entry['name'] }}</a></h4>
                    </div>
                </a>
                <input type="hidden" class="name" value="{{ mb_strtolower($entry['name']) }}">
                <input type="hidden" class="date" value="{{ $entry['date'] }}">
                <input type="hidden" class="size" value="0">
                <input type="hidden" class="extension" value="{{ __('filemanager::filemanager.Type_dir') }}">
                <div class="file-date">{{ date('d/m/Y H:i', $entry['date']) }}</div>
                <div class="file-size">-</div>
                <div class='file-extension'>{{ __('filemanager::filemanager.Type_dir') }}</div>
                <figcaption>
                    @if ($canUpload)
                        <a href="javascript:void('')" class="tip-left edit-button rename-file-paths rename-folder" title="{{ __('filemanager::filemanager.Rename') }}" data-folder="1"><i class="icon-pencil"></i></a>
                    @endif
                    @if ($canDelete)
                        <a href="javascript:void('')" class="tip-left erase-button delete-folder" title="{{ __('filemanager::filemanager.Erase') }}" data-confirm="{{ __('filemanager::filemanager.Confirm_Folder_del') }}"><i class="icon-trash"></i></a>
                    @endif
                </figcaption>
            </figure>
        </li>
    @else
        @php
            $isImage = isset($imageExts[$entry['extension']]);
            $isPdf = $entry['extension'] === 'pdf';
            $fileUrl = $filesManager->url($entry['path']);
            $thumbUrl = $isImage && $thumbsDisk->exists($entry['path']) ? $filesManager->thumbUrl($entry['path']) : null;
            $iconFile = isset($knownIconExts[$entry['extension']]) ? $entry['extension'] . '.jpg' : 'default.jpg';
            $iconUrl = filemanager_asset('img/' . $iconTheme . '/' . $iconFile);
            $imageSrc = $thumbUrl ?: $iconUrl;

            $itemTypeClass = 'ff-item-type-1';
            if ($isImage) {
                $itemTypeClass = 'ff-item-type-2';
            } elseif (isset($archiveExts[$entry['extension']])) {
                $itemTypeClass = 'ff-item-type-3';
            } elseif (isset($videoExts[$entry['extension']])) {
                $itemTypeClass = 'ff-item-type-4';
            } elseif (isset($audioExts[$entry['extension']])) {
                $itemTypeClass = 'ff-item-type-5';
            }
        @endphp
        <li data-name="{{ $entry['name'] }}" class="file {{ $itemTypeClass }} {{ $canDelete ? '' : 'no-selector' }}">
            <figure data-name="{{ $entry['name'] }}" data-path="{{ $entry['path'] }}" data-type="{{ $isImage ? 'img' : 'file' }}">
                @if ($canDelete)
                    <div class="selector">
                        <label class="cont">
                            <input type="checkbox" class="selection" name="selection[]" value="{{ $entry['name'] }}">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                @endif
                <a href="javascript:void('')" class="link" data-file="{{ $entry['name'] }}" data-function="{{ $apply }}">
                    <div class="img-precontainer">
                        @unless ($thumbUrl)
                            <div class="filetype {{ $entry['extension'] }}">{{ $entry['extension'] }}</div>
                        @endunless
                        <div class="img-container">
                            <img class="{{ $thumbUrl ? '' : 'icon' }}" src="{{ $imageSrc }}" data-src="{{ $imageSrc }}">
                        </div>
                    </div>
                    <div class="img-precontainer-mini {{ $thumbUrl ? 'original-thumb' : '' }}">
                        <div class="filetype {{ $entry['extension'] }} {{ $thumbUrl ? 'hide' : '' }}">{{ $entry['extension'] }}</div>
                        <div class="img-container-mini">
                            <img class="{{ $thumbUrl ? '' : 'icon' }}" src="{{ $imageSrc }}" data-src="{{ $imageSrc }}">
                        </div>
                    </div>
                    <div class="box">
                        <h4 class="ellipsis">{{ $entry['name'] }}</h4>
                    </div>
                </a>
                <input type="hidden" class="date" value="{{ $entry['date'] }}">
                <input type="hidden" class="size" value="{{ $entry['size'] }}">
                <input type="hidden" class="extension" value="{{ $entry['extension'] }}">
                <input type="hidden" class="name" value="{{ mb_strtolower($entry['name']) }}">
                <div class="file-date">{{ date('d/m/Y H:i', $entry['date']) }}</div>
                <div class="file-size">{{ $filesManager->humanFileSize($entry['size']) }}</div>
                <div class='file-extension'>{{ $entry['extension'] }}</div>
                <figcaption>
                    <form action="{{ route('filemanager.download') }}" method="post" class="download-form">
                        @csrf
                        <input type="hidden" name="path" value="{{ $subdir }}">
                        <input type="hidden" class="name_download" name="name" value="{{ $entry['name'] }}">
                        <a title="{{ __('filemanager::filemanager.Download') }}" class="tip-right" href="javascript:void('')" onclick="jQuery(this).closest('form').submit();"><i class="icon-download"></i></a>

                        @if ($isImage)
                            <a class="tip-right preview" title="{{ __('filemanager::filemanager.Preview') }}" data-featherlight="image" href="{{ $fileUrl }}"><i class="icon-eye-open"></i></a>
                            @if ($canUpload && ! in_array($entry['extension'], ['svg', 'ico'], true))
                                <a class="tip-right crop-file-btn" title="{{ __('filemanager::filemanager.Crop') }}" href="javascript:void(0)" data-url="{{ $fileUrl }}" data-path="{{ $entry['path'] }}" data-name="{{ $entry['name'] }}" style="display:inline-block; vertical-align:middle; cursor:pointer;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none; vertical-align:-1px;"><path d="M6 2v14a2 2 0 0 0 2 2h14"></path><path d="M18 22V8a2 2 0 0 0-2-2H2"></path></svg></a>
                            @endif
                        @elseif ($isPdf)
                            <a class="tip-right preview" title="{{ __('filemanager::filemanager.Preview') }}" data-featherlight="iframe" data-featherlight-iframe-width="100%" data-featherlight-iframe-height="100%" href="{{ $fileUrl }}"><i class="icon-eye-open"></i></a>
                        @elseif (config('filemanager.text_editing_enabled') && app(\OpenSID\LaravelFilemanager\Support\FilemanagerConfig::class)->isEditableTextExtension($entry['extension']))
                            <a class="tip-right file-preview-btn" title="{{ __('filemanager::filemanager.Preview') }}" data-url="{{ route('filemanager.ajax', ['action' => 'get_file', 'sub_action' => 'preview', 'preview_mode' => 'text', 'file' => $entry['path']]) }}" href="javascript:void('');"><i class="icon-eye-open"></i></a>
                        @else
                            <a class="preview disabled"><i class="icon-eye-open icon-white"></i></a>
                        @endif

                        @if ($canUpload)
                            <a href="javascript:void('')" class="tip-left edit-button rename-file-paths rename-file" title="{{ __('filemanager::filemanager.Rename') }}" data-folder="0"><i class="icon-pencil"></i></a>
                        @endif
                        @if ($canDelete)
                            <a href="javascript:void('')" class="tip-left erase-button delete-file" title="{{ __('filemanager::filemanager.Erase') }}" data-confirm="{{ __('filemanager::filemanager.Confirm_del') }}"><i class="icon-trash"></i></a>
                        @endif
                    </form>
                </figcaption>
            </figure>
        </li>
    @endif
@endforeach
