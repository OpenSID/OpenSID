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
        var paginationListHTML = `<ul class="pagination flex gap-2 flex-wrap">`;

        paginationListHTML += `<li class="page-item">
                                                    <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="1">
                                                        <i class="fas fa-arrow-left"></i>
                                                    </button>
                                                </li>`;

        if (currentPage > 1) {
        paginationListHTML += `<li class="page-item">
                                                        <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="${currentPage - 1}">
                                                            <i class="fas fa-chevron-left inline-block"></i>
                                                        </button>
                                                    </li>`;
        }

        for (var i = 1; i <= totalPages; i++) {
        paginationListHTML += `<li class="page-item">
                                                        <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-${i === currentPage ? "primary-100 text-white" : "white hover:text-primary-200"} btn-page" data-page="${i}">
                                                            ${i}
                                                        </button>
                                                    </li>`;
        }

        if (currentPage < totalPages) {
        paginationListHTML += `<li class="page-item">
                                                        <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="${currentPage + 1}">
                                                            <i class="fas fa-chevron-right inline-block"></i>
                                                        </button>
                                                    </li>`;
        }

        paginationListHTML += `<li class="page-item">
                                                    <button class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 btn-page" data-page="${totalPages}">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                </li>`;

        paginationListHTML += `</ul>`;

        paginationList.html(paginationListHTML);
    }

    paginationInfo.html(paginationInfoHTML);
}
