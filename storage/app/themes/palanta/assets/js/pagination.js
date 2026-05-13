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
        var paginationListHTML = `<ul class="pagination mg-b-0 page-0">`;

        paginationListHTML += `<li class="page-item">
                                                    <a class="page-link btn-page" data-page="1">
                                                        <i class="fa fa-angle-left"></i>
                                                    </a>
                                                </li>`;

        if (currentPage > 1) {
        paginationListHTML += `<li class="page-item">
                                                        <a class="page-link btn-page" data-page="${currentPage - 1}">
                                                            <i class="fa fa-angle-double-left inline-block"></i>
                                                        </a>
                                                    </li>`;
        }

        for (var i = 1; i <= totalPages; i++) {
        paginationListHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                                        <a class="page-link btn-page" data-page="${i}">
                                                            ${i}
                                                        </a>
                                                    </li>`;
        }

        if (currentPage < totalPages) {
        paginationListHTML += `<li class="page-item">
                                                        <a class="page-link btn-page" data-page="${currentPage + 1}">
                                                            <i class="fa fa-angle-double-right inline-block"></i>
                                                        </a>
                                                    </li>`;
        }

        paginationListHTML += `<li class="page-item">
                                                    <a class="page-link btn-page" data-page="${totalPages}">
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                </li>`;

        paginationListHTML += `</ul>`;

        paginationList.html(paginationListHTML);
    }

    paginationInfo.html(paginationInfoHTML);
}
