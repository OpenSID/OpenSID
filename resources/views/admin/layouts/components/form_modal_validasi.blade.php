<script>
    $(document).ready(function() {
        // Select2 dengan fitur pencarian
        $('.select2').select2({
            width: '100%',
            dropdownAutoWidth: true
        });

        $('.modal:visible').find('form').validate()
    })
</script>
