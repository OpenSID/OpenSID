<style>
    /* Peserta Bantuan Styles */
    .box-info {
        border-top-color: #3c8dbc;
    }

    .box-header {
        padding: 15px;
    }

    .box-title {
        font-weight: 600;
        font-size: 16px;
    }

    .table-container {
        margin: 0 1rem;
        padding: 15px 0;
    }

    #peserta_program {
        width: 100% !important;
    }

    #peserta_program thead th {
        background-color: #f7f7f7;
        font-weight: 600;
        border-bottom: 2px solid #ddd;
    }

    #peserta_program tbody tr:hover {
        background-color: #f5f5f5;
    }

    .title_text {
        width: 100%;
    }

    .single_page_content ul li {
        padding-left: 0;
    }

    /* Responsive table adjustments */
    @media (max-width: 768px) {
        .table-container {
            margin: 0 0.5rem;
        }
        
        .table-responsive {
            font-size: 14px;
        }
        
        #peserta_program th,
        #peserta_program td {
            padding: 8px 4px;
        }
    }

    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 4px 8px;
        border-radius: 4px;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .pagination-sm .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

<section class="content" id="maincontent">
    <div class="row">
        <div class="col-md-12">
            <input id="stat" type="hidden" value="{{ $lap }}">

            <!-- Data Peserta Bantuan -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-users"></i> Daftar {{ $heading }}
                    </h3>
                </div>
                <div class="box-body">
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="peserta_program">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Program</th>
                                        <th width="35%">Nama Peserta</th>
                                        <th width="35%">Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $lap) }}?filter[tahun]={{ $selected_tahun ?? '' }}'
</script>
