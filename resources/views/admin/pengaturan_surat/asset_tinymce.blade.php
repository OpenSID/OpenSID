@push('scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce-651/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/tinymce.js') }}"></script>
    <script>
        $(document).ready(function() {
            var default_font = "{{ setting('font_surat') }}"
            var pratinjau = window.location.href.includes("pratinjau");
            if (! pratinjau) {
                plugins_tambahan = ['advlist', 'autolink', 'lists', 'charmap', 'hr', 'pagebreak', 'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'insertdatetime', 'nonbreaking', 'table', 'contextmenu', 'directionality', 'emoticons', 'paste', 'textcolor', 'code', 'responsivefilemanager', 'salintemplate', 'kodeisian'];
            } else {
                plugins_tambahan = ['advlist', 'autolink', 'lists', 'charmap', 'hr', 'pagebreak', 'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'insertdatetime', 'nonbreaking', 'table', 'contextmenu', 'directionality', 'emoticons', 'paste', 'textcolor', 'code'];
            }

            tinymce.init({
                selector: '.editor',
                promotion: false,
                formats: {
                    menjorok: {
                        block: 'p',
                        styles: {
                            'text-indent': '30px'
                        }
                    }
                },
                block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6; Div=div; Preformatted=pre; Blockquote=blockquote; Menjorok=menjorok',
                style_formats_merge: true,
                table_sizing_mode: 'relative',
                height: "{{ $height ?? 700 }}",
                // theme: 'silver',
                plugins: plugins_tambahan,
                content_style: `body { font-family: ${default_font}; }`,
                toolbar1: "removeformat | bold italic underline subscript superscript | bullist numlist outdent indent lineheight | alignleft aligncenter alignright alignjustify | blocks fontfamily fontsizeinput",
                toolbar2: "responsivefilemanager | salintemplate | kodeisian",
                // toolbar: [{ name: 'blocks', items: [ 'p', 'h', 'menjorok' ] },],
                image_advtab: true,
                external_plugins: {
                    "filemanager": "{{ asset('filemanager/plugin.min.js') }}"
                },
                // content_css: [
                //     '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                //     '//www.tinymce.com/css/codepen.min.css'
                // ],
                skin: 'tinymce-5',
                relative_urls: false,
                remove_script_host: false,
                entity_encoding: 'raw',
                // gak bisa pakai false
                // forced_root_block: false, 
                forced_root_block: ' ',
                font_family_formats: "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black; Bookman Old Style=bookman old style; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva;",
                setup: function(ed) {
                    ed.on('init', function(e) {
                        ed.execCommand("fontName", false, "${default_font}");
                    });
                },
            });

            tinymce.init({
                selector: 'textarea#format-custom',
                height: 500,
                plugins: 'table wordcount',
                skin: 'tinymce-5',
                content_style: '.left { text-align: left; } ' +
                    'img.left, audio.left, video.left { float: left; } ' +
                    'table.left { margin-left: 0px; margin-right: auto; } ' +
                    '.right { text-align: right; } ' +
                    'img.right, audio.right, video.right { float: right; } ' +
                    'table.right { margin-left: auto; margin-right: 0px; } ' +
                    '.center { text-align: center; } ' +
                    'img.center, audio.center, video.center { display: block; margin: 0 auto; } ' +
                    'table.center { margin: 0 auto; } ' +
                    '.full { text-align: justify; } ' +
                    'img.full, audio.full, video.full { display: block; margin: 0 auto; } ' +
                    'table.full { margin: 0 auto; } ' +
                    '.bold { font-weight: bold; } ' +
                    '.italic { font-style: italic; } ' +
                    '.underline { text-decoration: underline; } ' +
                    '.example1 {} ' +
                    'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }' +
                    '.tablerow1 { background-color: #D3D3D3; }',
                formats: {
                    alignleft: {
                        selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                        classes: 'left'
                    },
                    aligncenter: {
                        selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                        classes: 'center'
                    },
                    alignright: {
                        selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                        classes: 'right'
                    },
                    alignfull: {
                        selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                        classes: 'full'
                    },
                    bold: {
                        inline: 'span',
                        classes: 'bold'
                    },
                    italic: {
                        inline: 'span',
                        classes: 'italic'
                    },
                    underline: {
                        inline: 'span',
                        classes: 'underline',
                        exact: true
                    },
                    strikethrough: {
                        inline: 'del'
                    },
                    customformat: {
                        inline: 'span',
                        styles: {
                            color: '#00ff00',
                            fontSize: '20px'
                        },
                        attributes: {
                            title: 'My custom format'
                        },
                        classes: 'example1'
                    }
                },
                style_formats: [{
                        title: 'Custom format',
                        format: 'customformat'
                    },
                    {
                        title: 'Align left',
                        format: 'alignleft'
                    },
                    {
                        title: 'Align center',
                        format: 'aligncenter'
                    },
                    {
                        title: 'Align right',
                        format: 'alignright'
                    },
                    {
                        title: 'Align full',
                        format: 'alignfull'
                    },
                    {
                        title: 'Bold text',
                        inline: 'strong'
                    },
                    {
                        title: 'Red text',
                        inline: 'span',
                        styles: {
                            color: '#ff0000'
                        }
                    },
                    {
                        title: 'Red header',
                        block: 'h1',
                        styles: {
                            color: '#ff0000'
                        }
                    },
                    {
                        title: 'Badge',
                        inline: 'span',
                        styles: {
                            display: 'inline-block',
                            border: '1px solid #2276d2',
                            'border-radius': '5px',
                            padding: '2px 5px',
                            margin: '0 2px',
                            color: '#2276d2'
                        }
                    },
                    {
                        title: 'Table row 1',
                        selector: 'tr',
                        classes: 'tablerow1'
                    },
                    {
                        title: 'Image formats'
                    },
                    {
                        title: 'Image Left',
                        selector: 'img',
                        styles: {
                            'float': 'left',
                            'margin': '0 10px 0 10px'
                        }
                    },
                    {
                        title: 'Image Right',
                        selector: 'img',
                        styles: {
                            'float': 'right',
                            'margin': '0 0 10px 10px'
                        }
                    },
                ]
            });
        });
    </script>
@endpush
