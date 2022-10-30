tinymce.init({
	selector: "textarea",
	plugins: "code",
	toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code",
	//toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code image_upload",
	menubar:false,
    statusbar: false,
	content_style: ".mce-content-body {font-size:15px;font-family:Arial,sans-serif;}",
	height: 200,
	setup: function(ed) {
		
		var fileInput = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
		$(ed.getElement()).parent().append(fileInput);
		
		fileInput.on("change",function(){			
			var file = this.files[0];
			var reader = new FileReader();			
			var formData = new FormData();
			var files = file;
			formData.append("file",files);
			formData.append('filetype', 'image');				
			jQuery.ajax({
				url: "upload.php",
				type: "post",
				data: formData,
				contentType: false,
				processData: false,
				async: false,
				success: function(response){
					var fileName = response;
					if(fileName) {
						ed.insertContent('<img src="upload/'+fileName+'"/>');
					}
				}
			});		
			reader.readAsDataURL(file);	 
		});		
		
		ed.addButton('image_upload', {
			tooltip: 'Upload Image',
			icon: 'image',
			onclick: function () {
				fileInput.trigger('click');
			}
		});
	}
});
