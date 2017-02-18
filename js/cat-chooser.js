$(document).ready(function(){
	var selected_cat = $(".categories-chooser li.activ-option").text();
	if(selected_cat != "") $('.select-cat-label').text($(".categories-chooser li.activ-option").text());
	$('.select-cat-label').click(function(){
		$(".categories-chooser").show();
		$(".mainevent-info-box").hide();
	});
	
	$(".categories-chooser li").click(function(){
		$('.select-cat-label').text($(this).text());
		$(".categories-chooser li").removeClass('activ-option');
		var id = $(this).attr('data-id');
		$(this).addClass('activ-option');
		$('#id_kat').val(id);
		$(".categories-chooser").hide();
		$(".mainevent-info-box").show();
	});
});