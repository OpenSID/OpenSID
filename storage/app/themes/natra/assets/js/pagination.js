function initPagination(data) {
    var paginationContainer = $("#pagination-container");
    var paginationInfo = $("#pagination-info");
    var paginationList = $("#pagination-list");

    paginationContainer.show();
    paginationInfo.empty();
    paginationList.empty();

    var totalPages = data.meta.pagination.total_pages;
    var currentPage = data.meta.pagination.current_page;

    if (totalPages > 1) {
        var paginationInfoHTML = `Halaman ${currentPage} dari ${totalPages}`;
        paginationInfo.html(paginationInfoHTML);

        var paginationListHTML = `<ul class="pagination">`;

        paginationListHTML += `<li>
                                    <a href="#" class="btn-page" data-page="1" title="Halaman Pertama" ${currentPage === 1 ? 'aria-disabled="true"' : ''}>
                                        <i class="fa fa-fast-backward"></i>&nbsp;
                                    </a>
                                </li>`;

        if (currentPage > 1) {
            paginationListHTML += `<li>
                                        <a href="#" class="btn-page" data-page="${currentPage - 1}" title="Halaman Sebelumnya">
                                            <i class="fa fa-chevron-left inline-block"></i>
                                        </a>
                                    </li>`;
        }

        var startPage = Math.max(currentPage - 2, 1);
        var endPage = Math.min(currentPage + 2, totalPages);

        for (var i = startPage; i <= endPage; i++) {
            paginationListHTML += `<li class="${i === currentPage ? 'active' : ''}">
                                        <a href="#" class="btn-page" data-page="${i}" title="Halaman ${i}">
                                            ${i}
                                        </a>
                                    </li>`;
        }

        if (currentPage < totalPages) {
            paginationListHTML += `<li>
                                        <a href="#" class="btn-page" data-page="${currentPage + 1}" title="Halaman Selanjutnya">
                                            <i class="fa fa-chevron-right inline-block"></i>
                                        </a>
                                    </li>`;
        }

        paginationListHTML += `<li>
                                    <a href="#" class="btn-page" data-page="${totalPages}" title="Halaman Terakhir" ${currentPage === totalPages ? 'aria-disabled="true"' : ''}>
                                        <i class="fa fa-fast-forward"></i>&nbsp;
                                    </a>
                                </li>`;

        paginationListHTML += `</ul>`;

        paginationList.html(paginationListHTML);
    } else {
        paginationInfo.empty();
    }
}
