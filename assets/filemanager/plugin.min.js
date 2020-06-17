/**
 * plugin.js
 *
 * Copyright, Alberto Peripolli
 * Released under Creative Commons Attribution-NonCommercial 3.0 Unported License.
 *
 * Contributing: https://github.com/trippo/ResponsiveFilemanager
 */

tinymce.PluginManager.add('filemanager', function(editor) {

	editor.settings.file_picker_types = 'file image media';
	editor.settings.file_picker_callback = filemanager;

	function filemanager_onMessage(event){
		if(editor.settings.external_filemanager_path.toLowerCase().indexOf(event.origin.toLowerCase()) === 0){
			if(event.data.sender === 'responsivefilemanager'){
				tinymce.activeEditor.windowManager.getParams().setUrl(event.data.url);
				tinymce.activeEditor.windowManager.close();

				// Remove event listener for a message from ResponsiveFilemanager
				if(window.removeEventListener){
					window.removeEventListener('message', filemanager_onMessage, false);
				} else {
					window.detachEvent('onmessage', filemanager_onMessage);
				}
			}
		}
	}

	function filemanager(callback, value, meta) {
		var width = window.innerWidth-30;
		var height = window.innerHeight-60;
		if(width > 1800) width=1800;
		if(height > 1200) height=1200;
		if(width>600){
			var width_reduce = (width - 20) % 138;
			width = width - width_reduce + 10;
		}

		// DEFAULT AS FILE
		urltype=2;
		if (meta.filetype === 'image' || meta.mediaType === 'image') { urltype=1; }
		if (meta.filetype === 'media' || meta.mediaType === 'media') { urltype=3; }

		var title="RESPONSIVE FileManager";
		if (typeof editor.settings.filemanager_title !== "undefined" && editor.settings.filemanager_title) {
			title=editor.settings.filemanager_title;
		}
		var akey="key";
		if (typeof editor.settings.filemanager_access_key !== "undefined" && editor.settings.filemanager_access_key) {
			akey=editor.settings.filemanager_access_key;
		}
		var sort_by="";
		if (typeof editor.settings.filemanager_sort_by !== "undefined" && editor.settings.filemanager_sort_by) {
			sort_by="&sort_by="+editor.settings.filemanager_sort_by;
		}
		var descending=0;
		if (typeof editor.settings.filemanager_descending !== "undefined" && editor.settings.filemanager_descending) {
			descending=editor.settings.filemanager_descending;
		}
		var fldr="";
		if (typeof editor.settings.filemanager_subfolder !== "undefined" && editor.settings.filemanager_subfolder) {
			fldr="&fldr="+editor.settings.filemanager_subfolder;
		}
		var crossdomain="";
		if (typeof editor.settings.filemanager_crossdomain !== "undefined" && editor.settings.filemanager_crossdomain) {
			crossdomain="&crossdomain=1";

			// Add handler for a message from ResponsiveFilemanager
			if(window.addEventListener){
				window.addEventListener('message', filemanager_onMessage, false);
			} else {
				window.attachEvent('onmessage', filemanager_onMessage);
			}
		}

		window.addEventListener('message', function receiveMessage(event) {
			window.removeEventListener('message', receiveMessage, false);
			if (event.data.sender === 'responsivefilemanager') {
				callback(event.data.url);
			}
		}, false);

		var dialogUrl = editor.settings.external_filemanager_path+'dialog.php?type='+urltype+'&descending='+descending+sort_by+fldr+crossdomain+'&lang='+editor.settings.language+'&akey='+akey;

		if (tinymce.majorVersion > 4) {
			tinymce.activeEditor.windowManager.openUrl({
				title: title,
				url: dialogUrl,
				width: width,
				height: height,
				resizable: true,
				maximizable: true,
				inline: 1,
			});
		} else {
			tinymce.activeEditor.windowManager.open({
				title: title,
				file: dialogUrl,
				width: width,
				height: height,
				resizable: true,
				maximizable: true,
				inline: 1,
			});
		}
	}

	return false;
});
