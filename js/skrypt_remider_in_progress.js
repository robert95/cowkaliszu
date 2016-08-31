$("#tryLogin").click(function(){
	$("#infoLogin").show();
	var login = $("#login").val();
	var pass = $("#pass").val();
	$.post("wowlogowaniewowtakietajne.php", {submit: "1", login: login, pass: pass}, function(result){
       if(result == "0") $("#infoLogin").text("Błędne hasło lub login!");
	   else{
		   if(result == "-1" ) $("#infoLogin").text("Twoje konto nie jest jeszcze aktywowane, kliknij w link w mailu aktywacyjnym");
		   else {
				$("#infoLogin").html("Logowanie pomyślne");
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

$("#saveRemaind").click(function() {
	var id_event = $("#event_id").data('id');
	
	var login = false;
	var adresurl = "isLogin.php";
	$.ajax({url: adresurl, success: function(result){
		login = result; 
		
		if(login == "1"){
			$("#saveRemaind").html("");
			if($("#SMSRemaind").data('check')){
				var h = $("#rem1").attr('data-time');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","setRemaind.php?h=" + h + "&type=1&idevent=" + id_event,true);
				xmlhttp.send();
				setTimeout(function(){ $("#saveRemaind").append("-Przypomnienie SMS zostało ustalone na " + h + " godzin przed rozpoczęciem<br>"); }, 500);
			}
			if($("#MAILRemaind").data('check')){
				var h2 = $("#rem2").attr('data-time');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","setRemaind.php?h=" + h2 + "&type=0&idevent=" + id_event,true);
				xmlhttp.send();
				setTimeout(function(){ $("#saveRemaind").append("-Przypomnienie e-mail zostało ustalone na " + h2 + " godzin przed rozpoczęciem");}, 500);
			}
		}
		else {
			$("#login_panel_rem").show("slow");
			$("#remineder_panel").hide();
		}

    }});
});