<div class="row">
    <div class="col-sm-12">
        {{-- <div class="box-header">
            @if (ci_auth()->id == super_admin())
                <a href="{{ ci_route('info_sistem.perbaiki_file_desa') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block " title="perbaiki file tidak valid"><i class="fa fa-check"></i> Perbaiki File Tidak Valid</a>
            @endif
        </div> --}}
        <div class="box-body">
            <div class="css-treeview">
                {!! create_tree_file($files, '') !!}
            </div>
        </div>
    </div>
</div>
