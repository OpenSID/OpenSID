$(document).ready(function() {
    checkAll();
    topMenu();
    toolTips();
    inputShadow();

    //$('#maincontent2').css({'height':35}).layout({});
    $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
    /* untuk datetimepicker */
    $('.datetimepicker-start').datetimepicker({
        controlType: 'select',
        timeFormat: "hh:mm tt",
        hourGrid: 4,
        minuteGrid: 10,
        dateFormat: 'dd-mm-yy',
        onClose: function(dateText, inst) {
            var _awal = $(this);
            var _akhir = _awal.parent().find('.datetimepicker-end');
            if (_akhir.val() != '') {
                var testStartDate = _awal.datetimepicker('getDate');
                var testEndDate = _akhir.datetimepicker('getDate');
                if (testStartDate > testEndDate)
                    _akhir.datetimepicker('setDate', testStartDate);
            } else {
                _akhir.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            var _p = $(this).parent();
            //	endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
            _p.find('.datetimepicker-end').datetimepicker('option', 'minDate', $(this).datetimepicker('getDate'));
        }
    });
    $('.datetimepicker-end').datetimepicker({
        controlType: 'select',
        timeFormat: "hh:mm tt",
        hourGrid: 4,
        minuteGrid: 10,
        dateFormat: 'dd-mm-yy',
        onClose: function(dateText, inst) {
            var _akhir = $(this);
            var _awal = _akhir.parent().find('.datetimepicker-start');
            if (_awal.val() != '') {
                var testStartDate = _awal.datetimepicker('getDate');
                var testEndDate = _akhir.datetimepicker('getDate');
                if (testEndDate < testStartDate)
                    _awal.datetimepicker('setDate', testEndDate);
            } else {
                _awal.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            var _p = $(this).parent();
            //	endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
            _p.find('.datetimepicker-start').datetimepicker('option', 'maxDate', $(this).datetimepicker('getDate'));
        }
    });
    /* set max date and min date based other datepicker */
    $('.datepicker-start').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selectedDate) {
            var _p = $(this).parent();
            _p.find('.datepicker-end').datepicker('option', 'minDate', selectedDate);
        }
    });
    $('.datepicker-end').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selectedDate) {
            var _p = $(this).parent();
            _p.find('.datepicker-start').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('input.help,textarea.help').formtips({
        tippedClass: 'tipped'
    });
    $('a#back-button').appendTo('#topmenu').css({ 'float': 'right' });
    $('#breadcrumb').appendTo('#pathway');
    $('a.cpanel.disabled').click(function() { return false });

    $('table.thread td a.toggler').click(function() {
        $('.textbox').slideUp();
        $(this).parent().next('.textbox').slideDown();
    });

    $('table.thread td a.comment-toggler').toggle(function() {
        $(this).children('span').html('Sembunyikan');
        $(this).parent().parent().find('.answer').show();
    }, function() {
        $(this).children('span').html('Tampilkan');
        $(this).parent().parent().find('.answer').hide();
    });

});

function checkAll() {
    $('.checkall').click(function() {
        $(this).parents('table').find(':checkbox').attr('checked', this.checked);
    });

    $('tbody.data tr')
        .filter(':has(:checkbox:checked)')
        .end()
        .click(function(event) {
            if (event.target.type !== 'checkbox') {
                $(':checkbox', this).attr('checked', function() {
                    return !this.checked;
                });
            }
        });

}

function undeleteBox(urlLoc) {
    $('#deletebox').remove();
    $('body').append('<div id="deletebox" title="Notifikasi" style="display:none;"><p>Apakah anda yakin membatalkan hapus data ini?</p>');
    $('#deletebox').dialog({
        resizable: false,
        draggable: false,
        width: 300,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK": function() {
                location.href = urlLoc;
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }

    });
    $('#deletebox').dialog('open');

}

function deleteBox(urlLoc) {
    $('#deletebox').remove();
    $('body').append('<div id="deletebox" title="Notifikasi" style="display:none;"><p>Apakah anda yakin mau menghapus data ini?</p>');
    $('#deletebox').dialog({
        resizable: false,
        draggable: false,
        width: 300,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK": function() {
                location.href = urlLoc;
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }

    });
    $('#deletebox').dialog('open');

}

function deleteAllBox(idForm, action) {
    $('#deletebox').remove();
    $('body').append('<div id="deletebox" title="Hapus Data" style="display:none;"><p>Apakah anda yakin mau menghapus data ini?</p></div>');
    $('#deletebox').dialog({
        resizable: false,
        draggable: false,
        width: 400,
        autoOpen: false,
        modal: true,
        buttons: {
            "Ya": function() {
                $('#' + idForm).attr('action', action);
                $('#' + idForm).submit();
            },
            "Tidak": function() {
                $(this).dialog("close");
            }
        }
    });
    $('#deletebox').dialog('open');
}

