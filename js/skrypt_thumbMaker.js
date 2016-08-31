function miniatura(x, y, w){
	var jcrop = null;
	$('#preview').Jcrop({
      onChange: updateThumb,
      onSelect: updateThumb,
      aspectRatio: 1,
	  minSize: [ 20, 20 ],
	  setSelect:   [ x, y, x+w, y+w ],
    },function(){
		jcrop = this;
	});
	
	//orginal size of image:
	var img = document.getElementById("preview");
	$("#orginalW").val(img.naturalWidth);
	$("#orginalH").val(img.naturalHeight);
	return jcrop;
};

function updateThumb(c){
	$("#X").val(c.x);
	$("#Y").val(c.y);
	$("#W").val(c.w);
	if($("#W").val() == 0){
		$("#X").val(0);
		$("#Y").val(0);
		$("#W").val(20);
	}
};

