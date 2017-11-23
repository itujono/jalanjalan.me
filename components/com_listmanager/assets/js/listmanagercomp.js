LMC(function($) {	
	$('.lm_comp_compare').click(function(){ $(this).showCompare(); });
	var lm_advise=$('.lm_compmod').find('#lm_advise_colums').dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		}
	});
	var lm_compare=$('.lm_compmod').find('#lm_compare').dialog({
		autoOpen: false,
		modal: true,
		width: 'auto',
		buttons: {
			Ok: function() {
				$(this).dialog('close');
			}
		}
	});	
	$(function() {
		var CreateDataTableObserver = function (data) {
			$( ".thumbnail" ).draggable({
				appendTo: "body",
				helper: function( event ) {
			        return $( "<div class='lmcomp_helper_wrapper'><span>"+$('.lm_comp_compare').getModuleData('draghelper')+"</span></div>" );
			    },
			    start: function( event, ui ) {},
			    cursorAt: { top: 10 },
				handle: "#lm_droppable"
			});				
			$( "#lm_droppable" ).droppable({
				activeClass: "ui-state-default",
				hoverClass: "lm-ui-state-hover",
				drop: function( event, ui ) {
					var numCols=$(this).getModuleData('numcomp');
					if ($(this).closest('#lm_droppable').find('.lm_comp_container').length>=numCols){					
						$(lm_advise).dialog('open');
						return;
					} 
					$(this).setPlaceHolder();
					var data=ui.draggable.find('div').contents().filter(function() {
					      return $(this).hasClass('lmcardcontentblock');
				    }).clone();				
				    var tools_group=$('<div class="btn-group"></div>');
				    // expand button
				    if ($(this).getModuleData('seeexpand')!='0'){
					    var see_wrapper='<a class="btn btn-link">';
					    if ($(this).getModuleData('seebutton')!='0') see_wrapper=see_wrapper+'<i class="glyphicon glyphicon-circle-arrow-down"></i>  ';
					    if ($(this).getModuleData('seetext')!='0') see_wrapper=see_wrapper+$(this).getModuleData('expand');
					    see_wrapper=see_wrapper+'</a>';
					    var see=$(see_wrapper);
						$(see).click(function(){ $(this).seeCard(); });
						$(tools_group).append(see);
					}
					// remove button
					if ($(this).getModuleData('seeremove')!='0'){
						var del_wrapper='<a class="btn btn-link">';
					    if ($(this).getModuleData('seebutton')!='0') del_wrapper=del_wrapper+'<i class="glyphicon glyphicon-remove-circle"></i>  ';
					    if ($(this).getModuleData('seetext')!='0') del_wrapper=del_wrapper+$(this).getModuleData('delete');
					    del_wrapper=del_wrapper+'</a>';
						var del=$(del_wrapper);
						$(del).click(function(){ $(this).removeCard(); });
						$(tools_group).append(del);	
					}
					var tools=$('<div class="lm_comp_tools btn-toolbar"></div>');
					$(tools).append(tools_group);
					var container=$('<div class="lm_comp_container"></div>');
					$(container).append(tools);
					var container_data=$('<div class="lm_comp_content row-fluid"></div>').append(data);
					$(container).append(container_data);
					$(container).appendTo(this);
					$(container).find('.lm_comp_content').slideUp('slow');
					$(container).SetProgressData();
			    	$(container).SetLinkedList();			    			    	
				}
			});			
		}
		if (typeof observable !='undefined')
			observable.subscribe('createDataTable',CreateDataTableObserver);
	});	
	Array.prototype.unique =function() {
	    var a = [];
	    var l = this.length;
	    for(var i=0; i<l; i++) {
	      for(var j=i+1; j<l; j++) {
	        // If this[i] is found later in the array
	        if (this[i] === this[j])
	          j = ++i;
	      }
	      a.push(this[i]);
	    }
	    return a;
	  };
});
(function($){
	var lmcomp_placeholder=null;
    $.fn.showCompare = function(){
    	if($(this).closest('.lm_compmod').find('.lm_comp_container').length<=0) return false;
    	$(lm_compare).find('.lm_comp_wrapper').empty();
    	var names=new Array();
    	var data=new Array();
    	$(this).closest('.lm_compmod').find('.lm_comp_content').each(function(index,elem){    		
			var card=new Array();			
    		var elemclone=$(elem).clone().find('.lmcarditem_data');
    		$(elemclone).each(function(){    			
    			var name=$(this).parent().find('.lmcarditem_name');
    			var name_value=$(name).text();
    			names.push(name_value);
    			card.push({'name':name_value,'content':$(this).html()});   			
    		});	
    		names=names.unique();
    		data.push(card);
    	});    	
    	var table_layout=$(this).getModuleData('comptable');
    	if (table_layout=='0')
    		$(lm_compare).find('.lm_comp_wrapper').append(createTableHorizontal(names,data));
    	else
    		$(lm_compare).find('.lm_comp_wrapper').append(createTableVertical(names,data));
    	
    	$(lm_compare).find('.lm_comp_content').show();
    	$(lm_compare).dialog('open');    
    	$(lm_compare).SetProgressData();
    	$(lm_compare).SetLinkedList();
    };
    $.fn.setPlaceHolder = function(){
    	if (lmcomp_placeholder==null)
			lmcomp_placeholder=$(this).find( ".lm_comp_placeholder" ).clone();
		$(this).find( ".lm_comp_placeholder" ).remove();	
    };
    $.fn.seeCard = function(){
    	if ($(this).find('i').hasClass('icon-circle-arrow-down')){
			$(this).closest('.lm_comp_container').find('.lm_comp_content').slideDown('slow');
			$(this).find('i').removeClass('icon-circle-arrow-down');
			$(this).find('i').addClass('icon-circle-arrow-up');
		} else {
			$(this).closest('.lm_comp_container').find('.lm_comp_content').slideUp('slow');
			$(this).find('i').removeClass('icon-circle-arrow-up');
			$(this).find('i').addClass('icon-circle-arrow-down');
		}	
    };
    $.fn.removeCard = function(){    	
    	if($(this).closest('#lm_droppable').find('.lm_comp_container').length==1){
    		$(this).closest('#lm_droppable').first('div').append(lmcomp_placeholder);
    	}
    	$(this).closest('.lm_comp_container').remove();
    };
    $.fn.getModuleData = function(name){
    	var ret=null;
    	$(this).closest(".lm_compmod").find('.lm_hidden_data').each(function(index,elem){    		
    		if($(elem).attr('name')==name){
    			ret=$(elem).attr('value');  
    			return;
    		}
    	});	
    	return ret;
    };
    $.fn.SetProgressData = function(){
	    $(this).find('.lm_progresson').each(function(){
			inte=$(this).attr('inte');
			prc=$(this).attr('prc');
			var porc=parseInt((prc*($(this).closest('.lm_progress').width()))/inte); 
			$(this).css('width',porc);
		});
    };    
    $.fn.SetLinkedList = function(){
	    $(this).find('.lmlinklist').each( function(index,elem){
			$(elem).click(function(event){
			  	gotoList($(this)); // This function is part of List Manager
			});
		});
    };
    
    
    function boot_column_conversor(numColumns){
    	var ret=1;
    	switch(numColumns){
    	case '1': ret='span12';break;
    	case '2': ret='span6';break;
    	case '3': ret='span4';break;
    	case '4': ret='span3';break;
    	case '5': case '6': ret='span2';break;	
    	}
    	return ret;
    };
    function createTableHorizontal(header,data){
    	var table=$('<table>',{'class':'lmcomp_detail_table table'});
    	for(var i=0;i<header.length;i++){
    		var tr=$('<tr>');
    		var td_name=$('<td>'+header[i]+'</td>');
    		$(tr).append(td_name);    		
    		for(var d=0;d<data.length;d++){
    			var isData=false;
    			for(var h=0;h<header.length;h++){
    				if (typeof data[d][h]== 'undefined') {
        				$(tr).append('<td></td>');
        				isData=true;
        				break;
    				}
    				var valname=data[d][h].name;    				
    				if (header[i]==valname){
	    				$(tr).append('<td>'+data[d][h].content+'</td>');
	    				isData=true;
	    				break;
	    			}    				
    			}    			
    			if(!isData){
		    		$(tr).append('<td></td>');
		    	}
    		}    		
    		$(table).append(tr);
    	}
    	return table;
    };
    function createTableVertical(header,data){
    	var table=$('<table>',{'class':'lmcomp_detail_table table'});
    	var thead=$('<thead>');
    	var tr=$('<tr>');
    	for(var i=0;i<header.length;i++){
    		$(tr).append('<th>'+header[i]+'</th>');
    	}
    	$(thead).append(tr);
    	var tbody=$('<tbody>');
    	
    	for(var i=0;i<data.length;i++){
    		var tr=$('<tr>');
    		for(var h=0;h<header.length;h++){
    			var isData=false;
		    	for(var j=0;j<data[i].length;j++){
		    		var valname=data[i][j].name;
		    		if (header[h]==valname){
		    			$(tr).append('<td>'+data[i][j].content+'</td>');
		    			isData=true;
		    			break;
		    		}
		    	}
		    	if(!isData){
		    		$(tr).append('<td></td>');
		    	}
		    }	    	
	    	$(tbody).append(tr);
    	}
    	$(table).append(thead);
    	$(table).append(tbody);
    	return table;
    };
})(LMC);