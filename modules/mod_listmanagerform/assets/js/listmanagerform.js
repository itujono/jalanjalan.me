var arrEditores="";

LMFM(function($) {
	$('#cb_form').css('display','');
	$('#cancelform').css('display','none');	
});
function replaceAll(text, searchtext, replacetext) {
  return text.replace(new RegExp(searchtext, 'g'),replacetext);
}


function getNumber(number,isdate,dateformat){
	if(isdate){
		var newdate=Date.fromString(number,{'order':dateformat});	        	
    	name=newdate.get('year')+''+String.from(newdate.get('month')).pad(2,'0','left')+''+String.from(newdate.get('date')).pad(2,'0','left');
    	return name.toFloat();
	} else { 
		if(LMJQ.type(number)!=='null' && !isNaN(number)){
			return parseFloat(number);
		}
	}
	return 0;
}
function testLimit(value, limit0, limit1, isdate, dateformat, type){
	switch(type){
		case 0:
			if (getNumber(value,isdate,dateformat)<getNumber(limit0,isdate,dateformat) || 
				getNumber(value,isdate,dateformat)>getNumber(limit1,isdate,dateformat)){				
		        return false;
		    }
			break;
		case 1:
			if (getNumber(value,isdate,dateformat)<getNumber(limit0,isdate,dateformat)){
				return false;
			}
			break;
		case 2:
			if (getNumber(value,isdate,dateformat)>getNumber(limit1,isdate,dateformat)){
				return false;
			}
			break;
	}
	return true;
}
function setCalendarLM(inputf, buttonfield){
	//Calendar.setup({'inputField':inputf,'ifFormat':getDateFormat(),'button':buttonfield});	
	LMFM('#'+inputf).datepicker({ 
		dateFormat: getDateFormat(), 
		onSelect: function() {LMFM(".ui-datepicker a").removeAttr("href"); }
	});
	LMFM(".ui-datepicker a").removeAttr("href");
}
(function($){	
})(LMFM);