function aksiBorongan(idForm, action, title, pesan, target) {
    if (typeof(target)==='undefined') target = '';
    $('#konfirmasibox').remove();
    $('body').append('<div id="konfirmasibox" title="'+title+'" style="display:none;"><p>'+pesan+'</p></div>');
    $('#konfirmasibox').dialog({
        resizable: false,
        draggable: false,
        width: 400,
        autoOpen: false,
        modal: true,
        buttons: {
            "Ya": function() {
                $('#' + idForm).attr('action', action);
                $('#' + idForm).attr('target', target);
                $('#' + idForm).submit();
                $(this).dialog("close");
            },
            "Tidak": function() {
                $(this).dialog("close");
            }
        }
    });
    $('#konfirmasibox').dialog('open');
}

function verifikasi(idForm, action) {
    $('#deletebox').remove();
    $('body').append('<div id="deletebox" title="Verifikasi Data" style="display:none;"><p>Apakah anda yakin mau verifikasi data ini?</p></div>');
    $('#deletebox').dialog({
        resizable: false,
        draggable: false,
        width: 400,
        autoOpen: false,
        modal: true,
        buttons: {
            "Ya": function() {
                $('#' + idForm).attr('action', action);
                $('#' + idForm).submit();
            },
            "Tidak": function() {
                $(this).dialog("close");
            }
        }
    });
    $('#deletebox').dialog('open');
}

function logOut(urlLoc) {
    $('#logout-box').remove();
    $('body').append('<div id="logout-box" title="Log Out" style="display:none;"><p>Apakah anda yakin mau keluar dari sistem?</p></div>');
    $('#logout-box').dialog({
        resizable: true,
        draggable: true,
        width: 400,
        autoOpen: false,
        modal: false,
        buttons: {
            "Ya": function() {
                location.href = urlLoc;
            },
            "Tidak": function() {
                $(this).dialog("close");
            }
        }

    });
    $('#logout-box').dialog('open');

}

function noticeInfo() {
    $('#notice-info').remove();
    $('body').append('<div id="notice-info" title="Pengumuman" style="display:none;">' + $('#pengumuman').html() + '</div>');
    $('#notice-info').dialog({
        resizable: true,
        draggable: true,
        width: 600,
        autoOpen: false,
        modal: false,
        buttons: {
            "tutup": function() {
                $(this).dialog("close");
            }
        }

    });
    $('#notice-info').dialog('open');

}

function simpanPerubahan(urlLoc) {
    $('confirm-box').remove();
    $('body').append('<div id="confirm-box" title="Simpan Semua Perubahan" style="display:none;">' + $('#simpan-perubahan').html() + '</div>');
    $('#confirm-box').dialog({
        resizable: true,
        draggable: true,
        width: 400,
        autoOpen: false,
        modal: false,
        buttons: {
            "Ya": function() {
                location.href = urlLoc;
            },
            "Tidak": function() {
                $(this).dialog("close");
            }
        }

    });
    $('#confirm-box').dialog('open');

}

function simpanFixAll(idForm, action) {
    $('#deletebox').remove();
    $('body').append('<div id="deletebox" title="Notifikasi" style="display:none;"><p>Apakah anda yakin untuk menyimpan perubahan, Jika Ya maka data yang terhapus tidak akan bisa kembali lagi. Anda Yakin?</p></div>');
    $('#deletebox').dialog({
        resizable: false,
        draggable: false,
        width: 300,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK": function() {
                $('#' + idForm).attr('action', action);
                $('#' + idForm).submit();
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }

    });
    $('#deletebox').dialog('open');

}

function detailPengumuman($_judul, $_content) {
    $('#detail-pengumuman').remove();
    $('body').append('<div id="detail-pengumuman" title="' + $_judul + '" style="display:none;">' + $_content + '</div>');
    $('#detail-pengumuman').dialog({
        resizable: true,
        draggable: true,
        width: 400,
        autoOpen: false,
        modal: false,
        buttons: {
            "tutup": function() {
                $(this).dialog("close");
            }
        }

    });
    $('#detail-pengumuman').dialog('open');

}

function topMenu() {
    $('#topmenu ul li ul').parent().addClass('sub').children('a').append('<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span>');
    $('#topmenu ul li.sub').hover(function() {
        $(this).addClass('active');
        $(this).children('ul').show();
    }, function() {
        $(this).removeClass('active');
        $(this).children('ul').hide();
    });
}

