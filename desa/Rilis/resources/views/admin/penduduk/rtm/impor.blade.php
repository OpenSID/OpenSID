@if (can('u'))
    <div class="modal fade" id="impor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Impor Pengelompokan Data Rumah Tangga</h4>
                </div>
                <form id="mainform" action="{{ ci_route('rtm.impor') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Pengelompokan data penduduk yang sudah tersimpan di dalam database SID, sehingga terkelompokkan secara otomatis berdasarkan nomor urut rumah tangga: </p>
                                <p>
                                <div class="row">
                                    <ol>
                                        <li value="1">Pastikan format data yang akan diimpor sudah sesuai dengan aturan impor data</li>
                                        <li>Simpan (Save) file spreadsheet sebagai file .xlsx</li>
                                        <li>Pastikan format Excel ber-ekstensi .xlsx (format Excel versi 2007 ke atas)</li>
                                    </ol>
                                </div>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file" class="control-label">File .xslx untuk diimpor : </label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path" name="userfile" required>
                                <input type="file" class="hidden" id="file" name="userfile" accept=".xls,.xlsx,.xlsm">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                            <input type="hidden" id="id_suplemen" name="id_suplemen">
                            <label>Data dengan NIK sama akan ditimpa</label>
                            <br />
                            <br />
                            <a href="{{ asset('import/FormatImporRTM.xlsx') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor Data Rumah Tangga</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! batal() !!}
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-check"></i> Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
