LMF(function($) {
	$(this).addFilterObserver();
		
});
(function($){	
	$.fn.getModuleData = function(name){
    	var ret=null;
    	$(this).closest(".lm_filtermod").find('.lm_hidden_data').each(function(index,elem){    		
    		if($(elem).attr('name')==name){
    			ret=$(elem).attr('value');  
    			return;
    		}
    	});	
    	return ret;
    };    
    $.fn.getModuleData = function(name){
    	var ret=null;
    	$(this).closest(".lm_filtermod").find('.lm_hidden_data').each(function(index,elem){    		
    		if($(elem).attr('name')==name){
    			ret=$(elem).attr('value');  
    			return;
    		}
    	});	
    	return ret;
    };    
    $.fn.addFilterObserver = function(){
    	if(typeof CreateFilterTableObserver == 'undefined'){
    		CreateFilterTableObserver = function (data) {
    			/*
    			 * data.list 
    			 * data.seed 
    			 */			
    			var CreateFilterTableObserver;
    			var found=new Array();
    			$(".lm_filtermod").each(function(){
    				var list=$(this).first().getModuleData('list');				
    				if (list==data.list && ($.inArray(list, found)<0)){
    					found.push(list);
    					$(this).find('.lm_filtermod_wrapper').empty();
    					var ret=eval('listManager'+data.seed+'.printAutofilterModule();');
    					$(this).find('.lm_filtermod_wrapper').append(ret);
    					eval('postAutoFilter($(this).find(".lm_filtermod_wrapper"));');
    					var hidefilteronlist=$(this).first().getModuleData('hidefilteronlist');    				
        				if (hidefilteronlist=="1"){
        					$('#'+data.seed+'cb_result_wrapper').find('.lmtools').remove();
        					$('#'+data.seed+'cb_result_wrapper').find('.lmtoolfilter').remove();
        					$('#'+data.seed+'cb_result_filter_wrapper').remove();
        				}
        				setIMGComboLMF();        				
    				}    				
    			});
    		}
    		if (typeof observable !='undefined')
    			observable.subscribe('createDataTable',CreateFilterTableObserver);
    	}
    }
})(LMF);
function setIMGComboLMF(){
	var isimg=false;
	LMF('.lm_filtermod').find('select').each(function(index,elem){
		var isimg=false;
		LMF(elem).find('option').find('img').each(function(indeximg,elemimg){
			LMF(elemimg).closest('option').attr('data-imagesrc',LMF(elemimg).attr('src'));
			LMF(elem).addClass('imgcombo');
			LMF(elemimg).remove();
			isimg=true;			 
		});
		if (isimg){
			LMF(elem).closest('.lm_filtermod_wrapper').append('<input type="hidden" lm_fid="'+LMF(elem).attr('lm_fid')+'" seed="'+LMF(elem).attr('seed')+'" id="'+LMF(elem).attr('id')+'">');
		}
	});	
	LMJQ('.imgcombo').ddslick({
	    selectText: "",
	    width:130,
	    background: "#fff",
	    onSelected: function (data) {
	    	var hddn=LMF(data.selectedItem).closest('.lm_filtermod_wrapper').find('input[type="hidden"]').filter('[lm_fid]');
	    	LMF(hddn).val(data.selectedData.value);
	    	var lm_fid=LMF(hddn).attr("lm_fid");
	    	var selval=data.selectedData.value;
	    	eval('addFilter'+seed+'('+lm_fid+',"'+selval+'",false);');
	    }
	});
}