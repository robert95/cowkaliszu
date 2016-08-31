$( document ).ready(function() {
    fitThumbSize();
});

function fitThumbSize(){
	$(".thumb_event img").each(function(index, value) {	
		if(this.naturalWidth-10 > this.naturalHeight){
			$(this).height("100%");
			$(this).width("auto");
		}else{
			$(this).width("100%");
			$(this).height("auto");
		}
	});
}
