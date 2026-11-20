
@push('script')
    <script type="text/javascript">
        $('document').ready(function() {
            // Get UUID from local storage
            const anjunganUuid = localStorage.getItem('anjungan_uuid');
            if (anjunganUuid) {
                // Set it to the hidden input (existing logic)
                $('#anjungan_uuid').val(anjunganUuid);

                // Set cookie as well to ensure persistence and compatibility with backend auto-restore
                const secureFlag = location.protocol === 'https:' ? '; Secure' : '';
                document.cookie = "anjungan_uuid=" + anjunganUuid + "; max-age=" + (365*24*60*60*5) + "; path=/; SameSite=Lax" + secureFlag;

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
