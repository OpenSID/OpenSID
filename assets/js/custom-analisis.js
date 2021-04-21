function assignValue() 
{
    var isSettingApplicable = true;
    $('.row-pertanyaan').each(function(i, obj) 
    {
        var idObj = $(obj).find('.input-id').val();
        objRowJawaban = $("#row-jawaban-" + idObj);
        // Isi nilai checkbox is-selected
        objRowJawaban.find('.is-selected').val($(obj).find('.input-is-selected').prop("checked"));
        // Isi nilai radio button is-nik-kk
        objRowJawaban.find('.is-nik-kk').val($(obj).find('.input-is-nik-kk').prop("checked"));
        // Isi nilai input tipe
        objRowJawaban.find('.tipe').val($(obj).find('.input-tipe').val());
        // Isi nilai input kategori
        objRowJawaban.find('.kategori').val($(obj).find('.input-kategori').val());
        // Isi nilai input bobot
        objRowJawaban.find('.bobot').val($(obj).find('.input-bobot').val());

        // Tampilkan Form Pilihan Jawaban untuk pertanyaan dengan syarat-syarat berikut:
        // 1. Tipe Pertanyaan Jawaban Tunggal
        // 2. Pertanyaan dipilih untuk disimpan
        // 3. Pertanyaan bukan berupa NIK/ No.KK
        if($(obj).find('.input-tipe').val() == "1" && $(obj).find('.input-is-selected').prop("checked") && !($(obj).find('.input-is-nik-kk').prop("checked")))
        {
            objRowJawaban.show();
            isSettingApplicable = false;
        }
        else
            objRowJawaban.hide();
    });

    // Tampilkan/Sembunyikan Empty State
    if(isSettingApplicable)
        $('#caption-jawaban').show();
    else
        $('#caption-jawaban').hide();
}

function setAsNikKK(objRow, setEnable=true) 
{
    objRow.find('.input-bobot').val("0");
    if(setEnable)
    {
        objRow.find('.input-is-selected').prop("disabled", true);
        objRow.find('.input-is-selected').prop("title", "NIK/No. KK harus disimpan");
        objRow.find('.input-tipe').prop("disabled", true);
        objRow.find('.input-kategori').val("NIK/No. KK");
        objRow.find('.input-kategori').prop("disabled", true);
        objRow.find('.input-bobot').prop("disabled", true);
    }
    else
    {
        objRow.find('.input-is-selected').prop("disabled", false);
        objRow.find('.input-is-selected').prop("title", "");
        objRow.find('.input-tipe').prop("disabled", false);
        objRow.find('.input-kategori').val("");
        objRow.find('.input-kategori').prop("disabled", false);
        objRow.find('.input-bobot').prop("disabled", false);
    }
}

function setSelectedQuestion(objRow, setSelected=true) 
{
    objRow.find('.input-is-selected').prop("checked", setSelected);
    objRow.find('.input-is-selected').data('waschecked', setSelected);
    setAsNikKK(objRow, false);
    objRow.find('.input-kategori').val("");
    objRow.find('.input-bobot').val("0");
    
    if(setSelected)
    {
        objRow.find('.input-is-nik-kk').prop("disabled", false);
        objRow.find('.input-tipe').prop("disabled", false);
        objRow.find('.input-kategori').prop("disabled", false);
        objRow.find('.input-bobot').prop("disabled", false);
    }
    else
    {
        objRow.find('.input-is-nik-kk').prop("disabled", true);
        objRow.find('.input-tipe').prop("disabled", true);
        objRow.find('.input-kategori').prop("disabled", true);
        objRow.find('.input-bobot').prop("disabled", true);
    }
}

function checkAllCheckbox() 
{
    countCheckedCheckbox = 0;
    $('.input-is-selected').each(function(i, obj) 
    {
        if($(obj).prop('checked'))
            countCheckedCheckbox += 1;
    });

    if(countCheckedCheckbox == $('.input-is-selected').length)
    {
        $('#select-all-question').prop('checkbox', true);
        $('#select-all-question').prop('indeterminate', false);
        $('#select-all-question').data('waschecked', true);
    }
    else if(countCheckedCheckbox == 0)
    {
        $('#select-all-question').prop('checkbox', false);
        $('#select-all-question').prop('indeterminate', false);
        $('#select-all-question').data('waschecked', false);
    }
    else
    {
        $('#select-all-question').prop('indeterminate', true);
        $('#select-all-question').data('waschecked', false);
    }
}

