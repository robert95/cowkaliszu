function sortById(){
	var elems = $('.event').sort(function (a, b) {
		var contentA = parseInt( $(a).attr('data-id'));
		var contentB = parseInt( $(b).attr('data-id'));
		return (contentA > contentB) ? -1 : (contentA < contentB) ? 1 : 0;
	});
	$('.event-list-direct-cont').html(elems);
} 
function sortByName(){
	var elems = $('.event').sort(function (a, b) {
		var contentA = $(a).attr('data-title');
		var contentB = $(b).attr('data-title');
		return contentA.localeCompare(contentB);
	});
	$('.event-list-direct-cont').html(elems);
}
function sortByRating(){
	var elems = $('.event').sort(function (a, b) {
		var contentA = parseFloat( $(a).attr('data-rating').replace(/,/g, '.'));
		var contentB = parseFloat( $(b).attr('data-rating').replace(/,/g, '.'));
		return (contentA < contentB) ? 1 : (contentA > contentB) ? -1 : 0;
	});
	$('.event-list-direct-cont').html(elems);
}

function hideLoadingPanel(){
	$('.full-loading-panel').hide();
}

$("input[name='sorted']").on('change', function(){
	$('.full-loading-panel').show();
	var val = $("input[name='sorted']:checked").val();
	if(val == 0){
		sortById();
	}else if(val == 1){
		sortByRating();
	}else if(val == 2){
		sortByName();
	}
	setTimeout(hideLoadingPanel, 500);
});