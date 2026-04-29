<div class="modal fade" id="modalHasilImport" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal Hasil Import</h4>
            </div>

            <form id="form-error" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-danger">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label">Terjadi beberapa error, antara lain</label>
                                                <input type="hidden" id="jml_error" value="{{ count($list_error) }}">
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped dataTable table-hover">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Error</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($list_error as $key => $data)
                                                                <tr class="row-pertanyaan">
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $data }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- Add footer content here if needed --}}
                </div>
            </form>
        </div>
    </div>
</div>