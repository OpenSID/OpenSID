<div class="uploader">
    <div class="flex">
        <div class="text-center">
            <button class="btn btn-inverse close-uploader"><i class="icon-backward icon-white"></i> {{ __('filemanager::filemanager.Return_Files_List') }}</button>
        </div>
        <div class="space10"></div>
        <div class="tabbable upload-tabbable">
            <div class="container1">
                <div class="tab-content">
                    <div class="tab-pane active" id="baseUpload">
                        <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="container2">
                                <div class="fileupload-buttonbar">
                                    <div class="fileupload-progress">
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                            <div class="bar bar-success" style="width:0%;"></div>
                                        </div>
                                        <div class="progress-extended"></div>
                                    </div>
                                    <div class="text-center">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>{{ __('filemanager::filemanager.Upload_add_files') }}</span>
                                            <input type="file" name="files[]" multiple="multiple" accept="{{ $acceptTypes ?? '' }}">
                                        </span>
                                        <button type="submit" class="btn btn-primary start">
                                            <i class="glyphicon glyphicon-upload"></i>
                                            <span>{{ __('filemanager::filemanager.Upload_start') }}</span>
                                        </button>
                                        <span class="fileupload-process"></span>
                                    </div>
                                </div>
                                <div id="filesTable">
                                    <table role="presentation" class="table table-striped table-condensed small"><tbody class="files"></tbody></table>
                                </div>
                                <div class="upload-help">{{ __('filemanager::filemanager.Upload_base_help') }}</div>
                            </div>
                        </form>

                        <script id="template-upload" type="text/x-tmpl">
                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                            <tr class="template-upload">
                                <td><span class="preview"></span></td>
                                <td>
                                    <p class="name">{%=file.relativePath%}{%=file.name%}</p>
                                    <strong class="error text-danger"></strong>
                                </td>
                                <td>
                                    <p class="size">Processing...</p>
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar bar-success" style="width:0%;"></div></div>
                                </td>
                                <td>
                                    {% if (!i && !o.options.autoUpload) { %}
                                        <button class="btn btn-primary start" disabled style="display:none">
                                            <i class="glyphicon glyphicon-upload"></i>
                                            <span>Start</span>
                                        </button>
                                    {% } %}
                                    {% if (!i) { %}
                                        <button class="btn btn-link cancel">
                                            <i class="icon-remove"></i>
                                        </button>
                                    {% } %}
                                </td>
                            </tr>
                        {% } %}
                        </script>

                        <script id="template-download" type="text/x-tmpl">
                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                            <tr class="template-download">
                                <td>
                                    <span class="preview">
                                        {% if (file.error) { %}
                                        <i class="icon icon-remove"></i>
                                        {% } else { %}
                                        <i class="icon icon-ok"></i>
                                        {% } %}
                                    </span>
                                </td>
                                <td>
                                    <p class="name">
                                        {% if (file.url) { %}
                                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                        {% } else { %}
                                            <span>{%=file.name%}</span>
                                        {% } %}
                                    </p>
                                    {% if (file.error) { %}
                                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                                    {% } %}
                                </td>
                                <td><span class="size">{%=o.formatFileSize(file.size)%}</span></td>
                                <td></td>
                            </tr>
                        {% } %}
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
