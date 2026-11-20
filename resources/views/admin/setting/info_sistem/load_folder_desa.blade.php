{{-- Load Folder Desa Content --}}
<div class="row">
    <div class="col-sm-12">
        <div class="box-header">
            <div>
                @if ($check_permission)
                    @if (can('u'))
                        <a href="#" onclick="updatePermission(this)" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block " title="Set hak akses folder"><i class="fa fa-check"></i> Perbaiki hak akses folder</a>
                    @endif
                @else
                    <div class="alert alert-info alert-dismissible">
                        <p>OS menggunakan Windows tidak membutuhkan cek permission</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="css-treeview">
                @php
                    $folders = directory_map(DESAPATH);
                    echo create_tree_folder($folders, DESAPATH);
                @endphp
            </div>
        </div>
    </div>
</div>
