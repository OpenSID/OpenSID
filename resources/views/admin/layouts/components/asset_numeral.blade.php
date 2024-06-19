<script src="{{ asset('js/numeral.min.js') }}"></script>

<script>
    numeral.register("locale", "id-id", {
        delimiters: {
            thousands: ".",
            decimal: ","
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        currency: {
            symbol: "Rp." //The currency for UAE is called the Dirham
        }
    });
    numeral.locale('id-id');
    numeral.defaultFormat('0,0.00');
    console.log(numeral.locale())

    // pengaturan peta
    var pengaturan_peta = {
        maxZoom: 30,
        minZoom: 1,
        fullscreenControl: {
            position: 'topright' // Menentukan posisi tombol fullscreen
        }
    };
</script>
