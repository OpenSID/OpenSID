<div class="tab-pane active">
    <div class="row" id="list-paket">
        <form id="form-paket" action="{{ ci_route('plugin.hapus') }}" method="post">
            <input type="hidden" name="name" value="">
            @forelse ($paket_terpasang as $item)
                @include('admin.plugin.item', ['item' => $item, 'button' => '<button type="button" value="' . $item['name'] . '" class="btn btn-danger">Hapus</button>'])
            @empty
                <div class="col-md-12">
                    <div class="alert alert-warning">Belum ada paket yang terpasang</div>
                </div>
            @endforelse
        </form>
    </div>
</div>
@push('scripts')
    <script>
        $(function() {
            $('#form-paket button:button').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda sudah melakukan backup database dan folder desa ?',
                    showDenyButton: true,
                    confirmButtonText: 'Sudah',
                    denyButtonText: `Belum`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Memproses',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        $(e.currentTarget).closest('form').find('input[name=name]').val($(e.currentTarget).val())
                        $(e.currentTarget).closest('form').submit()
                    }
                })
            })
        })
    </script>
@endpush
