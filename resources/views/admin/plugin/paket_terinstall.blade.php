<div class="tab-pane active">
    <div class="row" id="list-paket">
        <form id="form-paket" action="{{ ci_route('plugin.hapus') }}" method="post">
            <input type="hidden" name="name" value="">
            @forelse ($paket_terpasang as $item)
                <div class="col-md-4 col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">{{ $item['name'] }}</div>
                        </div>
                        <div class="panel-body" style="min-height:200px">{{ $item['description'] }}</div>
                        <div class="panel-footer">
                            <button type="button" value="{{ $item['name'] }}" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
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
                        console.log($(e.currentTarget).val())
                        $(e.currentTarget).closest('form').find('input[name=name]').val($(e.currentTarget).val())
                        $(e.currentTarget).closest('form').submit()
                    }
                })
            })
        })
    </script>
@endpush
