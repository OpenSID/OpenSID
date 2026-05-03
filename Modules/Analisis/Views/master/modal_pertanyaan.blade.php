<div class="modal fade" id="modalPertanyaan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal Pertanyaan</h4>
            </div>

            {{-- Modal Form --}}
            <form id="form-pertanyaan" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label">
                                                    Pilih pertanyaan yang akan disimpan pada tabel berikut
                                                </label>

                                                {{-- Questions Table --}}
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped dataTable table-hover">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" 
                                                                           id="select-all-question" 
                                                                           checked 
                                                                           data-waschecked="true" />
                                                                </th>
                                                                <th>NIK/KK</th>
                                                                <th>Pertanyaan</th>
                                                                <th>Tipe Pertanyaan</th>
                                                                <th>Kategori/Variabel</th>
                                                                <th>Bobot</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($data_import['pertanyaan'] as $key => $data)
                                                                <tr class="row-pertanyaan">
                                                                    {{-- Hidden Inputs --}}
                                                                    <input type="hidden" class="input-id" value="{{ $key }}">
                                                                    <input type="hidden" class="input-item-id" value="{{ $data['itemId'] }}">

                                                                    {{-- Checkbox Column --}}
                                                                    <td>
                                                                        <input type="checkbox" 
                                                                               class="input-is-selected" 
                                                                               checked 
                                                                               data-waschecked="true">
                                                                    </td>

                                                                    {{-- NIK/KK Radio Column --}}
                                                                    <td class="padat">
                                                                        <input type="radio" class="input-is-nik-kk">
                                                                    </td>

                                                                    {{-- Question Title Column --}}
                                                                    <td class="input-pertanyaan">
                                                                        {{ $data['title'] }}
                                                                    </td>

                                                                    {{-- Question Type Column --}}
                                                                    <td>
                                                                        <select name="tipe_pertanyaan" class="form-control input-sm input-tipe">
                                                                            <option value="0">Tipe Pertanyaan</option>
                                                                            <option value="1" {{ $data['type'] == 'MULTIPLE_CHOICE' ? 'selected' : '' }}>
                                                                                Pilihan (Tunggal)
                                                                            </option>
                                                                            <option value="2">Pilihan (Ganda)</option>
                                                                            <option value="3">Isian Jumlah (Kuantitatif)</option>
                                                                            <option value="4" {{ $data['type'] != 'MULTIPLE_CHOICE' ? 'selected' : '' }}>
                                                                                Isian Teks (Kualitatif)
                                                                            </option>
                                                                        </select>
                                                                    </td>

                                                                    {{-- Category Column --}}
                                                                    <td>
                                                                        <input type="text" class="form-control input-sm input-kategori">
                                                                    </td>

                                                                    {{-- Weight Column --}}
                                                                    <td>
                                                                        <input type="number" 
                                                                               class="form-control input-sm input-bobot" 
                                                                               value="0">
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        Tidak ada data pertanyaan
                                                                    </td>
                                                                </tr>
                                                            @endforelse
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

                {{-- Modal Footer --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="btn-next-pertanyaan">
                        <i class="fa fa-arrow-right"></i> Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
