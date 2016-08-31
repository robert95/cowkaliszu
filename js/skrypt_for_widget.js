function next(){
	var current = $("#currentPanel").text();
	hideAll();
	
	switch (current) {
    case "first_panel":
        $("#second_panel").show();
		$("#currentPanel").text("second_panel");
		$(".prev").css('display','inline-block');
		$(".next").css('display','inline-block');
		$("#add_place_panel").show();
		$(".add").hide();
		addMenuNav(2);
        break;
    case "second_panel":
        $("#third_panel").show();
		$("#currentPanel").text("third_panel");
		$(".prev").css('display','inline-block');
		$(".next").hide();
		$(".add").css('display','inline-block');
		addMenuNav(3);
        break;
	}
}

function prev(){
	var current = $("#currentPanel").text();	
	hideAll();
	
	switch (current) {
    case "second_panel":
        $("#first_panel").show();
		$("#currentPanel").text("first_panel");
		$(".prev").hide();
		$(".next").css('display','inline-block');
		$(".add").hide();
		addMenuNav(1);
        break;
    case "third_panel":
        $("#second_panel").show();
		$("#currentPanel").text("second_panel");
		$(".prev").css('display','inline-block');
		$(".next").css('display','inline-block');
		$(".add").hide();
		addMenuNav(2)
        break;
	}		
}

function hideAll(){
	$("#first_panel").hide();
	$("#second_panel").hide();
	$("#add_place_panel").hide();
	$("#third_panel").hide();
	$("#placeByLetter_panel").hide();
	$("#add_new_place_panel").hide();
}

$(".ABC_panel").click(function(){
	if($("#placeByLetter_panel").is(":visible") == true){
		$("#tabel").show();
		$("#second_panel").show();
		$("#placeByLetter_panel").hide();
		$(".prev").css('display','inline-block');
		$(".next").css('display','inline-block');
	}else{
		$("#tabel").hide();
		$("#second_panel").show();
		$("#placeByLetter_panel").show();
		$(".prev").hide();
		$(".next").hide();
	}
});

$("#placeByLetter_panel td").click(function(){
	$("[name=place]").val($(this).text());
	$(".ABC_panel").click();
	$('[name=place]').change();
});

$(".set_place").click(function(){
	$("[name=id_place]").val($(this).data('id'));
	$(".next").click();
});


$('[name=place]').keyup(function(){ 
	 $('[name=place]').change();
});

$('[name=place]').change(function() { 
		$(".noPlaceinfo").hide();
		
		var $rows = $('#tabel td');
		var $rowsTh = $('#tabel th');
		
        var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
            reg = RegExp(val, 'i'),
            text; 
 
        $rows.show().filter(function() { 
            text = $(this).text().replace(/\s+/g, ' ');
            return !reg.test(text); 
        }).hide();
		
		var a = $(this).val().substring(0, 1);
		val = '^(?=.*\\b' + $.trim(a).split(/\s+/).join('\\b)(?=.*\\b') + ').*$';
		reg = RegExp(val, 'i'),
		$rowsTh.show().filter(function() { 
            text = $(this).text().replace(/\s+/g, ' ');
            return !reg.test(text); 
        }).hide();
		
		isPropositionPlace();
 });
 
function isPropositionPlace(){
	var jest = false;
	
	$( "#tabel td" ).each(function( index ) {
		if($(this).is(":visible") == true) jest = true;
	});
	
	if(!jest){
		$( "#tabel th" ).hide();
		$(".noPlaceinfo").show();
	}
}


function removeRow(t){
	console.log(t);
	$(t).parent("tr").remove();
	loadTimetoHiddenInputs();
}
function addNewTime(){
	var ds = $("#data").val();
	var ds = new Date(ds);
	if(!checkDate()) {
		alert("Błędna data");
		return 
	}
	var a = "";
	if(ds.getMonth() < 10) a = "0"; 
	var b = "";
	if(ds.getDate() < 10) b = "0"; 
	ds = ds.getFullYear() + "-" + a + (ds.getMonth()+1) + "-" + b+ ds.getDate();
	$("#time").val($( "#labelH" ).text());
	$("#time_end").val($( "#labelM" ).text());
	var ts = $("#time").val();
	var te = $("#time_end").val();
	var newTime = "<tr><td>" + ds + "</td><td>" + ts + "</td><td>" + te + "</td><td class='deleteTime' onclick='removeRow(this);'>Usuń</td></tr>";
	$('#timeListTable tr:last').after(newTime);
	loadTimetoHiddenInputs();
}

