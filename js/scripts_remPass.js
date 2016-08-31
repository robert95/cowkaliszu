function remindPass(){
	var mail = $("#my-mail").val();

	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","remPass.php?mail=" + mail,true);
	xmlhttp.send();
	$("#result-remaind").show();
	setTimeout(function(){ location.reload(); }, 1000);	
}

function remPassShow(){
	$("#remaidForm").show();
}