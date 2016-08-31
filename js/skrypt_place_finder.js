var $rows = $('.set_place');
$('#search').keyup(function() {
	    showAllPlace();
	    var val = '^' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b'),
	        reg = RegExp(val, 'i'),
	        text;
	    
	    $rows.show().filter(function() {
	        text = $(this).text().replace(/\s+/g, ' ');
	        return !reg.test(text);
	    }).hide();
		
		if($(this).val()){
			hideFirstLetterOfNullPlace();
			cleanPlaceList()
		}
	});
	
function hideFirstLetterOfNullPlace(){
	$("#place_list .letter").each(function(index){
		var empty = true;
		$(this).find( "a" ).each(function( i ) {
			if($(this).css('display') !== 'none') {
				empty = false;
			}
		});
		if(empty) $(this).hide();
	});
}

function showAllPlace(){
	$("#place_list .letter").show();
	$("#place_list .letterTR").show();
}

function cleanPlaceList(){
	$("#place_list .letterTR").each(function(index){
		var empty = true;
		$(this).find( ".letter" ).each(function( i ) {
			if($(this).css('display') !== 'none') {
				empty = false;
			}
		});
		if(empty) $(this).hide();
	});
}