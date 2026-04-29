<style>
    #map {
        width: 100%;
        height: 85vh
    }

    .leaflet-popup-content {
        height: auto;
        overflow-y: auto;
    }

    /* Scroll only inside Statistik Penduduk & Bantuan cards when list is long */
    .leaflet-popup-content #collapseStatPenduduk .card.card-body,
    .leaflet-popup-content #collapseStatBantuan .card.card-body {
        max-height: 50vh;
        overflow-y: auto;
    }

    table {
        table-layout: fixed;
        white-space: normal !important;
    }

    td {
        word-wrap: break-word;
    }

    .persil {
        min-width: 350px;
    }

    .persil td {
        padding-right: 1rem;
    }
</style>
