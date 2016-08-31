$("#saveRemaind").click(function() {
	$("#saveRemaind").text("Funkcję uruchomimy wkrótce");
});

$("#tryLogin").click(function(){
	$("#infoLogin").show();
	var login = $("#login").val();
	var pass = $("#pass").val();
	$.post("wowlogowaniewowtakietajne.php", {submit: "1", login: login, pass: pass}, function(result){
       if(result == "0") $("#infoLogin").text("B&#322;&#281;dne has&#322;o lub login!");
	   else{
		   if(result == "-1" ) $("#infoLogin").text("Twoje konto nie jest jeszcze aktywowane, kliknij w link w mailu aktywacyjnym");
		   else {
				$("#infoLogin").html("Logowanie pomy&#347;lne");
				setTimeout(function(){
					$("#login_panel_rem").hide();
					$("#remineder_panel").show("slow");
				}, 500);
			
				
		   }
	   }
    });
});

$( "#rem1" ).draggable({
	axis: "y",
	stop: checkRemindSetterPosition,
});
$( "#rem2" ).draggable({
	axis: "y",
	stop: checkRemindSetterPosition,
});

function checkRemindSetterPosition(){
	if(parseInt($( "#rem2" ).css("top")) > 50){
		$( "#rem2" ).css({top: 50});
	}
	if(parseInt($( "#rem1" ).css("top")) > 50){
		$( "#rem1" ).css({top: 50});
	}
	if(parseInt($( "#rem2" ).css("top")) <-530){
		$( "#rem2" ).css({top: -530});
	}
	if(parseInt($( "#rem1" ).css("top")) <-530){
		$( "#rem1" ).css({top: -530});
	}
	setRemindTime();
}

function setRemindTime(){
	var top1 = parseInt($( "#rem1" ).css("top"));
	var h1 = -top1/50;
	h1 = Math.floor(h1);
	h1 += 2;
	$( "#rem1" ).attr("data-time", h1);
	
	var top2 = parseInt($( "#rem2" ).css("top"));
	var h2 = -top2/50;
	h2 = Math.floor(h2);
	h2 += 2;
	$( "#rem2" ).attr("data-time", h2);
}

$(".check_img").click(function() {
	if($(this).data('check') == 0){
		 $(this).attr("src", "img/checked.png");
		 $(this).data('check', 1);
	}
	else{
		 $(this).attr("src", "img/nochecked.png");
		 $(this).data('check', 0);
	}
});