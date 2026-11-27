<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ __('filemanager::filemanager.Toolbar') }}</title>
    <link rel="shortcut icon" href="{{ filemanager_asset('img/ico/favicon.ico') }}">

    <link rel="stylesheet" href="{{ filemanager_asset('css/jquery.fileupload.css') }}">
    <link rel="stylesheet" href="{{ filemanager_asset('css/jquery.fileupload-ui.css') }}">
    <noscript><link rel="stylesheet" href="{{ filemanager_asset('css/jquery.fileupload-noscript.css') }}"></noscript>
    <noscript><link rel="stylesheet" href="{{ filemanager_asset('css/jquery.fileupload-ui-noscript.css') }}"></noscript>
    <link rel="stylesheet" href="{{ filemanager_asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ filemanager_asset('css/cropper.min.css') }}">

    <script src="{{ filemanager_asset('js/jquery.min.js') }}"></script>
    <script src="{{ filemanager_asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ filemanager_asset('js/plugins.js') }}"></script>
    <script src="{{ filemanager_asset('js/cropper.min.js') }}"></script>
    <script src="{{ filemanager_asset('js/modernizr.custom.js') }}"></script>

    <script>
        var ext_img = @json(config('filemanager.extensions.image', []));
        var image_editor = false; {{-- TUI image editor dropped from this package; keep the guard include.js checks --}}
    </script>

    <script src="{{ filemanager_asset('js/include.js') }}"></script>

    <script>
        $(function () {
            if ($.featherlight && $.featherlight.contentFilters && $.featherlight.contentFilters.image) {
                $.featherlight.contentFilters.image.regex = /\.(png|jpg|jpeg|gif|tiff?|bmp|svg|webp|avif)(\?\S*)?$/i;
            }

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

            $('#info').off('click').on('click', function(e) {
                e.preventDefault();
                var $dialog = bootbox.dialog(
                    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position:absolute; top:12px; right:16px; font-size:24px; line-height:1; font-weight:300; opacity:0.6; cursor:pointer; background:none; border:none; color:#0f172a; padding:0; z-index:100;">&times;</button>' +
                    '<div class="text-center" style="padding: 16px 16px 12px 16px;">' +
                    '<h4 style="margin:0 0 6px 0; font-size:16px; font-weight:600; color:#0f172a;">OpenSID File Manager</h4>' +
                    '<p style="margin:0 0 16px 0;"><a href="https://opendesa.id" target="_blank" rel="noopener" style="color:#2563eb; text-decoration:none; font-weight:500;">opendesa.id</a></p>' +
                    '<p style="margin:0 0 6px 0; color:#334155; font-size:13px;">Pengembang: <strong>Tim Pengembang OpenDesa</strong></p>' +
                    '<p style="margin:0 0 16px 0; color:#64748b; font-size:12px;">Hak Cipta &copy; 2016 - {{ date("Y") }} <a href="https://opendesa.id" target="_blank" rel="noopener" style="color:#475569;">Perkumpulan Desa Digital Terbuka</a></p>' +
                    '<p style="margin:0; color:#94a3b8; font-size:11px;">Lisensi: <a href="http://www.gnu.org/licenses/gpl.html" target="_blank" rel="noopener" style="color:#64748b;">GPL v3</a></p>' +
                    '</div>',
                    []
                );
                $dialog.find('.modal-footer').remove();
                $dialog.find('.modal-header').remove();
            });

            $(document).on('click', '#sorting-dropdown-btn, .sorting-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $('#sorting-menu');
                var isVisible = $menu.is(':visible');
                $('.dropdown-menu').hide();
                if (!isVisible) {
                    $menu.show();
                }
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('#sorting-dropdown-btn, #sorting-menu, .sorting-btn').length) {
                    $('#sorting-menu').hide();
                }
            });

            $(document).on('click', '#sorting-menu .sorter', function () {
                $('#sorting-menu').hide();
            });

            // Re-initialize tooltips with container: 'body'
            $('.navbar .tip, .navbar [title], .breadcrumb-container .tip, .breadcrumb-container [title]').tooltip('destroy').tooltip({
                container: 'body',
                placement: 'bottom'
            });
            $('ul.grid .tip, ul.grid .tip-top, ul.grid [title]').tooltip('destroy').tooltip({
                container: 'body',
                placement: 'top'
            });
            $('.tip-bottom').tooltip('destroy').tooltip({
                container: 'body',
                placement: 'bottom'
            });
            $('.tip-left').tooltip('destroy').tooltip({
                container: 'body',
                placement: 'left'
            });
            $('.tip-right').tooltip('destroy').tooltip({
                container: 'body',
                placement: 'right'
            });
        });
    </script>

    <style>
        /* Modern Reset & Base */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            padding-top: 50px;
        }

        /* Navbar & Top Toolbar */
        .navbar-fixed-top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            margin-bottom: 0;
        }
        .navbar-inner {
            background: #ffffff !important;
            background-image: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            min-height: 46px;
            padding: 3px 12px;
        }
        .navbar .brand {
            display: none;
        }
        .navbar .btn {
            background: #ffffff;
            background-image: none;
            border: 1px solid #cbd5e1;
            color: #475569;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            padding: 5px 10px;
            transition: all 0.15s ease;
            text-shadow: none;
        }
        .navbar .btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
            color: #0f172a;
        }
        .navbar .btn.upload-btn {
            background: #3b82f6 !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            font-weight: 500;
        }
        .navbar .btn.upload-btn:hover {
            background: #2563eb !important;
        }
        .navbar .btn.upload-btn i {
            filter: brightness(0) invert(1);
        }
        .navbar .btn.btn-inverse {
            background: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
        }

        /* Filter & Search Bar */
        .filters .types {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-height: 38px;
            gap: 6px;
        }
        .filters .types span {
            margin: 0 !important;
            line-height: 1;
            color: #64748b;
            font-size: 12px;
            font-weight: 500;
        }
        .filters .types input#filter-input {
            margin: 0 !important;
            height: 30px;
            padding: 4px 10px;
            font-size: 13px;
            border-radius: 5px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #1e293b;
            outline: none;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }
        .filters .types input#filter-input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .filters .types label.btn {
            padding: 4px 8px;
            border-radius: 5px;
            margin: 0;
            font-size: 12px;
        }

        /* Breadcrumbs Container & Alignment */
        .breadcrumb-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 6px 12px;
            margin: 10px 0 14px 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 42px;
            box-sizing: border-box;
            position: relative;
            z-index: 50;
        }
        .breadcrumb {
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px;
            list-style: none;
            overflow: visible !important;
        }
        .breadcrumb > li {
            text-shadow: none;
            display: inline-flex !important;
            align-items: center !important;
            line-height: 1 !important;
            margin: 0;
        }
        .breadcrumb > li a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            padding: 4px 6px;
            border-radius: 4px;
            display: inline-flex !important;
            align-items: center !important;
            transition: color 0.15s ease, background 0.15s ease;
        }
        .breadcrumb > li a:hover {
            color: #2563eb;
            background: #eff6ff;
        }
        .breadcrumb > li.active {
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
            padding: 4px 6px;
        }
        .breadcrumb .divider {
            color: #cbd5e1;
            padding: 0 2px;
            display: inline-flex;
            align-items: center;
        }
        .breadcrumb small {
            background: #f1f5f9;
            color: #64748b;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 6px;
            display: inline-flex;
            align-items: center;
            line-height: 1.2;
        }
        .breadcrumb-actions {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            height: 28px !important;
        }
        .bc-action-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 28px !important;
            box-sizing: border-box !important;
            border-radius: 5px !important;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            color: #475569 !important;
            text-decoration: none !important;
            font-size: 13px !important;
            line-height: 1 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            vertical-align: middle !important;
            cursor: pointer !important;
            margin: 0 !important;
            padding: 0 6px !important;
            gap: 4px !important;
        }
        .bc-action-btn.tip {
            width: 28px !important;
            padding: 0 !important;
        }
        #sorting-dropdown-btn {
            width: auto !important;
            min-width: 44px !important;
            padding: 0 6px !important;
        }
        .bc-action-btn:hover {
            background: #f1f5f9 !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }
        .bc-action-btn i,
        .bc-action-btn [class^="icon-"],
        .bc-action-btn [class*=" icon-"] {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
            vertical-align: middle !important;
            display: inline-block !important;
        }
        .bc-action-btn .caret {
            display: inline-block !important;
            vertical-align: middle !important;
            margin: 0 !important;
            border-top: 4px solid #475569 !important;
            border-right: 4px solid transparent !important;
            border-left: 4px solid transparent !important;
            border-bottom: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }
        i[class^="icon-"], i[class*=" icon-"] {
            vertical-align: middle !important;
            margin-top: 0 !important;
            display: inline-block;
        }

        /* Dropdown & Menus */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 999999;
            display: none;
            float: left;
            min-width: 160px;
            padding: 6px 0;
            margin: 4px 0 0;
            list-style: none;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
        }
        .dropdown-menu.pull-right,
        #sorting-menu {
            right: 0 !important;
            left: auto !important;
        }
        .dropdown-menu > li > a {
            display: block;
            padding: 6px 16px;
            clear: both;
            font-weight: 500;
            line-height: 18px;
            color: #334155;
            white-space: nowrap;
            text-decoration: none;
            font-size: 12px;
            transition: background 0.12s ease;
        }
        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            color: #1e293b;
            text-decoration: none;
            background-color: #f1f5f9;
            background-image: none;
        }

        /* Modern Grid & Cards */
        ul.grid {
            padding: 4px 0;
            list-style: none;
        }
        ul.grid li {
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
            overflow: hidden !important;
        }
        ul.grid li:hover {
            border-color: #cbd5e1 !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04) !important;
        }
        ul.grid li figure {
            border-radius: 5px !important;
            overflow: hidden !important;
        }
        ul.grid li figure .img-precontainer,
        ul.grid li figure .img-container,
        ul.grid li figure .img-container-mini,
        ul.grid li figure .img-precontainer-mini,
        ul.grid li figure .filetype,
        ul.grid li figure .cover,
        ul.grid li figure img {
            border-top-left-radius: 5px !important;
            border-top-right-radius: 5px !important;
        }
        ul.grid li figure .box {
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
            padding: 6px 8px 8px 8px !important;
            border-radius: 0 0 5px 5px !important;
            min-height: 28px;
            box-sizing: border-box;
        }
        ul.grid li figure .box h4 {
            color: #1e293b;
            font-weight: 500;
            font-size: 12px;
            margin: 0 !important;
            line-height: 1.3 !important;
            padding-bottom: 2px !important;
        }
        ul.grid li figure figcaption {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(4px);
            border-radius: 0 0 5px 5px !important;
            padding: 5px 6px 6px 6px !important;
            min-height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
        }
        ul.grid li figure figcaption form {
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-around !important;
            width: 100% !important;
            height: 100% !important;
        }
        ul.grid li figure figcaption a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 22px !important;
            height: 22px !important;
            margin: 0 2px !important;
            border-radius: 4px !important;
            color: #ffffff !important;
            line-height: 1 !important;
            transition: background 0.15s ease;
        }
        ul.grid li figure figcaption a i {
            margin: 0 !important;
            line-height: 1 !important;
            vertical-align: middle !important;
            font-size: 12px !important;
        }
        ul.grid li figure figcaption a:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Checkbox Selector */
        .selector {
            border-radius: 4px !important;
        }
        .selector label.cont {
            border-radius: 4px !important;
            overflow: hidden;
        }
        .selector .checkmark {
            border-radius: 3px !important;
        }

        /* Floating Tooltips */
        .tooltip {
            position: absolute !important;
            z-index: 100000 !important;
            display: block;
            visibility: visible;
            font-size: 11px;
            line-height: 1.3;
            opacity: 0;
            filter: alpha(opacity=0);
            pointer-events: none;
        }
        .tooltip.in {
            opacity: 0.95 !important;
            filter: alpha(opacity=95);
        }
        .tooltip.top {
            margin-top: -3px;
            padding: 5px 0;
        }
        .tooltip.right {
            margin-left: 3px;
            padding: 0 5px;
        }
        .tooltip.bottom {
            margin-top: 3px;
            padding: 5px 0;
        }
        .tooltip.left {
            margin-left: -3px;
            padding: 0 5px;
        }
        .tooltip-inner {
            max-width: 220px;
            padding: 5px 9px;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            background-color: #0f172a !important;
            border-radius: 5px;
            font-weight: 500;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.15);
        }
        .tooltip-arrow {
            position: absolute;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
        }
        .tooltip.top .tooltip-arrow {
            bottom: 0;
            left: 50%;
            margin-left: -5px;
            border-width: 5px 5px 0;
            border-top-color: #0f172a !important;
        }
        .tooltip.right .tooltip-arrow {
            top: 50%;
            left: 0;
            margin-top: -5px;
            border-width: 5px 5px 5px 0;
            border-right-color: #0f172a !important;
        }
        .tooltip.left .tooltip-arrow {
            top: 50%;
            right: 0;
            margin-top: -5px;
            border-width: 5px 0 5px 5px;
            border-left-color: #0f172a !important;
        }
        .tooltip.bottom .tooltip-arrow {
            top: 0;
            left: 50%;
            margin-left: -5px;
            border-width: 0 5px 5px;
            border-bottom-color: #0f172a !important;
        }

        /* Checkbox */
        .selector label.cont {
            border-radius: 4px;
        }

        /* Centering Images & Thumbnails in All Views */
        .img-container, .img-container-mini {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin: 0 auto !important;
        }
        .img-container img, .img-container-mini img {
            margin: auto !important;
            display: block !important;
            object-fit: contain !important;
        }
        .list-view1.grid .img-precontainer, .list-view2.grid .img-precontainer {
            display: none !important;
        }
        .list-view1.grid .img-precontainer-mini .filetype,
        .list-view2.grid .img-precontainer-mini .filetype {
            display: none !important;
        }
        /* List View 1 & 2 Thumbnail Position */
        .list-view1.grid .img-precontainer-mini,
        .list-view2.grid .img-precontainer-mini {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            left: 8px !important;
            width: 28px !important;
            height: 28px !important;
            overflow: hidden !important;
            border-radius: 4px !important;
            background: transparent !important;
            z-index: 5 !important;
        }
        .list-view1.grid figure:has(.selector) .img-precontainer-mini,
        .list-view2.grid figure:has(.selector) .img-precontainer-mini,
        .list-view1.grid li:not(.no-selector) .img-precontainer-mini,
        .list-view2.grid li:not(.no-selector) .img-precontainer-mini {
            left: 28px !important;
        }
        .list-view1.grid li.no-selector .img-precontainer-mini,
        .list-view2.grid li.no-selector .img-precontainer-mini {
            left: 8px !important;
        }
        .list-view1.grid .img-container-mini,
        .list-view2.grid .img-container-mini {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            left: 0 !important;
        }
        .list-view1.grid .img-container-mini img,
        .list-view2.grid .img-container-mini img {
            max-width: 26px !important;
            max-height: 26px !important;
            width: auto !important;
            height: auto !important;
            display: block !important;
            margin: auto !important;
            object-fit: contain !important;
        }

        /* List View 1 (Single Full-Width List) */
        .list-view1.grid li {
            margin-bottom: 4px !important;
            width: 100% !important;
        }
        .list-view1.grid li figure {
            display: block !important;
            height: 38px !important;
            line-height: 38px !important;
            position: relative !important;
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            padding: 0 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }
        .list-view1.grid li figure:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }
        .list-view1.grid .selector {
            position: absolute !important;
            left: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            z-index: 10 !important;
        }
        .list-view1.grid figure .box {
            display: block !important;
            height: 38px !important;
            line-height: 38px !important;
            margin: 0 0 0 42px !important;
            padding: 0 440px 0 0 !important;
            border: none !important;
            background: transparent !important;
            overflow: hidden !important;
        }
        .list-view1.grid figure:has(.selector) .box,
        .list-view1.grid li:not(.no-selector) figure .box {
            margin-left: 62px !important;
        }
        .list-view1.grid li.no-selector figure .box {
            margin-left: 42px !important;
        }
        .list-view1.grid figure .box h4 {
            margin: 0 !important;
            padding: 0 !important;
            height: 38px !important;
            line-height: 38px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            text-align: left !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }
        .list-view1.grid .file-date, .list-view1.grid .file-size, .list-view1.grid .file-extension {
            position: absolute !important;
            top: 0 !important;
            height: 38px !important;
            line-height: 38px !important;
            margin: 0 !important;
            display: block !important;
        }
        .list-view1.grid .file-extension { width: 55px; right: 360px; font-size: 11px; color: #64748b; }
        .list-view1.grid .file-size { width: 65px; right: 290px; font-size: 11px; color: #64748b; }
        .list-view1.grid .file-date { width: 120px; right: 160px; font-size: 11px; color: #64748b; white-space: nowrap; }
        .list-view1.grid figcaption {
            position: absolute !important;
            right: 6px !important;
            top: 0 !important;
            bottom: 0 !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            z-index: 15 !important;
        }
        .list-view1.grid figcaption form {
            display: flex !important;
            align-items: center !important;
            gap: 3px !important;
            height: 100% !important;
            margin: 0 !important;
        }
        .list-view1.grid figcaption a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 22px !important;
            height: 22px !important;
            color: #64748b !important;
            border-radius: 4px !important;
            background: #f1f5f9 !important;
            transition: all 0.15s ease !important;
        }
        .list-view1.grid figcaption a:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .list-view1.grid figcaption a i {
            margin: 0 !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            font-size: 11px !important;
        }

        /* List View 2 (Multi-Column Compact Cards) */
        .list-view2.grid li {
            margin-bottom: 4px !important;
        }
        .list-view2.grid li figure {
            display: block !important;
            height: 38px !important;
            line-height: 38px !important;
            position: relative !important;
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            padding: 0 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden !important;
        }
        .list-view2.grid li figure:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }
        .list-view2.grid .selector {
            position: absolute !important;
            left: 6px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            z-index: 10 !important;
        }
        .list-view2.grid figure .box {
            display: block !important;
            height: 38px !important;
            line-height: 38px !important;
            margin: 0 0 0 42px !important;
            padding: 0 92px 0 0 !important;
            border: none !important;
            background: transparent !important;
            overflow: hidden !important;
        }
        .list-view2.grid figure:has(.selector) .box,
        .list-view2.grid li:not(.no-selector) figure .box {
            margin-left: 62px !important;
        }
        .list-view2.grid li.no-selector figure .box {
            margin-left: 42px !important;
        }
        .list-view2.grid figure .box h4 {
            margin: 0 !important;
            padding: 0 !important;
            height: 38px !important;
            line-height: 38px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            text-align: left !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }
        .list-view2.grid figure .box h4 a {
            color: #1e293b !important;
            text-decoration: none !important;
        }
        .list-view2.grid figcaption {
            position: absolute !important;
            right: 4px !important;
            top: 0 !important;
            bottom: 0 !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-end !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            z-index: 15 !important;
        }
        .list-view2.grid figcaption form {
            display: flex !important;
            align-items: center !important;
            gap: 2px !important;
            margin: 0 !important;
        }
        .list-view2.grid figcaption a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 20px !important;
            height: 20px !important;
            color: #64748b !important;
            border-radius: 4px !important;
            background: #f1f5f9 !important;
            transition: all 0.15s ease !important;
            padding: 0 !important;
        }
        .list-view2.grid figcaption a:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .list-view2.grid figcaption a i {
            margin: 0 !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            font-size: 10px !important;
        }
        .list-view2.grid .file-date,
        .list-view2.grid .file-size,
        .list-view2.grid .file-extension {
            display: none !important;
        }

        /* Modern Lightbox Preview (Images & Iframes) */
        .featherlight .featherlight-content {
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            background: #ffffff !important;
            overflow: hidden !important;
            padding: 10px !important;
        }
        .featherlight .featherlight-inner,
        .featherlight .featherlight-image {
            border-radius: 5px !important;
            display: block !important;
            max-width: 90vw !important;
            max-height: 85vh !important;
        }
        .featherlight-close-icon {
            border-radius: 5px !important;
        }
        .featherlight-iframe .featherlight-content {
            width: 85vw !important;
            height: 85vh !important;
            max-width: 1050px !important;
            padding: 8px !important;
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            background: #ffffff !important;
        }
        .featherlight-iframe iframe {
            border: none !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 5px !important;
        }

        /* Modern Uploader */
        .uploader {
            background: #f8fafc;
            border-radius: 5px !important;
            padding: 16px;
        }
        .upload-tabbable {
            border-radius: 5px !important;
            border: 2px dashed #cbd5e1;
            background: #ffffff;
            padding: 20px;
        }

        /* Universal 5px Rounded for All UI Components */
        .btn, button, input[type="button"], input[type="submit"], input[type="reset"],
        .btn-group, .btn-group-vertical, .btn-toolbar,
        input, select, textarea, .uneditable-input,
        .modal, .modal-dialog, .modal-content, .bootbox,
        .alert, .well, .thumbnail,
        .progress, .progress-bar, .bar,
        .nav-tabs, .nav-pills, .nav-tabs > li > a, .nav-pills > li > a,
        .tab-content, .tab-pane,
        .badge, .label,
        .popover, .tooltip-inner,
        .uploader, .upload-tabbable, .fileupload-buttonbar, .fileinput-button,
        .close-uploader, .start, .cancel, .delete {
            border-radius: 5px !important;
        }
        .btn {
            text-shadow: none !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.15s ease !important;
        }
        .btn-success {
            background: #16a34a !important;
            border-color: #15803d !important;
            color: #ffffff !important;
        }
        .btn-success:hover {
            background: #15803d !important;
            border-color: #166534 !important;
        }
        .btn-primary {
            background: #2563eb !important;
            border-color: #1d4ed8 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover {
            background: #1d4ed8 !important;
        }
        .btn-inverse, .close-uploader {
            background: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
        }
        .btn-inverse:hover, .close-uploader:hover {
            background: #1e293b !important;
            color: #ffffff !important;
        }
        .btn-danger {
            background: #ef4444 !important;
            border-color: #dc2626 !important;
            color: #ffffff !important;
        }
        .btn-warning {
            background: #f59e0b !important;
            border-color: #d97706 !important;
            color: #ffffff !important;
        }
        .modal {
            border-radius: 5px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        .modal-header {
            border-top-left-radius: 5px !important;
            border-top-right-radius: 5px !important;
        }
        .modal-footer {
            border-bottom-left-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }
        .progress {
            border-radius: 5px !important;
            overflow: hidden !important;
        }
        .progress .bar {
            border-radius: 5px !important;
        }
    </style>
</head>
<body>
    {{--
        Config bridge: include.js (unmodified) reads its configuration from
        these hidden inputs rather than from an API call. Keep ids/values in
        lockstep with what that bundle expects.
    --}}
    <input type="hidden" id="ftp" value="">
    <input type="hidden" id="popup" value="{{ $popup ? 1 : 0 }}">
    <input type="hidden" id="callback" value="{{ $callback }}">
    <input type="hidden" id="crossdomain" value="{{ $crossdomain ? 1 : 0 }}">
    <input type="hidden" id="editor" value="{{ $editor }}">
    <input type="hidden" id="view" value="{{ $viewType }}">
    <input type="hidden" id="subdir" value="{{ $subdir }}">
    <input type="hidden" id="field_id" value="{{ $fieldId }}">
    <input type="hidden" id="multiple" value="{{ $multiple }}">
    <input type="hidden" id="type_param" value="{{ $type }}">
@php
    $filesManager = app(\OpenSID\LaravelFilemanager\Services\FilesystemManager::class);
    $mediaBaseUrl = $filesManager->url('');
    $mediaBasePath = trim((string) parse_url($mediaBaseUrl, PHP_URL_PATH), '/');
    $mediaBasePath = $mediaBasePath !== '' ? $mediaBasePath . '/' : '';
    $subdirWithSlash = $subdir !== '' ? trim($subdir, '/') . '/' : '';
    $curDir = '/' . $mediaBasePath . $subdirWithSlash;
@endphp

    <input type="hidden" id="insert_folder_name" value="{{ __('filemanager::filemanager.Insert_Folder_Name') }}">
    <input type="hidden" id="rename_existing_folder" value="{{ __('filemanager::filemanager.Rename_existing_folder') }}">
    <input type="hidden" id="new_folder" value="{{ __('filemanager::filemanager.New_Folder') }}">
    <input type="hidden" id="ok" value="{{ __('filemanager::filemanager.OK') }}">
    <input type="hidden" id="cancel" value="{{ __('filemanager::filemanager.Cancel') }}">
    <input type="hidden" id="rename" value="{{ __('filemanager::filemanager.Rename') }}">
    <input type="hidden" id="lang_duplicate" value="{{ __('filemanager::filemanager.Duplicate') }}">
    <input type="hidden" id="duplicate" value="{{ $canUpload ? 1 : 0 }}">
    <input type="hidden" id="base_url" value="{{ str_replace('/index.php', '', url('/')) }}">
    <input type="hidden" id="upload_dir" value="{{ '/' . $mediaBasePath }}">
    <input type="hidden" id="cur_dir" value="{{ $curDir }}">
    <input type="hidden" id="ftp_base_url" value="">
    <input type="hidden" id="fldr_value" value="{{ $subdirWithSlash }}">
    <input type="hidden" id="sub_folder" value="">
    <input type="hidden" id="return_relative_url" value="{{ $relativeUrl ? 1 : 0 }}">
    <input type="hidden" id="file_number_limit_js" value="500">
    <input type="hidden" id="sort_by" value="{{ $sortBy }}">
    <input type="hidden" id="descending" value="{{ $descending ? 1 : 0 }}">
    <input type="hidden" id="current_url" value="{{ url()->current() }}">
    <input type="hidden" id="lang_show_url" value="{{ __('filemanager::filemanager.Show_url') }}">
    <input type="hidden" id="copy_cut_files_allowed" value="{{ $canDelete ? 1 : 0 }}">
    <input type="hidden" id="copy_cut_dirs_allowed" value="{{ $canDelete ? 1 : 0 }}">
    <input type="hidden" id="copy_cut_max_size" value="false">
    <input type="hidden" id="copy_cut_max_count" value="false">
    <input type="hidden" id="lang_copy" value="{{ __('filemanager::filemanager.Copy') }}">
    <input type="hidden" id="lang_cut" value="{{ __('filemanager::filemanager.Cut') }}">
    <input type="hidden" id="lang_paste" value="{{ __('filemanager::filemanager.Paste') }}">
    <input type="hidden" id="lang_paste_here" value="{{ __('filemanager::filemanager.Paste_Here') }}">
    <input type="hidden" id="lang_paste_confirm" value="{{ __('filemanager::filemanager.Paste_Confirm') }}">
    <input type="hidden" id="lang_files" value="{{ __('filemanager::filemanager.Files') }}">
    <input type="hidden" id="lang_folders" value="{{ __('filemanager::filemanager.Folders') }}">
    <input type="hidden" id="lang_files_on_clipboard" value="{{ __('filemanager::filemanager.Files_ON_Clipboard') }}">
    <input type="hidden" id="clipboard" value="{{ $clipboardHasContent ? 1 : 0 }}">
    <input type="hidden" id="lang_clear_clipboard_confirm" value="{{ __('filemanager::filemanager.Clear_Clipboard_Confirm') }}">
    <input type="hidden" id="lang_lang_change" value="{{ __('filemanager::filemanager.Lang_Change') }}">
    <input type="hidden" id="edit_text_files_allowed" value="{{ config('filemanager.text_editing_enabled') && $canUpload ? 1 : 0 }}">
    <input type="hidden" id="lang_edit_file" value="{{ __('filemanager::filemanager.Edit_File') }}">
    <input type="hidden" id="lang_new_file" value="{{ __('filemanager::filemanager.New_File') }}">
    <input type="hidden" id="lang_filename" value="{{ __('filemanager::filemanager.Filename') }}">
    <input type="hidden" id="lang_file_info" value="{{ __('filemanager::filemanager.File_info') }}">
    <input type="hidden" id="lang_error_upload" value="{{ __('filemanager::filemanager.Error_Upload') }}">
    <input type="hidden" id="lang_select" value="{{ __('filemanager::filemanager.Select') }}">
    <input type="hidden" id="lang_extract" value="{{ __('filemanager::filemanager.Extract') }}">
    <input type="hidden" id="extract_files" value="0">
    <input type="hidden" id="transliteration" value="{{ config('filemanager.transliterate') ? 'true' : 'false' }}">
    <input type="hidden" id="convert_spaces" value="{{ config('filemanager.convert_spaces') ? 'true' : 'false' }}">
    <input type="hidden" id="replace_with" value="{{ config('filemanager.convert_spaces') ? config('filemanager.replace_with') : '' }}">
    <input type="hidden" id="lower_case" value="{{ config('filemanager.lower_case') ? 'true' : 'false' }}">
    <input type="hidden" id="show_folder_size" value="0">
    <input type="hidden" id="add_time_to_img" value="0">

    @if ($canUpload)
        @include('filemanager::partials.upload-panel')
    @endif

    <div class="container-fluid">
        @include('filemanager::partials.toolbar')

        <div class="header-container">
            <div class="sorter-container list-view{{ $viewType }}">
                <a class="sorter sort-name {{ $sortBy == 'name' ? ($descending ? 'descending' : 'ascending') : '' }}" href="javascript:void('')" data-sort="name"><span class="file-name">{{ __('filemanager::filemanager.Filename') }}</span></a>
                <a class="sorter sort-date {{ $sortBy == 'date' ? ($descending ? 'descending' : 'ascending') : '' }}" href="javascript:void('')" data-sort="date"><span class="file-date">{{ __('filemanager::filemanager.Date') }}</span></a>
                <a class="sorter sort-size {{ $sortBy == 'size' ? ($descending ? 'descending' : 'ascending') : '' }}" href="javascript:void('')" data-sort="size"><span class="file-size">{{ __('filemanager::filemanager.Size') }}</span></a>
                <a class="sorter sort-extension {{ $sortBy == 'extension' ? ($descending ? 'descending' : 'ascending') : '' }}" href="javascript:void('')" data-sort="extension"><span class="file-extension">{{ __('filemanager::filemanager.Type') }}</span></a>
                <span class="file-operations">{{ __('filemanager::filemanager.Operations') }}</span>
            </div>
        </div>

        <div class="row-fluid">
            <div class="row-fluid ff-container">
                <div class="span12">
                    <h4 id="help">{{ __('filemanager::filemanager.Swipe_help') }}</h4>

                    <ul class="grid cs-style-2 list-view{{ $viewType }}" id="main-item-container">
                        @include('filemanager::partials.grid')
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="loading_container" style="display:none;">
        <div id="loading" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
        <img id="loading_animation" src="{{ filemanager_asset('img/storing_animation.gif') }}" alt="loading" style="z-index:10001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%">
    </div>

    {{-- Modal Crop Gambar Modern --}}
    <div id="cropper-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.8); z-index:9999999; padding:15px; box-sizing:border-box; overflow-y:auto; align-items:center; justify-content:center;">
        <div style="background:#ffffff; border-radius:5px; max-width:850px; width:100%; margin:auto; box-shadow:0 25px 50px -12px rgba(0,0,0,0.3); overflow:hidden; display:flex; flex-direction:column; position:relative; z-index:10000000;">
            {{-- Header Modal --}}
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 18px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                <div>
                    <h3 style="margin:0; font-size:15px; font-weight:600; color:#0f172a;">{{ __('filemanager::filemanager.Crop') }}</h3>
                    <small id="cropper-file-name" style="color:#64748b; font-size:12px;"></small>
                </div>
                <button type="button" id="cropper-close-btn" style="background:none; border:none; font-size:24px; line-height:1; color:#64748b; cursor:pointer; padding:2px 8px; border-radius:5px;">&times;</button>
            </div>

            {{-- Workspace Gambar --}}
            <div style="padding:15px; background:#0f172a; text-align:center; min-height:340px; max-height:55vh; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                <img id="cropper-target-image" src="" alt="Crop target" style="max-width:100%; max-height:50vh; display:block;">
            </div>

            {{-- Toolbar Crop Controls --}}
            <div style="padding:12px 16px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; flex-wrap:wrap; gap:8px; align-items:center; justify-content:space-between;">
                {{-- Aspect Ratio Buttons --}}
                <div style="display:inline-flex; gap:4px; align-items:center;">
                    <span style="font-size:12px; font-weight:600; color:#475569; margin-right:4px;">Rasio:</span>
                    <button type="button" class="btn btn-small cropper-ratio-btn active" data-ratio="NaN" style="border-radius:5px; font-size:11px; padding:3px 8px;">Bebas</button>
                    <button type="button" class="btn btn-small cropper-ratio-btn" data-ratio="1" style="border-radius:5px; font-size:11px; padding:3px 8px;">1:1</button>
                    <button type="button" class="btn btn-small cropper-ratio-btn" data-ratio="1.3333333333" style="border-radius:5px; font-size:11px; padding:3px 8px;">4:3</button>
                    <button type="button" class="btn btn-small cropper-ratio-btn" data-ratio="1.7777777778" style="border-radius:5px; font-size:11px; padding:3px 8px;">16:9</button>
                </div>

                {{-- Action / Transform Buttons --}}
                <div style="display:inline-flex; gap:4px; align-items:center;">
                    <button type="button" id="cropper-rotate-left" class="btn btn-small" title="Putar Kiri 90°" style="border-radius:5px; padding:3px 8px; font-size:12px;"><i class="icon-repeat" style="transform: scaleX(-1); display:inline-block;"></i> -90°</button>
                    <button type="button" id="cropper-rotate-right" class="btn btn-small" title="Putar Kanan 90°" style="border-radius:5px; padding:3px 8px; font-size:12px;"><i class="icon-repeat"></i> +90°</button>
                    <button type="button" id="cropper-flip-h" class="btn btn-small" title="Flip Horizontal" style="border-radius:5px; padding:3px 8px; font-size:12px;">Flip H</button>
                    <button type="button" id="cropper-flip-v" class="btn btn-small" title="Flip Vertical" style="border-radius:5px; padding:3px 8px; font-size:12px;">Flip V</button>
                    <button type="button" id="cropper-reset" class="btn btn-small" title="Reset" style="border-radius:5px; padding:3px 8px; font-size:12px;"><i class="icon-refresh"></i></button>
                </div>
            </div>

            {{-- Footer & Save Options --}}
            <div style="padding:12px 18px; border-top:1px solid #e2e8f0; background:#ffffff; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:10px;">
                <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:260px;">
                    <label style="margin:0; font-size:12px; color:#334155; display:inline-flex; align-items:center; gap:4px; cursor:pointer;">
                        <input type="radio" name="cropper_save_mode" value="overwrite" checked style="margin:0;">
                        <span>Timpa berkas asli</span>
                    </label>
                    <label style="margin:0; font-size:12px; color:#334155; display:inline-flex; align-items:center; gap:4px; cursor:pointer;">
                        <input type="radio" name="cropper_save_mode" value="new" style="margin:0;">
                        <span>Simpan berkas baru:</span>
                    </label>
                    <input type="text" id="cropper-new-name" placeholder="nama-file-baru" style="height:26px; padding:2px 8px; font-size:12px; border:1px solid #cbd5e1; border-radius:5px; display:none; max-width:180px; margin:0;">
                </div>

                <div style="display:flex; gap:8px;">
                    <button type="button" id="cropper-cancel-btn" class="btn" style="border-radius:5px; font-size:12px; padding:5px 14px;">Batal</button>
                    <button type="button" id="cropper-save-btn" class="btn btn-primary" style="border-radius:5px; font-size:12px; padding:5px 16px; background:#2563eb; color:#ffffff; font-weight:500;">
                        <i class="icon-ok icon-white"></i> Simpan Gambar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var cropperInstance = null;
            var currentFilePath = '';
            var currentFileName = '';
            var scaleX = 1;
            var scaleY = 1;

            // Buka Modal Crop Gambar
            $(document).on('click', '.crop-file-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $btn = $(this);
                currentFilePath = $btn.attr('data-path') || $btn.closest('figure').attr('data-path') || '';
                currentFileName = $btn.attr('data-name') || $btn.closest('figure').attr('data-name') || '';
                var imageUrl = $btn.attr('data-url') || $btn.closest('figure').find('a.preview').attr('href') || '';

                if (!imageUrl) {
                    bootbox.alert('URL gambar tidak ditemukan.');
                    return;
                }

                $('#cropper-file-name').text(currentFileName);
                $('#cropper-new-name').val(currentFileName.replace(/(\.[^.]+)$/, '-crop$1')).hide();
                $('input[name="cropper_save_mode"][value="overwrite"]').prop('checked', true);

                var $img = $('#cropper-target-image');
                $img.attr('src', imageUrl);

                $('#cropper-modal').css('display', 'flex').show();

                if (cropperInstance) {
                    try { cropperInstance.destroy(); } catch(err){}
                    cropperInstance = null;
                }

                scaleX = 1;
                scaleY = 1;

                if (typeof Cropper === 'undefined') {
                    bootbox.alert('Pustaka Cropper.js sedang dimuat, silakan coba beberapa saat lagi.');
                    return;
                }

                setTimeout(function () {
                    try {
                        cropperInstance = new Cropper($img[0], {
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.85,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    } catch(err) {
                        console.error('Cropper error:', err);
                    }
                }, 100);
            });

            // Tutup Modal Crop
            function closeCropperModal() {
                $('#cropper-modal').hide();
                if (cropperInstance) {
                    try { cropperInstance.destroy(); } catch(err){}
                    cropperInstance = null;
                }
            }

            $('#cropper-close-btn, #cropper-cancel-btn').on('click', closeCropperModal);

            // Ganti Pilihan Mode Simpan
            $('input[name="cropper_save_mode"]').on('change', function () {
                if ($(this).val() === 'new') {
                    $('#cropper-new-name').show().focus();
                } else {
                    $('#cropper-new-name').hide();
                }
            });

            // Rasio Crop Preset
            $(document).on('click', '.cropper-ratio-btn', function () {
                $('.cropper-ratio-btn').removeClass('active btn-primary');
                $(this).addClass('active btn-primary');
                if (cropperInstance) {
                    var ratio = parseFloat($(this).attr('data-ratio'));
                    cropperInstance.setAspectRatio(ratio);
                }
            });

            // Putar dan Flip
            $('#cropper-rotate-left').on('click', function () {
                if (cropperInstance) cropperInstance.rotate(-90);
            });

            $('#cropper-rotate-right').on('click', function () {
                if (cropperInstance) cropperInstance.rotate(90);
            });

            $('#cropper-flip-h').on('click', function () {
                if (cropperInstance) {
                    scaleX = -scaleX;
                    cropperInstance.scaleX(scaleX);
                }
            });

            $('#cropper-flip-v').on('click', function () {
                if (cropperInstance) {
                    scaleY = -scaleY;
                    cropperInstance.scaleY(scaleY);
                }
            });

            $('#cropper-reset').on('click', function () {
                if (cropperInstance) {
                    scaleX = 1;
                    scaleY = 1;
                    cropperInstance.reset();
                }
            });

            // Simpan Hasil Crop ke Server
            $('#cropper-save-btn').on('click', function () {
                if (!cropperInstance) return;

                var $btn = $(this);
                var saveMode = $('input[name="cropper_save_mode"]:checked').val();
                var newName = saveMode === 'new' ? $.trim($('#cropper-new-name').val()) : '';

                var canvas = cropperInstance.getCroppedCanvas();
                if (!canvas) {
                    bootbox.alert('Gagal membuat kanvas potongan gambar.');
                    return;
                }

                var ext = currentFileName.split('.').pop().toLowerCase();
                var mimeType = ext === 'png' ? 'image/png' : (ext === 'webp' ? 'image/webp' : 'image/jpeg');
                var dataUrl = canvas.toDataURL(mimeType, 0.92);

                $btn.prop('disabled', true).text('Menyimpan...');
                show_animation();

                $.ajax({
                    type: 'POST',
                    url: '/filemanager/execute?action=crop_image',
                    data: {
                        path: currentFilePath,
                        image_data: dataUrl,
                        name_new: newName
                    }
                }).done(function (res) {
                    hide_animation();
                    $btn.prop('disabled', false).html('<i class="icon-ok icon-white"></i> Simpan Gambar');

                    if (res && res !== '') {
                        bootbox.alert(res);
                    } else {
                        closeCropperModal();
                        setTimeout(function () {
                            window.location.href = $('#refresh').attr('href') + '&' + (new Date).getTime();
                        }, 250);
                    }
                }).fail(function () {
                    hide_animation();
                    $btn.prop('disabled', false).html('<i class="icon-ok icon-white"></i> Simpan Gambar');
                    bootbox.alert('Terjadi kesalahan saat menyimpan potongan gambar.');
                });
            });
        });
    </script>

    <script src="{{ filemanager_asset('js/tmpl.min.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/load-image.all.min.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/canvas-to-blob.min.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.iframe-transport.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-process.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-image.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-audio.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-video.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-validate.js') }}" defer></script>
    <script src="{{ filemanager_asset('js/jquery.fileupload-ui.js') }}" defer></script>
</body>
</html>
