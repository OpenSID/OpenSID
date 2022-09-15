@push('scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var default_font = "{{ setting('font_surat') }}"
            tinymce.init({
                selector: '.editor',
                table_sizing_mode: 'relative',
                height: "{{ $height ?? 700 }}",
                theme: 'silver',
                plugins: [
                    "advlist autolink lists charmap hr pagebreak",
                    "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor code salintemplate kodeisian",
                ],
                content_style: `body { font-family: ${default_font}; }`,
                toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | code | fontselect fontsizeselect | salintemplate | kodeisian",
                image_advtab: true,
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ],
                relative_urls: false,
                remove_script_host: false,
                entity_encoding: 'raw',
                forced_root_block: false,
                font_formats: "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva;",
                setup: function(ed) {
                    ed.on('init', function(e) {
                        ed.execCommand("fontName", false, "${default_font}");
                    });
                }
            });
        });
    </script>
@endpush
