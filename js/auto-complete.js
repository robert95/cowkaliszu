var MIN_LENGTH = 1;

$( document ).ready(function() {
	$("#keyword").keyup(function() {
		var keyword = $("#keyword").val();
		if (keyword.length >= MIN_LENGTH) {

			$.get( "auto-complete.php", { keyword: keyword } )
			.done(function( data ) {
				$('#results').html('');
				var results = $.parseJSON(data);
				results = $.parseJSON(results);
				
				$(results.lista).each(function(key, value) {
					$('#results').append('<div class="item" data-id="' + value.id + '">' + value.name + '</div>');
				})

			    $('.item').click(function() {
			    	var id = $(this).data('id');
			    	$('#keyword').val(id);
					$( "[name='id_place']" ).val(id);
					$( "#next" ).click();
			    })

			});
		} else {
			$('#results').html('');
		}
	});

    $("#keyword").blur(function(){
    		$("#results").fadeOut(500);
    	})
        .focus(function() {		
    	    $("#results").show();
    	});

});