
<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-submit">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="kategori">Kategori Produk</label>
            <input id="nama_produk" class="form-control input-sm required nama_produk" type="text" name="kategori" placeholder="Kategori Produk" value="{{ $main->kategori }}" />
        </div>
        <div class="form-group">
            <label class="control-label" for="kategori">Slug</label>
            <input id="slug_produk" class="form-control input-sm required slug_produk" type="text" name="slug" placeholder="Slug Produk" value="{{ $main->slug }}" />
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social  btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social  btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
<script>
$(document).ready(function () {
    $(".nama_produk").on("keyup change", function () {
        let text = $(this).val();

        // Convert ke slug
        let slug = text
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')   // buang karakter aneh
            .replace(/\s+/g, '-')           // ganti spasi dengan -
            .replace(/-+/g, '-');           // hapus double -

        $(".slug_produk").val(slug);
    });
});
</script>
