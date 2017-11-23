LMS(function($) {	
	if( $('.lm_searchmod').getModuleData('includebutton')!=0){
		$('.lms_search').click(function(){
			var val=$(this).closest('.form-search').find('.lms_search_input').val();			
			if (typeof observable !='undefined')
				observable.publish('search',val);
		});	
		$('.lms_search_input').keydown(function(event){
			var code = event.which; 
			if(code==13){
				$('.lms_search').click();
		    }			
		});
	} else {
		$('.lms_search_input').change(function(){
			if (typeof observable !='undefined')
				observable.publish('search',$(this).val());
		});
	}	
	if( $('.lm_searchmod').getModuleData('hidesearchonlist')!=0){
		$('.lm_search').hide();
	}
});
(function($){
	$.fn.getModuleData = function(name){
    	var ret=null;
    	$(this).closest(".lm_searchmod").find('.lm_hidden_data').each(function(index,elem){    		
    		if($(elem).attr('name')==name){
    			ret=$(elem).attr('value');  
    			return;
    		}
    	});	
    	return ret;
    };    
})(LMS);