function loadTimetoHiddenInputs(){
	$("#listOfTimes").html("");
	$( "#timeListTable td" ).each(function( index ) {
		var t = $(this).text();
		var x = "<input type='hidden' name='listTime[]' value='" + t + "' />";
		$("#listOfTimes").append(x);
	});
}

function checkDate(){
	var ds = $("#data").val();
	var testDate = new Date(ds);
	if ( isNaN( testDate.getTime() ) ) {  // d.valueOf() could also work
		return false;
	}
	return true;
}

function checkTime(){
	if($( "#timeListTable td" ).size() == 0){
		addNewTime();
	}
	return true;
}

function addMenuNav(i){
	$("#nav_pan1").attr("class", "");
	$("#nav_pan2").attr("class", "");
	$("#nav_pan3").attr("class", "");
	
	$("#nav_pan" + i).attr("class", "active");
}

$("#nav_pan1").click(function(){
	$("#currentPanel").text("second_panel");
	prev();
});
$("#nav_pan2").click(function(){
	$("#currentPanel").text("third_panel");
	prev();
});
$("#nav_pan3").click(function(){
	$("#currentPanel").text("second_panel");
	next();
});

function add_event(){
	checkTime();
	if(checkForm()) $("#form_add_event_panel").submit();
}
function checkForm(){
	var good = true;
	if($("#name").val() == ""){
		alert("Brak nazwy!");
		good = false;
	}
	if($("#desc").val() == ""){
		alert("Brak opisu!");
		good = false;
	}
	if( document.getElementById("imageTHUMB").files.length == 0 ){
		alert("Brak zdjęcia!");
		good = false;
	}
	if($("#id_place").val() == ""){
		alert("Brak miejsca");
		good = false;
	}
	if($("#data").val() == ""){
		alert("Brak daty!");
		good = false;
	}
	return good;
}

$("#show_liked_event").click(function(){
	$("#my_liked_panel").show();
	$("#add_event_panel_mini").hide();
	$("#panel_manu_1").attr("src", "img/panel_liked_1.png");
	$("#add_new_button_event").attr("src", "img/panel_liked_2.png");
	$("#panel_manu_3").attr("src", "img/panel_liked_3.png");
});

function showAddPanel(){
	$("#my_liked_panel").hide();
	$("#add_event_panel_mini").show();
	$("#panel_manu_1").attr("src", "img/panel_liked_1b.png");
	$("#add_new_button_event").attr("src", "img/panel_liked_2b.png");
	$("#panel_manu_3").attr("src", "img/panel_liked_3b.png");
}

$("#tryLogin").click(function(){
	$("#infoLogin").show();
	var login = $("#login").val();
	var pass = $("#pass").val();
	$.post("wowlogowaniewowtakietajne.php", {submit: "1", login: login, pass: pass}, function(result){
       if(result == "0") $("#infoLogin").text("Błędne hasło lub login!");
	   else{
		   if(result == "-1" ) $("#infoLogin").text("Twoje konto nie jest jeszcze aktywowane, kliknij w link w mailu aktywacyjnym");
		   else {
			   $("#infoLogin").text("Logowanie pomyślne");
			   setTimeout(function(){
					location.reload();
					window.parent.location.reload();
				}, 500);
		   }
	   }
    });
});

$(".delete_from_widget_liked").click(function(){
	var adresurl = "addToLiked.php?id=" + $(this).data('id');
	$.ajax({url: adresurl, success: function(result){
		window.parent.location.reload();
    }});	
});

$("#tryRegister").click(function(){
	$("#infoRegister").show();
	var email = $("#r_email").val();
	var login = $("#r_login").val();
	var phone = $("#r_phone").val();
	var pass = $("#r_pass").val();
	var re_pass = $("#r_re_pass").val();
	var cap = $("#r_cap_kod").val();
	$.post("wowTakaTajnaRejestracjaPrzezPanel.php", {submit: "1", email: email, login: login, phone: phone, pass: pass, re_pass: re_pass, cap: cap}, function(result){
      $("#infoRegister").show();
	  $("#infoRegister").html(result);
    });
});

$(".cowkaliszu-maybe_register").click(function(){
	$("#cowkaliszu_register").show();
	$("#cowkaliszu_login").hide();
});

$(".maybe_login").click(function(){
	$(".widget_panel_form_co").show("slow");
});
