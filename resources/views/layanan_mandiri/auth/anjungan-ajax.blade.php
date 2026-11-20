
@push('script')
    <script type="text/javascript">
        $('document').ready(function() {
            // Get UUID from local storage
            const anjunganUuid = localStorage.getItem('anjungan_uuid');
            if (anjunganUuid) {
                // Set it to the hidden input (existing logic)
                $('#anjungan_uuid').val(anjunganUuid);

                // --- NEW AJAX LOGIC ---
                // Send UUID to server to validate and create session, then show button
                const url = '{{ site_url("layanan-mandiri/cek-anjungan-ajax") }}';

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        'anjungan_uuid': anjunganUuid,
                    },
                    success: function(data) {
                        if (data.is_anjungan) {
                            $('#anjungan-button-container').html(`
                                <div class="form-group">
                                    <a href="{{ route('anjungan.index') }}">
                                        <button type="button" class="btn btn-block bg-green"><b>ANJUNGAN</b></button>
                                    </a>
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error setting anjungan session:', status, error);
                    }
                });
            }
        });
    </script>
@endpush
