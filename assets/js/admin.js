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