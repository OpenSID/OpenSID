@push('css')
<!-- Jquery UI -->
  <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
<!-- Validasi -->
<script src="{{ asset('js/validasi.js') }}"></script>
<script src="{{ asset('js/localization/messages_id.js') }}"></script>
<script>
  function is_form_valid(form_id){
    form_id = form_id.startsWith('#') ? form_id : '#' + form_id;
    let validate = $(form_id).validate();
    if (validate.errorList.length > 0) {
        if(typeof Swal !== 'undefined'){
            Swal.fire({
                icon: 'error',
                html: validate.errorList[0].message,
            })
        }else{
            alert(validate.errorList[0].message);
        }
        validate.errorList[0].element.focus();
        return false;
    }
    return true;
  }
</script>
@endpush