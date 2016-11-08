$( document ).ready(function(e) {
	$("#imgWidth").val($(".mainevent_img_on_eventpage").width());
	startSortabaleField();
	if($("#main_picture").attr('src')){
		$("#main_picture").show();
		$("#add-main-imange-icon").hide();
	}
	$('#preview-add img').hide();
	$(".add-image-cat-place-container").click(function(){
		$("#image").click();
	});
	$("#uploadimage").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
			url: "addImage.php", 	  // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				var data = jQuery.parseJSON(data);
				var newImg = '<div class="col-sm-2 newsImg" data-id="' + data.id + '" data-src="' + data.src + '"><img src="img/delete.png" onclick="removeMe(this);" class="delete-img"><img src="' + data.thumb + '"></div>';
				$(".article-gallery").append(newImg);
				$('#addImg').modal('hide');
				$('#uploadimage')[0].reset();
				$("#preview-add").children("img").attr('src', "");
				updateImageIds();
			}
		});
	}));
	$("#addNewImg").click(function(){
		$("#uploadimage").submit();
	});
	$("#editImgBTN").click(function(){
		$("#editImage").submit();
	});
	updateImageIds();
});

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#main_picture').show();
			$("#add-main-imange-icon").hide();
			$('#main_picture').attr('src', e.target.result);
		}
		
		reader.readAsDataURL(input.files[0]);
	}
}

$("#image").change(function(){
	//readURL(this);
	resizeImg(this);
});

function updateImageIds(){
	$("#imageIds").html("");
	$( ".newsImg" ).each(function( index ) {
		var id = $(this).attr('data-id');
		if(id != -1) $("#imageIds").append('<input type="hidden" name="img[]" value="' + id + '">');
	});
}

function removeMe(o){
	$(o).parent().remove();
	updateImageIds();
}

function resizeImg(input){
	if (input.files && input.files[0]) {
		var img = document.createElement("img");
		var reader = new FileReader();
		reader.onload = function(e)
		{
			img.src = e.target.result;
			var canvas = document.createElement("canvas");
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0);

			setTimeout(function(){
				var MAX_WIDTH = 1501;
				var MAX_HEIGHT = 1501;
				var width = img.width;
				var height = img.height;
				
				//if (width > height) {
				  if (width > MAX_WIDTH) {
					height *= MAX_WIDTH / width;
					width = MAX_WIDTH;
				  }
				/*} else {
				  if (height > MAX_HEIGHT) {
					width *= MAX_HEIGHT / height;
					height = MAX_HEIGHT;
				  }
				}*/
				canvas.width = width;
				canvas.height = height;
				var ctx = canvas.getContext("2d");
				ctx.drawImage(img, 0, 0, width, height);

				var dataurl = canvas.toDataURL("image/jpeg");
				document.getElementById('main_picture').src = dataurl;    
				$('#img').val(dataurl); 
				$('#main_picture').show();
				$("#add-main-imange-icon").hide();
				$('#main_picture').attr('src', dataurl);  
				$("#X").val(0);
				$("#Y").val(0);
				$("#W").val(100);
			}, 500);
		}
		reader.readAsDataURL(input.files[0]);
	}	
}
var jcrop = null;
function startDoThumb(x, y, wa){
	if($("#main_picture").attr('src')){
		var w = parseInt($("#W").val());
		var x = parseInt($("#X").val());
		var y = parseInt($("#Y").val());
		$('#main_picture').Jcrop({
		  onChange: updateThumb,
		  onSelect: updateThumb,
		  aspectRatio: 1,
		  minSize: [ 20, 20 ],
		  setSelect:   [ x, y, x+w, y+w ],
		},function(){
		console.log(w);
			jcrop = this;
		});
		
		$("#stop_main_thumb").show();
		$(".add-image-cat-place-container").hide();
		$("#add_main_thumb").hide();
		//orginal size of image:
		var img = document.getElementById("main_picture");
		$("#orginalW").val(img.width);
		$("#orginalH").val(img.height);
		return jcrop;
	}	
}

function destroyJcrop(){
	$("#stop_main_thumb").hide();
	$(".add-image-cat-place-container").show();
	$("#add_main_thumb").show();
	$("#main_picture").removeAttr('style');
	$("#main_picture").attr('style', '');
	$("#main_picture").show();
	jcrop.destroy();
}

function updateThumb(c){
	$("#changeThumb").val(1);
	$("#X").val(c.x);
	$("#Y").val(c.y);
	$("#W").val(c.w);
	if($("#W").val() == 0){
		$("#X").val(0);
		$("#Y").val(0);
		$("#W").val(100);
	}
};