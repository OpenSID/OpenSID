 // Notifikasi
window.setTimeout(function() {
  $("#notifikasi").fadeTo(500, 0).slideUp(500, function(){
    $(this).remove();
  });
}, 1000);

// Sidebar
if (typeof (Storage) !== 'undefined' && localStorage.getItem('sidebar') === 'false') {
  $("#sidebar_collapse").addClass('sidebar-collapse');
}

$('.sidebar-toggle').on('click', function() {
  localStorage.setItem('sidebar', $("#sidebar_collapse").hasClass('sidebar-collapse'));
});

// Select2 default
$('.select2').select2();

// Select2 dengan fitur pencarian dan boleh isi sendiri
$('.select2-tags').select2({
  tags: true
});

//CheckBox All Selected
checkAllHeader("id_cb[]");
checkAllBody("#checkall", "#tabeldata", "id_cb[]");

function checkAllHeader(name = "id_cb[]") {
  $('table').on('click', "input[name='" + name + "']", function() {
    enableHapusTerpilih(name);
  });
  enableHapusTerpilih(name);
}

function checkAllBody(id = "#checkall", tabel = "#tabeldata", name = "id_cb[]") {
  $('table').on('click', id, function() {
    if ($(this).is(':checked')) {
      $(tabel + " input[type=checkbox]").each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(tabel + " input[type=checkbox]").each(function () {
        $(this).prop("checked", false);
      });
    }
    $(tabel + " input[type=checkbox]").change();
    enableHapusTerpilih(name);
  });
  $("[data-toggle=tooltip]").tooltip();
}

function enableHapusTerpilih(name = "id_cb[]") {
	if ($("input[name='" + name + "']:checked:not(:disabled)").length <= 0) {
		$(".aksi-terpilih").addClass('disabled');
		$(".hapus-terpilih").addClass('disabled');
		$(".hapus-terpilih").attr('href','#');
	} else {
		$(".aksi-terpilih").removeClass('disabled');
		$(".hapus-terpilih").removeClass('disabled');
		$(".hapus-terpilih").attr('href','#confirm-delete');
	}
}
