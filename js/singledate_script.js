
var weekNames = ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];
var mounthNames = ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'];
function addDate(date) {
	removeDate(0);
    if (jQuery.inArray(date, dates) < 0){
		dates.push(date);
		var stringDate = date;
		var date = new Date(Date.parse(stringDate));
		var dayNumber = date.getDate();
		var weekDayName = weekNames[date.getDay()];
		var mounthName = mounthNames[date.getMonth()];
		var index = $(".day-time").length;
		var html = '<div class="day-time">';
		html += '<input type="hidden" class="date-value-hide" name="date['+index+']" value="'+stringDate+'">';
		html += '<p>'+weekDayName+' <span>'+dayNumber+' '+mounthName+'</span></p>';
		html += '<div class="time-details-cont">';
		html += '<div class="time-details">';
		html += '<p data-index="'+index+'">od <input type="text" name="time['+index+'][0]" value="00:00"> do <input type="text" name="time['+index+'][1]" value="23:59"> <span><img src="img/add_time.png" alt="Następna godzina" class="add-next-time" onclick="addNextTime(this);"> <img src="img/remove_time.png" alt="Usuń godzinę" class="remove-this-time" onclick="removeThisTime(this);"></span></p>';
		html += '</div></div></div>';
		
		$(".time-conatiner").append(html);
		upDateShowTimeDetails();
		updateVisibleTypeHours();
	}
}

function addDateToSingleDate(date, time, time_end) {
	removeDate(0);
    if (jQuery.inArray(date, dates) < 0){
		dates.push(date);
		var stringDate = date;
		var date = new Date(Date.parse(stringDate));
		var dayNumber = date.getDate();
		var weekDayName = weekNames[date.getDay()];
		var mounthName = mounthNames[date.getMonth()];
		var html = '<div class="day-time">';
		html += '<input type="hidden" class="date-value-hide" name="date[0]" value="'+stringDate+'">';
		html += '<p>'+weekDayName+' <span>'+dayNumber+' '+mounthName+'</span></p>';
		html += '<div class="time-details-cont">';
		html += '<div class="time-details">';
		html += '<p data-index="0">od <input type="text" name="time[0][0]" value="' + time + '"> do <input type="text" name="time[0][1]" value="' + time_end + '"></p>';
		html += '</div></div></div>';
		
		$(".time-conatiner").append(html);
		upDateShowTimeDetails();
		updateVisibleTypeHours();
	}
}

function removeDate(index) {
	var dateToRemove = dates[index];
    dates.splice(index, 1);
	updateVisibleTypeHours();
	
	$(".date-value-hide").each(function(){
		if($(this).val() == dateToRemove){
			$(this).parent().remove();
		}
	});
}

function printArray() {
    var printArr = new String;
    dates.forEach(function (val) {
        printArr += '<h4>' + val + '</h4>';
    });
    $('#print-array').html(printArr);
}
// Adds a date if we don't have it yet, else remove it
function addOrRemoveDate(date) {
    var index = jQuery.inArray(date, dates);
    if (index >= 0) 
        removeDate(index);
    else 
        addDate(date);
}

// Takes a 1-digit number and inserts a zero before it
function padNumber(number) {
    var ret = new String(number);
    if (ret.length == 1) ret = "0" + ret;
    return ret;
}

$(document).ready(function(){
	$("#datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		dayNamesMin: [ "Nie", "Pon", "Wt", "Śr", "Cz", "Pt", "So" ],
		monthNames: [ "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień" ],
		firstDay: 1,
		onSelect: function (dateText, inst) {
			addOrRemoveDate(dateText);
		},
		beforeShowDay: function (date) {
			var year = date.getFullYear();
			// months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
			var month = padNumber(date.getMonth() + 1);
			var day = padNumber(date.getDate());
			// This depends on the datepicker's date format
			var dateString = year + '-' + month + "-" + day;

			var gotDate = jQuery.inArray(dateString, dates);
			if (gotDate >= 0) {
				// Enable date so it can be deselected. Set style to be highlighted
				return [true, "ui-state-highlight"];
			}
			// Dates not in the array are left enabled, but with no extra style
			return [true, ""];
		}
	});
	
	$("input[name='price']").change(function(){
		if($(this).val() == 1){
			$("input[name='price-val']").parent().removeClass("no-active-price");
			$("input[name='price-val']").attr("disabled", false);
		}else{
			$("input[name='price-val']").parent().addClass("no-active-price");
			$("input[name='price-val']").attr("disabled", true);
		}
	});
	
	$("input[name='time-from-place']").on('click', upDateShowTimeDetails);
	
	getOpenHours();
});

$(document).mouseup(function (e){
    var container = $("#datepicker");
    if (!container.is(e.target) && container.has(e.target).length === 0){
        container.hide();
    }
});

function showCalendar(){
	$("#datepicker").show();
}

function addNextTime(obj){
	var index = $(obj).parent().parent().attr('data-index');
	var index2 = ($(obj).parent().parent().parent().parent().find('.time-details').length) * 2;
	var html = '<div class="time-details"><p data-index="'+index+'">od <input type="text" name="time['+index+']['+index2+']" value="00:00"> do <input type="text" name="time['+index+']['+(index2+1)+']" value="23:59"> <span><img src="img/add_time.png" alt="Następna godzina" class="add-next-time" onclick="addNextTime(this);"> <img src="img/remove_time.png" alt="Usuń godzinę" class="remove-this-time" onclick="removeThisTime(this);"></span></p></div>';
	$(obj).parent().parent().parent().after(html);
}

function removeThisTime(obj){
	$(obj).parent().parent().parent().remove();
}

function upDateShowTimeDetails(){
	if($("input[name='time-from-place']:checked").val() == 1){
		$(".time-details-cont").hide();
	}else{
		$(".time-details-cont").show();
	}
}

function updateVisibleTypeHours(){
	if(dates.length == 0 || !hasOpenHours){
		$(".time-from-place-cont").hide();
		$("#set-hours").attr('checked', 'checked');
		$("#set-hours").prop("checked", true)
		upDateShowTimeDetails();
	}else{
		$(".time-from-place-cont").show();
	}
}

var hasOpenHours = false;
function getOpenHours(){
	var placeId = $("#placeId").val();
	$.ajax({
	   type: "GET",
	   url: "user_ajax.php?action=getOpenHours&place="+placeId,
	   success: function(data){
		   if(data == ""){
			   hasOpenHours = false;
			   updateVisibleTypeHours();
		   }else{
				hasOpenHours = true;
				updateVisibleTypeHours();
		   }
	   }
	});
}