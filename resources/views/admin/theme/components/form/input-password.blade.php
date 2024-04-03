<div class="input-group">
    <input
        type="password"
        class="form-control input-sm"
        id="input{{ $value['key'] }}"
        name="opsi[{{ $value['key'] }}]"
        placeholder="{{ $value['placeholder'] }}"
        value=""
        {{ $value['readonly'] }}
        {!! $value['attributes'] !!}
        data-password="{{ $value['default'] ? 1 : 0 }}"
    >
    <span class="input-group-addon input-sm show-hide-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility('input{{ $value['key'] }}')"></i></span>
</div>
@if ($value['default'])
    <p class="help-block small text-red">{!! $value['caption'] !!}</p>
@endif
@push('scripts')
    <script>
        function togglePasswordVisibility(inputId) {
            var input = document.getElementById(inputId);
            var icon = input.nextElementSibling.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
@endpush