function messageSuccess() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="success">Success!</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messageError() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="failed">Gagal!</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messageTambah() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="success">Berhasil Ditambah!</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messageHapus() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="success">Berhasil Dihapus!</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messageNoDelete() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="failed">Gagal, atau tidak bisa dihapus, sedang digunakan!</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messageDuplicate() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="failed">Duplikat record, periksa kembali</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messagenoaksesbelajalangsung() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="failed">Gagal, belum punya hak akses</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function messagepagunol() {
    $('#toppanel').append('<div id="messagebox"><div class="box" style="display:none"><span class="failed">Gagal, Pagu masih kosong</span></div></div>');
    $('#messagebox .box').slideDown(100, function() {
        $(this).delay(3000).slideUp(100);
    });
}

function formAction(idForm, action) {
    $('#' + idForm).attr('action', action);
    $('#' + idForm).submit();
}

function toolTips() {
    //Tooltips
    var tip;
    $(".tip_trigger").hover(function() {

        //Caching the tooltip and removing it from container; then appending it to the body
        tip = $(this).find('.tip').remove();
        $('body').append(tip);

        tip.show(); //Show tooltip

    }, function() {

        tip.hide().remove(); //Hide and remove tooltip appended to the body
        $(this).append(tip); //Return the tooltip to its original position

    }).mousemove(function(e) {
        //console.log(e.pageX)
        var mousex = e.pageX + 20; //Get X coodrinates
        var mousey = e.pageY + 20; //Get Y coordinates
        var tipWidth = tip.width(); //Find width of tooltip
        var tipHeight = tip.height(); //Find height of tooltip

        //Distance of element from the right edge of viewport
        var tipVisX = $(window).width() - (mousex + tipWidth);
        var tipVisY = $(window).height() - (mousey + tipHeight);

        if (tipVisX < 20) { //If tooltip exceeds the X coordinate of viewport
            mousex = e.pageX - tipWidth - 20;
            $(this).find('.tip').css({ top: mousey, left: mousex });
        }
        if (tipVisY < 20) { //If tooltip exceeds the Y coordinate of viewport
            mousey = e.pageY - tipHeight - 20;
            tip.css({ top: mousey, left: mousex });
        } else {
            tip.css({ top: mousey, left: mousex });
        }
    });

}

function addNewQuestion() {
    $('table.thread tr.newquestion').show();
}

function inputShadow() {
    $('table td.col1 .inputbox,table td.col2 .inputbox,table td.col3 .inputbox,table td.col4 .inputbox').attr("onfocus", "if(this.value=='0,00') this.value=''");
    $('table td.col1 .inputbox,table td.col2 .inputbox,table td.col3 .inputbox,table td.col4 .inputbox').attr("onblur", "if(this.value=='') this.value='0,00'");
}

// https://www.sitepoint.com/how-to-deal-with-cookies-in-javascript/

function createCookie(name, value, expires, path, domain) {
  var cookie = name + "=" + escape(value) + ";";

  if (expires) {
    // If it's a date
    if(expires instanceof Date) {
      // If it isn't a valid date
      if (isNaN(expires.getTime()))
       expires = new Date();
    }
    else
      expires = new Date(new Date().getTime() + parseInt(expires) * 1000 * 60 * 60 * 24);

    cookie += "expires=" + expires.toGMTString() + ";";
  }

  if (path)
    cookie += "path=" + path + ";";
  if (domain)
    cookie += "domain=" + domain + ";";

  document.cookie = cookie;
}
function getCookie(name) {
  var regexp = new RegExp("(?:^" + name + "|;\\s*"+ name + ")=(.*?)(?:;|$)", "g");
  var result = regexp.exec(document.cookie);
  return (result === null) ? null : result[1];
}

function deleteCookie(name, path, domain) {
  // If the cookie exists
  if (getCookie(name))
    createCookie(name, "", -1, path, domain);
}

function authInfoChangesNoticeUI(warning, paksa) {
    if (getCookie("skip_admin_warning") == '1' && !paksa) return;

    $(function() {
        var $dialogBox = $('<div>')
        $dialogBox.html(warning[1])
		$dialogBox.dialog({
                title: '<div>'+ warning[0] +'</div>',
                width: 400,
                buttons: {'Ok': ok, 'Lain kali': cancel}
          });

        function ok() {
            $(document).ajaxComplete(function() {
                $('input[type=password]').attr('required', true)
            })
            var btn = $('a[href$=user_setting]')[0];
            deleteCookie('skip_admin_warning');
            $(btn).trigger('click');
            $dialogBox.dialog("close");
        }

        function cancel() {
            deleteCookie('skip_admin_warning');
            createCookie('skip_admin_warning', '1', 1, '/');
            $dialogBox.dialog("close");
        }
    })
}

