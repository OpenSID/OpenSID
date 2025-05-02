// Notifikasi Editor Tinymce
function notifEditor(text, type = "info", timeout = 2000) {
  tinymce.activeEditor.notificationManager.open({
    text: text,
    type: type,
    closeButton: true,
    timeout: timeout,
  });
}

// Set Content Editor dan Menampilkan Notifikasi
function setContent(editor, value, notif) {
  if (value) {
    return editor.setContent(value);
  } else {
    notifEditor(notif);
  }
}
