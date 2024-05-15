<script>
    var t = $("#inventaris").DataTable({
        responsive: true,
        processing: true,
        autoWidth: false,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"]
        ],
        pageLength: 10,
        order: [
            [1, 'asc']
        ],
        language: {
            url: "<?= asset('bootstrap/js/dataTables.indonesian.lang') ?>",
        },
    });
    t.on("order.dt search.dt", function() {
    t.column(0, {
            search: "applied",
            order: "applied"
        })
        .nodes()
        .each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
</script>