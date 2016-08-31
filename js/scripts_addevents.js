$("[name='kategoria']").change(function() {
	id = $(this).val();
	$("[name='id_kat']").attr('value', id);
});

function addHours(h){
	var i = parseInt($("#t_h1").text());
	var j = parseInt($("#t_h2").text());
	var x = i*10+j;
	console.log(x);
	x += h;
	console.log(x);
	x %= 24;
	if(i == 2 && h == 10){
		$("#t_h1").text(0);
		$("#t_h2").text(j);
	}else{
		if(i == 1 && h == 10 && j > 3){
			$("#t_h1").text(2);
			$("#t_h2").text(0);
		}
		else{
			$("#t_h1").text(Math.floor(x/10));
			$("#t_h2").text(x%10);
		}		
	}	
}
function subHours(h){
	var i = parseInt($("#t_h1").text());
	var j = parseInt($("#t_h2").text());
	var x = i*10+j;
	console.log(x);
	x -= h;
	if(x < 0) x = 23;
	console.log(x);
	x %= 24;
	$("#t_h1").text(Math.floor(x/10));
	$("#t_h2").text(x%10);
}
function addMinuts(m){
	var i = parseInt($("#t_m1").text());
	var j = parseInt($("#t_m2").text());
	var x = i*10+j;
	console.log(x);
	x += m;
	console.log(x);
	x %= 60;
	if(i == 5 && m == 10){
		$("#t_m1").text(0);
		$("#t_m2").text(j);
	}else{
		$("#t_m1").text(Math.floor(x/10));
		$("#t_m2").text(x%10);	
	}	
}
function subMinuts(m){
	var i = parseInt($("#t_m1").text());
	var j = parseInt($("#t_m2").text());
	var x = i*10+j;
	console.log(x);
	x -= m;
	if(x < 0) x = 59;
	console.log(x);
	x %= 60;
	$("#t_m1").text(Math.floor(x/10));
	$("#t_m2").text(x%10);
}
$("#t_uh1").click(function() {
	addHours(10);
});
$("#t_uh2").click(function() {
	addHours(1);
});
$("#t_um1").click(function() {
	addMinuts(10);
});
$("#t_um2").click(function() {
	addMinuts(1);
});

$("#t_dh1").click(function() {
	subHours(10);
});
$("#t_dh2").click(function() {
	subHours(1);
});
$("#t_dm1").click(function() {
	subMinuts(10);
});
$("#t_dm2").click(function() {
	subMinuts(1);
});

//end time
function addHoursE(h){
	var i = parseInt($("#t_h1e").text());
	var j = parseInt($("#t_h2e").text());
	var x = i*10+j;
	console.log(x);
	x += h;
	console.log(x);
	x %= 24;
	if(i == 2 && h == 10){
		$("#t_h1e").text(0);
		$("#t_h2e").text(j);
	}else{
		if(i == 1 && h == 10 && j > 3){
			$("#t_h1e").text(2);
			$("#t_h2e").text(0);
		}
		else{
			$("#t_h1e").text(Math.floor(x/10));
			$("#t_h2e").text(x%10);
		}		
	}	
}
function subHoursE(h){
	var i = parseInt($("#t_h1e").text());
	var j = parseInt($("#t_h2e").text());
	var x = i*10+j;
	console.log(x);
	x -= h;
	if(x < 0) x = 23;
	console.log(x);
	x %= 24;
	$("#t_h1e").text(Math.floor(x/10));
	$("#t_h2e").text(x%10);
}
function addMinutsE(m){
	var i = parseInt($("#t_m1e").text());
	var j = parseInt($("#t_m2e").text());
	var x = i*10+j;
	console.log(x);
	x += m;
	console.log(x);
	x %= 60;
	if(i == 5 && m == 10){
		$("#t_m1e").text(0);
		$("#t_m2e").text(j);
	}else{
		$("#t_m1e").text(Math.floor(x/10));
		$("#t_m2e").text(x%10);	
	}	
}
function subMinutsE(m){
	var i = parseInt($("#t_m1e").text());
	var j = parseInt($("#t_m2e").text());
	var x = i*10+j;
	console.log(x);
	x -= m;
	if(x < 0) x = 59;
	console.log(x);
	x %= 60;
	$("#t_m1e").text(Math.floor(x/10));
	$("#t_m2e").text(x%10);
}
$("#t_uh1e").click(function() {
	addHoursE(10);
});
$("#t_uh2e").click(function() {
	addHoursE(1);
});
$("#t_um1e").click(function() {
	addMinutsE(10);
});
$("#t_um2e").click(function() {
	addMinutsE(1);
});

$("#t_dh1e").click(function() {
	subHoursE(10);
});
$("#t_dh2e").click(function() {
	subHoursE(1);
});
$("#t_dm1e").click(function() {
	subMinutsE(10);
});
$("#t_dm2e").click(function() {
	subMinutsE(1);
});

$("#event_add_time").click(function() {
	var time = $("#t_h1").text() + $("#t_h2").text() + ":" + $("#t_m1").text() + $("#t_m2").text();
	$("#time").val(time);
});
$("#event_add_time_end").click(function() {
	var time = $("#t_h1e").text() + $("#t_h2e").text() + ":" + $("#t_m1e").text() + $("#t_m2e").text();
	$("#time_end").val(time);
});

$("#edit_event_time").click(function() {
	var time = $("#t_h1").text() + $("#t_h2").text() + ":" + $("#t_m1").text() + $("#t_m2").text();
	$("#time").val(time);
});

$("#edit_event_time_end").click(function() {
	var time = $("#t_h1e").text() + $("#t_h2e").text() + ":" + $("#t_m1e").text() + $("#t_m2e").text();
	$("#time_end").val(time);
});

function removeRow(t){
	console.log(t);
	$(t).parent("tr").remove();
	loadTimetoHiddenInputs();
}
function addNewTime(){
	var ds = $("#data").val();
	var ds = new Date(ds);
	if(!checkDate()) {
		alert("Błędna data!");
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
	$("html, body").animate({ scrollTop: 0 }, "slow");
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
	$('#send_form_add').click();
}

function showInfoBox(){
	$("#info_add").show();
}

$( document ).ready(function() {
    if($("#isOk").data('info') != "") showInfoBox();
	if($("#isOk").data('info') == "1") setTimeout(function(){ 
		var id = $("#id").val();
		window.location.href = 'event.php?id='+id+'&preview=1'; }, 2500 );
	else{
		setTimeout(function(){ $("#info_add").hide(); }, 2000 );
	}
	
	$("[name='price_type']").change(function() {
		if($(this).val() == 0){
			$("#price").val("0");
			$("#price").prop( "disabled", true );
		}else{
			$("#price").val("0");
			$("#price").prop( "disabled", false );
		}
	});
});