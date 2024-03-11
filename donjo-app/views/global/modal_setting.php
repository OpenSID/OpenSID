<?= form_open(ci_route('setting.update'), 'class="form-group" id="main_setting"') ?>
<div class="modal-body">
    <?php $this->load->view('setting/modal_form.php') ?>
</div>
<div class="modal-footer">
    <?= batal() ?>
    <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
</div>
</form>
<script>
    $(document).ready(function() {
        $("#main_setting").validate();
    })
</script>