function validasiModalPertanyaan() 
{   
    // Cek Required NIK/KK
    if($('#id-row-nik-kk').val() == "")
        return 'Kolom NIK/No. KK Belum Ditentukan';
    
    // Cek Isian Kategori
    var error = "";
    $('.row-pertanyaan').each(function(i, obj) 
    {
        if($(obj).find('.input-is-selected').prop("checked"))
        {
            var pertanyaan = $(obj).find('.input-pertanyaan').html();
            if($(obj).find('.input-kategori').val() == "" || $(obj).find('.input-kategori').val() == undefined)
                error = 'Kategori untuk Pertanyaan \"' + pertanyaan + '\" belum diisi';

            if($(obj).find('.input-tipe').val() == 0)
                error = 'Tipe Pertanyaan untuk Pertanyaan \"' + pertanyaan + '\" belum diisi';
        }
    });

    return error;     
}

function validasiModalJawaban() 
{
    // Cek Required Nama Form Analisis
    if($('#nama_form').val() == "")
        return 'Nama Form Analisis Belum Ditentukan';
    
    // Cek Required Tahun Pendataan
    if($('#tahun_pendataan').val() == "")
        return 'Tahun Pendataan Belum Ditentukan';
    
    // Cek Required Tipe Subjek Analisis
    if($('#subjek_analisis').val() == 0)
        return 'Subjek Analisis Belum Ditentukan';
    
    return "";
}

$(document).ready(function()
{
    var isDataPertanyaanExist = false;
    if($('#mode-form').val() == 5)
        $('#modalPertanyaan').modal('show');
    else if($('#jml_error').val() != "0")
        $('#modalHasilImport').modal('show');
    
    $('#btn-next-pertanyaan').click(function() 
    {
        if(validasiModalPertanyaan() != "")
            toastr.error(validasiModalPertanyaan());
        else
        {
            assignValue();
            $('#modalPertanyaan').modal('hide');
            isDataPertanyaanExist = true;
        }
    });

    $('#modalPertanyaan').on('hidden.bs.modal', function () 
    {
        if(isDataPertanyaanExist)
        {
            $('#modalJawaban').modal('show');
            isDataPertanyaanExist = false;
        }
    });

    $('#btn-prev-jawaban').click(function() 
    {
        $('#modalJawaban').modal('hide');
        isDataPertanyaanExist = true;
    });

    $('#modalJawaban').on('hidden.bs.modal', function () 
    {
        if(isDataPertanyaanExist)
        {
            $('#modalPertanyaan').modal('show');
            isDataPertanyaanExist = false;
        }
    });

    $('#btn-next-jawaban').click(function() 
    {
        if(validasiModalJawaban() != "")
            toastr.error(validasiModalJawaban());
        else
        {
            $('#form-jawaban').submit();
        }
    });

    $('.input-is-nik-kk').click(function() 
    {
        if($(this).data('waschecked') == true)
        {
            $(this).prop("checked", false);
            $(this).data('waschecked', false);
            $('#id-row-nik-kk').val("");
            $('#gform-id-nik-kk').val("");

            setAsNikKK($(this).closest('.row-pertanyaan'), false);
        }
        else
        {
            $('.input-is-nik-kk').each(function(i, obj) 
            {
                $(obj).prop("checked", false);
                $(obj).data('waschecked', false);
                setAsNikKK($(this).closest('.row-pertanyaan'), false);
            });
            
            $(this).prop("checked", true);
            $(this).data('waschecked', true);
            $('#id-row-nik-kk').val($(this).closest('.row-pertanyaan').find('.input-id').val());
            $('#gform-id-nik-kk').val($(this).closest('.row-pertanyaan').find('.input-item-id').val());

            setAsNikKK($(this).closest('.row-pertanyaan'), true);
        }

        checkAllCheckbox();
    });

    $('.input-is-selected').click(function() 
    {
        if($(this).data('waschecked') == true)
            setSelectedQuestion($(this).closest('.row-pertanyaan'), false);
        else
            setSelectedQuestion($(this).closest('.row-pertanyaan'), true);

        checkAllCheckbox();
    });

    $('#select-all-question').click(function() 
    {
        var waschecked = $(this).data('waschecked');
        $('.row-pertanyaan').each(function(i, obj) 
        {
            var idObj = $(obj).find('.input-id').val();
            if(waschecked)
            {
                if(idObj != $('#id-row-nik-kk').val())
                    setSelectedQuestion($(obj), false);
            }
            else
            {
                if(idObj != $('#id-row-nik-kk').val())
                    setSelectedQuestion($(obj), true);
            }
        });
        if(waschecked)
        {
            $(this).data('waschecked', false);
            if($('#id-row-nik-kk').val() != "")
                $(this).prop('indeterminate', true);
        }
        else
        {
            $(this).prop('checked', true);
            $(this).data('waschecked', true);
            $(this).prop('indeterminate', false);
        }
    });
})