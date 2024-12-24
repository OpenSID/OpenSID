@include('admin.layouts.components.asset_validasi')
<style>
    .input-hidden[type=radio]:checked+label {
        border: 1px solid #fff;
        box-shadow: 0 0 3px 3px #090;
    }

    .bs-glyphicons {
        padding-left: 0;
        padding-bottom: 1px;
        margin-bottom: 20px;
        list-style: none;
        overflow: hidden;
    }

    .bs-glyphicons li {
        float: left;
        width: 25%;
        height: 115px;
        padding: 10px;
        margin: 0 -1px -1px 0;
        font-size: 12px;
        line-height: 1.4;
        text-align: center;
        border: 1px solid #ddd;
    }

    .bs-glyphicons .glyphicon {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 24px;
    }

    .bs-glyphicons .glyphicon-class {
        display: block;
        text-align: center;
        word-wrap: break-word;
        /* Help out IE10+ with class names */
    }

    .bs-glyphicons li:hover {
        background-color: #605ca8;
        color: #fff;
    }

    .bs-glyphicons li.active {
        background-color: #605ca8;
        color: #fff;
    }

    @media (min-width: 768px) {
        .bs-glyphicons li {
            width: 12.5%;
        }
    }

    .vertical-scrollbar {
        overflow-x: hidden;
        /*for hiding horizontal scroll bar*/
        overflow-y: auto;
        /*for vertical scroll bar*/
    }
</style>

<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="form-group">
            <label class="control-label" for="nama">Nama Kategori Lokasi</label>
            <input
                id="nama"
                name="nama"
                class="form-control input-sm nomor_sk required"
                maxlength="100"
                type="text"
                placeholder="Nama Kategori Lokasi"
                value="{{ $point['nama'] }}"
            />
        </div>
        <div class="form-group">
            <label for="nomor" class="control-label">Simbol</label>
            @if ($point['simbol'])
                <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) . $point['simbol'] }}" />
            @else
                <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) }}default.png" />
            @endif
        </div>
        <div class="form-group">
            <label for="id_master" class="control-label">Ganti Simbol</label>
            <div class="vertical-scrollbar" style="max-height:200px;">
                <div class="bs-glyphicons">
                    <ul class="bs-glyphicons">
                        @foreach ($simbol as $data)
                            <li @active($point['simbol'] == $data['simbol']) onclick="li_active($(this).val());">
                                <label>
                                    <input type="radio" name="simbol" id="simbol" class="input-hidden hidden" value="{{ $data['simbol'] }}" @checked($point['simbol'] == $data['simbol'])>
                                    <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) . $data['simbol'] }}">
                                    <span class="glyphicon-class">
                                        {{ $data['simbol'] }}
                                    </span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
            Simpan</button>
    </div>
</form>

<script>
    function li_active() {
        $('li').click(function() {
            $('li.active').removeClass('active');
            $(this).addClass('active');
            $(this).children("input[type=radio]").click();
        });
    };
</script>
