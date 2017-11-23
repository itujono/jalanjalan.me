var ListManager = function(options){
	//Implements: [Options, Events],
	this.acl=null;
	this.effects=true;
	this.headers= [];
	this.data= [];
	this.filter=null;
	this.filtrableData=[];
	this.totalsData=[];
	this.totals=[];
	this.pagact=1;
	this.porpag=10;
	this.countData=0;
	this.isFilterChange=false;
	this.displayAll=false;
	this.seed=null;
	this.list_columns=null;
	this.list_height=null;
	this.list_name_class=null;
	this.list_value_class=null;
	this.tool_column_position=null;
	this.tool_column_name=null;
	this.isBulk=null;
	this.options={
		viewonly: false,
		showtotals: false,
		dateformat:'yy-m-d',
		dateformatview:'yy-m-d',
		items_view:10,
		access_type:0,
		uid:0,
		effects:1,
		show_filter:0,
		filter_automatic:0
	};
    /*initialize: function(options){
    	this.setOptions(options);    	
    },*/
	this.options = jQuery.extend(this.options, options || {});
	this.setPrefs=function(data){
    	this.countData = data['datacount'];
    	this.filtrableData = JSON.parse(data['datafilter']);
    	this.totalsData = JSON.parse(data['datatotal']);
    };
    this.setSeed=function(seed){
    	this.seed=seed;
    };
    this.setCardColumns=function(list_columns){
    	this.list_columns=list_columns;
    };
    this.setCardColumnsHeight=function(list_height){
    	this.list_height=list_height;
    };
    this.setCardNameClass=function(list_name_class){
    	this.list_name_class=list_name_class;
    };
    this.setCardValueClass=function(list_value_class){
    	this.list_value_class=list_value_class;
    };
    this.setToolComlumnPosition=function(tool_column_position){
    	this.tool_column_position=tool_column_position;
    };
    this.setToolComlumnName=function(tool_column_name){
    	this.tool_column_name=tool_column_name;
    };
    this.reset=function(){
    	this.headers.length=0;
    	this.data.length=0;
    	this.totals.length=0;
    	this.countData=0;
    };
    this.isEffects=function(){return this.options.effects==1;},
    this.setPagAct=function(pagact){this.pagact=pagact;},
    this.setPorPag=function(porpag){
    	this.displayAll=isNaN(porpag);
    	if (porpag<=0) this.porpag=this.options.items_view;
    	else this.porpag=porpag;
    };
    this.setAcl= function(acl){
    	this.acl=new LmAcl();
    	this.acl.setAcl(acl);
    };
    this.getAcl= function(){ return this.acl; };
    this.setFilter=function(filter){ 
    	this.filter=filter;
    	this.isFilterChange=true;
    };
    this.getFilterValueById= function(headerid){
    	if (this.filter!=null){
    		return this.filter.getFilterByHeaderid(headerid);
    	}
    	return null;
    };
    this.getFilterValueByIdRaw= function(headerid){
    	if (this.filter!=null){
    		return this.filter.getFilterByHeaderidRaw(headerid);
    	}
    	return null;
    };
    this.isBulkSelected=function(){
    	if (this.isBulk!=null) return this.isBulk;
    	for(var i=0;i<this.headers.length;i++){
    		if (this.headers[i].bulk=='1'){
    			this.isBulk=true;    			
    		}	
    	}    
    	if (this.isBulk==null){
    		this.isBulk=false;
    	}    	
    	return this.isBulk;
    }
    this.addHeader= function(header){ this.headers.push(header); };    
    this.printHeader= function(){    	
    	var tr=LMJQ('<tr>');
    	var th,div,divtext,imagen,imagen_desc,tdoptions;
    	LMJQ(this.headers).each(function(index,header){
    		if(header.isVisible()){
    			var th=LMJQ('<th>',{'class':'lm_tableheader lm_columnheader lm_column_'+index});    			
    			var div=LMJQ('<div>',{'class':'lm_header_toolbar_wrapper'});    			   			
    			if (header.isShowOrder()){
    				var imagen = LMJQ('<div>',{'class':'lm_order lm_selectable glyphicon glyphicon-chevron-up lm_img_asc',
	                          'name':'imgorder','title':getLangTradsLM('LM_ORDER')+' :: '+getLangTradsLM('LM_ORDER_TOOLTIP')});
    				LMJQ(imagen).attr('idorder', header.id);      			
    				LMJQ(div).append(imagen);	      				      			
	      			var imagen_desc = LMJQ('<div>',{'class':'lm_order lm_selectable glyphicon glyphicon-chevron-down lm_img_desc',
                        'name':'imgorderdesc','title':getLangTradsLM('LM_ORDER_DESC')+' :: '+getLangTradsLM('LM_ORDER_TOOLTIP')});	      			
	      			LMJQ(imagen_desc).attr('idorder', header.id);
	      			LMJQ(div).append(imagen_desc);
    			}
    			LMJQ(th).append(div);
    			var divtext=LMJQ('<span>',{'class':'lm_header_toolbar_text'});
    			LMJQ(divtext).html(header.name);
    			LMJQ(th).append(divtext);
    			LMJQ(tr).append(th);    			 			
    		}
    	});
    	if (!this.options.viewonly){ 
    		var tdoptions=LMJQ('<th>',{'class':'lm_tableheader_empty'});
    		//LMJQ(tdoptions).html(this.tool_column_name);
    		var calculatedwidth=0;
    		var oneatleast=false;
    		// Edit
	        if (!this.options.viewonly && this.acl.isGlobalEdit()){
	        	calculatedwidth=calculatedwidth+32;
	        	oneatleast=true;
	        }
	        // Detail
	        if (this.acl.isDetail()) {
	        	calculatedwidth=calculatedwidth+32;
	        	oneatleast=true;
	        }
	        // DetailPdf
	        if (this.acl.isDetailpdf()) {
	        	calculatedwidth=calculatedwidth+32;
	        	oneatleast=true;
	        }
	        // DetailRtf
	        if (this.acl.isDetailrtf()) {
	        	calculatedwidth=calculatedwidth+32;
	        	oneatleast=true;
	        }
	        // Delete
	        if (this.acl.isDelete()) {
	        	calculatedwidth=calculatedwidth+32; 
	        	oneatleast=true;
	        }
	        // Bulk
	        var bulkAll='';
	        if (this.isBulkSelected() && this.acl.isBulk()){
	        	calculatedwidth=calculatedwidth+16; 
	        	oneatleast=true;
	        	bulkAll='<input type="checkbox" class="pull-right" style="margin-right: 8px;margin-left: 8px;" onclick="bulkcheckAll(this);"/>';
	        }
	        LMJQ(tdoptions).html(this.tool_column_name+' '+bulkAll);
	        LMJQ(tdoptions).css('min-width',calculatedwidth+'px');
	        LMJQ(tdoptions).css('max-width',calculatedwidth+'px');
	        LMJQ(tdoptions).css('width',calculatedwidth+'px');
	        if (!this.options.viewonly && oneatleast){
	        	if (this.tool_column_position==0)
	        		LMJQ(tr).append(tdoptions);
	        	else    	        		
	        		LMJQ(tr).find('th').eq(this.tool_column_position-1).before(tdoptions);
	        }    		
  		}   	
  		return tr; 
    };    
    this.printAutofilterTool= function(){
    	var isFiltro=false;
    	for(var j=0;j<this.headers.length;j++){
    		var header=this.headers[j];
    		if(header.isAutofilter() && header.isVisible()){
    			isFiltro=true;
    		}    				
    	}
    	if (isFiltro){    		
    		/*var tr_tool=LMJQ('<tr>');
	    	var headers_number=this.headers.length+1;
	    	if (this.options.viewonly) headers_number=this.headers.length;
	    	var th_tool=LMJQ('<th>',{'class':'lm_tableheader','colspan':headers_number});*/
    		var div_container=LMJQ('<div>',{'class':'lm-filter'});
    		var nav_container=LMJQ('<nav class="navbar" role="navigation">');
    		var nav_header=LMJQ('<div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#filternav"><span class="sr-only">'+getLangTradsLM('LM_TOGGLE_NAVIGATION')+'</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><span class="navbar-brand">'+getLangTradsLM('LM_VIEW_FILTER')+'</span></div>');
    		var nav_content=LMJQ('<div class="collapse navbar-collapse" id="filternav">');
    		var ul_nav=LMJQ('<ul>',{'class':'nav navbar-nav form-inline'});
    		    		
    		if (this.options.show_filter=='0'){
    			// Show
        		var li_show=LMJQ('<li>');
        		var li_show_button=LMJQ('<button>',{'type':'button','class':'btn btn-default btn-sm'});
        		var li_show_button_icon=LMJQ('<span>',{'class':'glyphicon glyphicon glyphicon-filter'});
        		LMJQ(li_show_button).append(li_show_button_icon,' '+getLangTradsLM('LM_FILTER_SHOW'));
        		LMJQ(li_show_button).click(function(event){    			
        			LMJQ(this).closest('.panel').find('.panel-body').toggle({
        				effect:'blind',
        				complete: function(){
    		    				LMJQ(this).closest('.panel').find('.panel-body').find('.result_filter_wrapper').isotope({
    		        		    	itemSelector : '.lm_filter_col'
    		        	    });    		    				
    		    		}
        			});
    			});
        		LMJQ(li_show).append(li_show_button);
        		LMJQ(ul_nav).append(li_show);	
    		} 
    		
    		if (this.options.filter_automatic=='0'){
	    		// Apply
	    		var li_apply=LMJQ('<li>');
	    		var li_apply_button=LMJQ('<button>',{'type':'button','class':'btn btn-default btn-sm'});
	    		var li_apply_button_icon=LMJQ('<span>',{'class':'glyphicon glyphicon glyphicon-log-in'});
	    		LMJQ(li_apply_button).append(li_apply_button_icon,' '+getLangTradsLM('LM_FILTER_APPLY'));
	    		LMJQ(li_apply_button).click(function(event){
	    			var idtable=LMJQ(this).closest('.lm_result').attr('seed');
					eval("executeFilters"+idtable+"();");				
				});
	    		LMJQ(li_apply).append(li_apply_button);
	    		LMJQ(ul_nav).append(li_apply);
    		}
    		// Disable
    		var li_disable=LMJQ('<li>');
    		var li_disable_button=LMJQ('<button>',{'type':'button','class':'btn btn-default btn-sm'});
    		var li_disable_button_icon=LMJQ('<span>',{'class':'glyphicon glyphicon glyphicon-log-out'});
    		LMJQ(li_disable_button).append(li_disable_button_icon,' '+getLangTradsLM('LM_DISABLE_FILTER'));
    		LMJQ(li_disable_button).click(function(event){
    			var idtable=LMJQ(event.target).closest('.lm_result').attr('seed');
				eval("clearAutofilter"+idtable+"()");
			});
    		LMJQ(li_disable).append(li_disable_button);
    		LMJQ(ul_nav).append(li_disable);    	
    		
    		LMJQ(nav_content).append(ul_nav);
    		LMJQ(nav_container).append(nav_header);
    		LMJQ(nav_container).append(nav_content);
    		
    		/*LMJQ(th_tool).append(nav_container);
    		LMJQ(tr_tool).append(th_tool);
			return tr_tool;*/    		
    		return nav_container;
    	}
    	return '';
    };
    this.printAutofilter= function(){
    	var isAnyFilter=false;    	
    	var tr=LMJQ('<div>',{'class':'row-fluid result_filter_wrapper'});
    	for(var j=0;j<this.headers.length;j++){
    		var header=this.headers[j];
    		if(header.isAutofilter() && header.isVisible()){  
	    		var th=LMJQ('<div>',{'class':'lm_filter_col col-sm-6 col-md-3 lm_filter_colum_'+j});
	    		var valueselected=this.getFilterValueById(header.id);
	    		var arrayvalueselected=this.getFilterValueByIdRaw(header.id);	    		  
    			isAnyFilter=true;
    			var arr_filtrables=new Array();
    			arr_filtrables=this.filtrableData[header.id];
    			var divwrapper=LMJQ('<div>');
    			LMJQ(divwrapper).addClass('thumbnail');
    			var div=LMJQ('<div>');
    			LMJQ(div).addClass('caption');
    			var h3=LMJQ('<h4>');
    			LMJQ(h3).text(header.name);
    			LMJQ(div).append(h3);
    			switch(header.autofilter){
    				case '0': // Select    					
    					if(header.type!=15){
    						LMJQ(div).append(af_default_select(header,arr_filtrables,this.seed,valueselected,this.options.filter_automatic));
    					} else {
    						LMJQ(div).append(af_rate_select(header,this.seed,valueselected,this.options.filter_automatic));
    					}
    					break;
    				case '1': // Text
    					LMJQ(div).append(af_default_text(header,this.seed,valueselected,this.options.filter_automatic));
    					break;
    				case '2': // Multiple
    					if(header.type!=15){
    						LMJQ(div).append(af_default_multivalue(header,arr_filtrables,this.seed,arrayvalueselected,this.options.filter_automatic));
    					}else{
    						LMJQ(div).append(af_rate_multivalue(header,arr_filtrables,this.seed,arrayvalueselected,this.options.filter_automatic));
    					}
    					break;
    				case '3': //Range
    					switch(header.type){
    						case '1': //Date    							
    							LMJQ(div).append(af_default_daterange(header,this.seed,arrayvalueselected,this.options.filter_automatic));  
    							break;
    						case '0': // Number
    						case '14': // Number slider
    						case '19': // Autoincrement
    							LMJQ(div).append(af_default_numberrange(header,this.seed,arrayvalueselected,this.options.filter_automatic));
    							break;
    						case '4': // Text
    							LMJQ(div).append(af_default_textrange(header,this.seed,arrayvalueselected,this.options.filter_automatic));
    							break;
    					}
    					break;
    			}    
    			//LMJQ(div).text(header.name);
    			/*
    			 * 
  						<div class="col-sm-6 col-md-3">
    						<div class="thumbnail">
    							<div class="caption">
        							<h3>Thumbnail label</h3>
        							<p>...</p>
    			 */
    			
    			LMJQ(divwrapper).append(div);
    			LMJQ(th).append(divwrapper);
    		}    		
    		if (header.isVisible()) LMJQ(tr).append(th);
    	}    	
    	//var th=LMJQ('<th>',{'class':'lm_tableheader lm_column_last'});
    	//var div_disable=LMJQ('<div>');
    	//LMJQ(th).append(div_disable);
    	//LMJQ(tr).append(th);    	
    	if(isAnyFilter) return tr;
    	else return '';
    };
    this.setHeaders= function(data){
    	this.headers.length=0;
    	for(var i=0;i<data.length;i++){    	
    		var header_temp = new LmHeader();
    		header_temp.setJsonData(data[i]);
    		this.addHeader(header_temp);
    	}
    }; 
    this.getHeaders=function(){
    	return this.headers;
    };
    this.getHeadersLength=function(){
    	return this.headers.length;
    };  
    this.getHeader=function(index){
    	return this.headers[index];
    };
    this.getHeaderById=function(id){
    	for(var i=0;i<this.headers.length;i++){
    		if (this.headers[i].id==id) return this.headers[i]; 
    	}
    	return null;
    };
    this.getHeaderByName=function(name){
    	for(var i=0;i<this.headers.length;i++){    		
    		if (this.headers[i].name==name) return this.headers[i]; 
    	}
    	return null;
    };
    this.setHeaderOrderToogle=function(id){
    	for(var i=0;i<this.headers.length;i++){
    		if (this.headers[i].id==id) {
    			if (this.headers[i].order)
    				this.headers[i].order=false;
    			else
    				this.headers[i].order=true;
    		}
    	}
    };
    this.addData=function(data){
    	this.data.push(data);
    };
    this.setCountData=function(value){
    	this.countData=value;
    };
    this.getCountData=function(){    	 	
    	return this.countData;    	
    };
    this.getTotalPages=function(){
    	if (this.getCountData()>0)
    		return Math.ceil(this.getCountData()/this.porpag);
    	else
    		return 1;
    };
    this.getAllIdRecordList=function(){
    	var ret="";
    	var index=0;
    	for(var i=0;i<this.data.length;i++){
    		var elem=this.data[i];    		
    		if (!elem.checkFilter(this.filter,this.getHeaders())) continue;
    		if(index>0) ret+="#";
    		ret+=this.getRow(i).idrecord;
    		index++;
    	}
    	return ret;
    };
    this.printData= function(){    	
    	var tbody=LMJQ('<tbody>');
    	var numreg=0;    
    	this.totals.length=0;
    	/*if (this.data.length==0){
    		alert('YOUR_MESSAGE');
    	}*/
    	for(var i=0;i<this.data.length;i++){
    		var uservalue=0;
    		var elem=this.data[i];    		
    	        var trdato;
    	        if(numreg%2==0)
    	            trdato=LMJQ('<tr>',{'class':'lm_trwhite'});    	            
    	        else    
    	            trdato=LMJQ('<tr>',{'class':'lm_trblack'});
    	        numreg++;
    	        for(var h=0; h<this.getHeadersLength();h++){
    	        	var header=this.getHeader(h);    	        	
    	        	if(header.isVisible()){
    	        		var tddato= LMJQ('<td>',{'class':'lm_td_dato lm_column_'+h+' '+header.styleclass});
    	        		if (this.getRow(i).getData(header.id)!=null || header.type=='15'){    	        			
    	        			if (header.link_url!=null && header.link_url!=''){
	    	        			// Link
	    	        			LMJQ(tddato).addClass('lmlinklist');
			        			LMJQ(tddato).attr('seed',this.seed);
			        			LMJQ(tddato).attr('headerid',header.id);
			        			//LMJQ(tddato).attr('data-detail',header.link_detail);
    	        			}
		        			
    	        			switch(header.type){
    	        				case '1':
    	        				case '12':
    	        					// Date
    	        					/*var valordato=this.getRow(i).getData(header.id).getValue();
    	        					LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormat()});
    	        					LMJQ('#lmdatepicker_calcs').datepicker('setDate', valordato);
    	        					//LMJQ('#lmdatepicker_calcs').val(valordato);
    	        					var jsdate=LMJQ('#lmdatepicker_calcs').datepicker( "getDate" );
    	        					valordato=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);
    	        					LMJQ(tddato).html(valordato);*/    	        					
    	        					var valordato=this.getRow(i).getData(header.id).getValue();
    	        					var randid=Math.floor((Math.random() * (9999999)) + 100000);
    	        					var newTmpElem='<input type="text" id="r_'+randid+'"></div>';
    	        					LMJQ('#lmdatepicker_calcs').append(newTmpElem);
    	        					LMJQ('#r_'+randid).datepicker({dateFormat: this.getDateFormat()});    	        					
    	        					LMJQ('#r_'+randid).val(valordato);
    	        					//console.log(valordato+'    '+this.getDateFormat());
    	        					var jsdate=LMJQ('#r_'+randid).datepicker("getDate");
    	        					//console.log(jsdate);
    	        					valordato=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);
    	        					//console.log(valordato);
    	        					//console.log('---------------');
    	        					valTemp=valordato;
    	        					LMJQ('#r_'+randid).remove();
    	        					LMJQ(tddato).html(valordato);
    	        					break; 
    	        					
	    	        			case '4':
	    	        				if (header.readmore=='1'){
	    	        					LMJQ(tddato).addClass('expandable');
	    	        					LMJQ(tddato).attr('rm_word',header.readmore_word_count);
	    	        				}
	    	        				if(header.styleclass && header.styleclass.indexOf('linkurl') !=-1) {	                                    
                                        LMJQ(tddato).html("<a href='"+this.getRow(i).getData(header.id).getValue()+"'>"+this.getRow(i).getData(header.id).getValue()+"</a>");
                                    }                                   
                                    if(header.styleclass && header.styleclass.indexOf('linkmail') !=-1) {                                   
                                        LMJQ(tddato).html("<a href='mailto:"+this.getRow(i).getData(header.id).getValue()+"'>"+this.getRow(i).getData(header.id).getValue()+"</a>");
                                    }
	    	        				LMJQ(tddato).html(this.getRow(i).getData(header.id).getValue());
	    	        				break;
	    	        			case '6':
		        					// User
		        					//var dataTempUser=this.getRow(i).getData('_struser').getValue();
	    	        				var dataTempUser=this.getRow(i).getData(header.id+'_str').getValue();
	    	        				if (LMJQ.type(dataTempUser)!=='null')
	    	        					LMJQ(tddato).html(dataTempUser);
		        					uservalue=this.getRow(i).getData(header.id).getValue();
		        					if (LMJQ.type(uservalue)==='null')uservalue=0;
		        					break;
		        				case '7':
		        					var valordato=this.getRow(i).getData(header.id).getValue();
		        					if (header.readmore=='1'){
	    	        					LMJQ(tddato).addClass('expandable');
	    	        					LMJQ(tddato).attr('rm_word',header.readmore_word_count);
	    	        				}
		        					LMJQ(tddato).html(valordato.replace(/\n\r?/g, '<br />'));
	    	        				break;
		        				case '8':
		        					// Progress
		        				   var valordato=this.getRow(i).getData(header.id).getValue(); 
		        				       var intervalo=header.getIntervalo();
		    	                       var valorreal=valordato-header.limit0;
		    	                       var porc=parseInt((valorreal*100)/intervalo); 	    	                       
		    	                       LMJQ(tddato).html("<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='"+porc+"' aria-valuemin='"+header.limit0+"' aria-valuemax='"+intervalo+"' style='width:"+porc+"%;'><span class='sr-only'>"+porc+"%</span></div></div>");
		    	                    break;		        				
		        				case '2':
		        				case '10':
		        				case '11':
		        				case '16':
		        					var valordato=this.getRow(i).getData(header.id+'m').getValue();    	        					
		        					LMJQ(tddato).html(valordato.replace(/#/g, '<br/>'));
		        					break; 
		        				case '15':		        					
		        					var rate_wrapper=LMJQ('<div>',{'class':'lm_rate_wrapper'});
		        					var valtemp=0
		        					if (this.getRow(i).getData(header.id)!=null)
		        						valtemp=this.getRow(i).getData(header.id).getValue();
		        					if(valtemp.length<=0) valtemp=0;
		        					var rate=LMJQ('<div>',{'id':'l'+header.idlisting+'r'+this.getRow(i).idrecord+'f'+header.id,
		        						'class':'rateit',
		        						'data-rateit-value':valtemp,
		        						'data-rateit-ispreset':'true',
		        						'data-idlisting':header.idlisting,		        						
		        						'data-idfield':header.id,
		        						'data-idrecord':this.getRow(i).idrecord,
		        						'title':getLangTradsLM('LM_RATE')});
		        					rate.css('width','100px');
		        					var rate_text=LMJQ('<div>',{'class':'lm_rate_text'})
		        					LMJQ(rate_text).html(getNumber(valtemp,null,null).toFixed(2)+' / 5');
		        					LMJQ(rate_wrapper).append(rate_text);
		        					LMJQ(rate_wrapper).append(rate);		        					
		        					LMJQ(tddato).append(rate_wrapper);
		        					break;
		        				case '17':
		        					var pp_var=this.getRow(i).getData(header.id).getValue();
		        					if (pp_var.length>0){
			        					var pp_data=LMJQ('<div>',{ 
			        							'class':'btn paypal_btn',		        							
			        							'pp_var': pp_var});
			        					LMJQ(pp_data).html(getLangTradsLM('LM_PAYPAL_BUTTON'));
			        					LMJQ(tddato).append(pp_data);
		        					}
		        					break;
		        				case '18':
		        					LMJQ(tddato).addClass('lmlinklist');
		        					LMJQ(tddato).attr('seed',this.seed);
		        					LMJQ(tddato).attr('headerid',header.id);
		        					LMJQ(tddato).html(this.getRow(i).getData(header.id).getValue());
    	        				default:
    	        					LMJQ(tddato).html(this.getRow(i).getData(header.id).getValue());
    	        					break;
    	        			}    	        			
    	        		 
    	        		}
    	        		LMJQ(trdato).append(tddato);
    	        	}else if(header.type=='6'&& this.getRow(i).getData('_struser')!=null){ // non-visible user
    	        		// User    	        		
    	        		uservalue=this.getRow(i).getData(header.id).getValue();
    					if (LMJQ.type(uservalue)==='null')uservalue=0;    					
    	        	}
    	        }
    	        // Show only user data    	        
        		if (this.options.access_type==1 && (this.options.uid!=uservalue && uservalue!=0)) continue;
        		
    	        var imgedit = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-pencil',                         
                    'title':getLangTradsLM('LM_EDIT')+' :: '+getLangTradsLM('LM_EDIT_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'imgedit'
                    });                      
    	        var imgdelete = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-remove-circle',                         
                    'title':getLangTradsLM('LM_DELETE')+' :: '+getLangTradsLM('LM_DELETE_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'imgdelete'
                    });
    	        var imgdetail = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-list-alt',                         
                    'title':getLangTradsLM('LM_DETAIL')+' :: '+getLangTradsLM('LM_DETAIL_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'imgdetail'
                    });
    	        var imgdetailpdf = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-save',                         
                    'title':getLangTradsLM('LM_DETAILPDF')+' :: '+getLangTradsLM('LM_DETAILPDF_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'imgdetailpdf'
                    });    	 
    	        var imgdetailrtf = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-save',                         
                    'title':getLangTradsLM('LM_DETAILRTF')+' :: '+getLangTradsLM('LM_DETAILRTF_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'imgdetailrtf'
                    });    	 
    	        var chkbulk=LMJQ('<input>',{'type':'checkbox','class':'lm_selectable lm_hasTip2 ',                         
                    'title':getLangTradsLM('LM_SELECTBULK')+' :: '+getLangTradsLM('LM_SELECTBULK_TOOLTIP'),
                    id:this.getRow(i).idrecord,'name':'chkbulk'
                    });    	        
    	        var tdopt= LMJQ('<td>',{'class':'lm_lasttd lm_lasttdoff'});
    	        var identify =LMJQ('<input>',{'type':'hidden','name':'identify','value':this.getRow(i).idrecord});
    	        var span=LMJQ('<div>',{'class':'lm_lasttd_delete'});    	        
    	        var spanedit=LMJQ('<div>',{'class':'lm_lasttd_edit'});
    	        var spandetail=LMJQ('<div>',{'class':'lm_lasttd_detail'});
    	        var spandetailpdf=LMJQ('<div>',{'class':'lm_lasttd_detailpdf'});
    	        var spandetailrtf=LMJQ('<div>',{'class':'lm_lasttd_detailrtf'});
    	        var spanwrapper=LMJQ('<div>',{'class':'lm_lasttd_wrapper'});
    	        var spanbulk=LMJQ('<div>',{'class':'lm_lasttd_bulk'});
    	        LMJQ(spanedit).append(imgedit);
    	        
    	        var oneatleast=false;
    	        
    	        var isEdit=false;
    	        if (this.options.access_type==2){
    	        	if(this.options.uid==uservalue){ 
    	        		//LMJQ(tdopt).append(spanwrapper);
    	        		isEdit=true;
    	        	}     	        	
    	        } else {
    	        	//LMJQ(tdopt).append(spanwrapper);
    	        	isEdit=true;
    	        }
    	        
    	        LMJQ(tdopt).append(spanwrapper);
    	        
    	        // Edit
    	        if (!this.options.viewonly && this.acl.isGlobalEdit() && isEdit){
    	        	LMJQ(spanwrapper).append(spanedit);
    	        	oneatleast=true;
    	        }   
    	        
    	        // Detail
    	        if (this.acl.isDetail()) {
    	        	LMJQ(spandetail).append(imgdetail);
    	        	LMJQ(spanwrapper).append(spandetail);
    	        	oneatleast=true;
    	        }
    	     // Detail
    	        if (this.acl.isDetailpdf()) {
    	        	LMJQ(spandetailpdf).append(imgdetailpdf);
    	        	LMJQ(spanwrapper).append(spandetailpdf);
    	        	oneatleast=true;
    	        }
    	     // Detail
    	        if (this.acl.isDetailrtf()) {
    	        	LMJQ(spandetailrtf).append(imgdetailrtf);
    	        	LMJQ(spanwrapper).append(spandetailrtf);
    	        	oneatleast=true;
    	        }
    	        // Delete
    	        if (!this.options.viewonly && this.acl.isDelete() && isEdit) {
    	        	LMJQ(span).append(imgdelete);
    	        	LMJQ(spanwrapper).append(span);
    	        	oneatleast=true;
    	        }
    	        // Bulk
    	        if (!this.options.viewonly && this.isBulkSelected() && this.acl.isBulk() && isEdit){
    	        	LMJQ(spanbulk).append(chkbulk);
    	        	LMJQ(spanwrapper).append(spanbulk);
    	        	oneatleast=true;
    	        }
    	        
    	        if (!this.options.viewonly && oneatleast){
    	        	if (this.tool_column_position==0)
    	        		LMJQ(trdato).append(tdopt);
    	        	else    	        		
    	        		LMJQ(trdato).find('td').eq(this.tool_column_position-1).before(tdopt);
    	        }
    	        LMJQ(trdato).append(identify);
    	        LMJQ(tbody).append(trdato);    	     
    	}
    	return tbody;
    };
    this.printTotales=function(){
    	if (this.options.showtotals && this.headers.length>0){
    		var trbodyt=LMJQ('<tr>',{'class':'lm_trtotal'});
    		for(var i=0;i<this.headers.length;i++){
    			var header=this.headers[i];
    			if(header.isVisible()){
    				var tddatot= LMJQ('<th>',{'class':'lm_column_'+i+' '+header.styleclass});
                    if(this.totalsData[header.id]!=undefined && this.totalsData[header.id]!=0){
                    	tddatot.html(this.totalsData[header.id]);
                    }
                    LMJQ(trbodyt).append(tddatot);
    			}
    		}
    		var tddatoult= LMJQ('<th>',{id:'totalline'});
    		tddatoult.html(getLangTradsLM('LM_TOTAL'));
    		if (!this.options.viewonly){
	        	if (this.tool_column_position==0)
	        		LMJQ(trbodyt).append(tddatoult);
	        	else    	        		
	        		LMJQ(trbodyt).find('th').eq(this.tool_column_position-1).before(tddatoult);
	        }             
            return trbodyt;
    	}
    	return '';
    };
    this.deleteData=function(){
    	this.data.length=0;
    };    
    this.setData= function(jsondata){
    	this.deleteData();
    	var keys=jsondata.k;
    	var values=jsondata.v;
    	if (LMJQ.type(keys)==='undefined' || keys.length<=0) {
    		// TODO message of NO DATA
    		return;
    	}
    	for(var k=0;k<keys.length;k++){
    		var jsondata_elems=keys[k];
    		var row_temp = new LmRow();
    		row_temp.setRecordId(keys[k]);
    		var data_elems=values[k];
    		for (var elems in data_elems){
    			var dataelem=data_elems[elems];
    			var data_temp = new LmData({id:elems,value:dataelem});
    			row_temp.setData(data_temp);
    		}   
    		this.addData(row_temp);
    	}    	
    };   
    this.getRow=function(i){
    	return this.data[i];
    };
    this.getRowById= function(id){
    	for(var i=0;i<this.data.length;i++){
    		if (this.data[i].idrecord==id)
    			return this.data[i]; 
    	}
    };
    this.sort= function(headerId){    	
    	var header = this.getHeaderById(headerId);
    	this.data=lm_sort(header, this.data,this.options.dateformat);
    	this.setHeaderOrderToogle(headerId);
    };
    this.sortOrder= function(headerId,isDesc){
    	var header = this.getHeaderById(headerId);    	
    	this.data=lm_sort_order(header, this.data,isDesc,this.options.dateformat);    	
    };
    this.setContentInDetail= function(contentId){
    	var ret = {};
    	var values=this.getRowById(contentId);
    	for(var i=0;i<this.getHeadersLength();i++){
    		var elem=this.getHeader(i);
    		if ((LMJQ.type(values.getData(elem.id))==='null' || LMJQ.type(values.getData(elem.id).getValue())==='null') && elem.type!='15') continue;
    		var valTemp=""; 
    		if (values.getData(elem.id)!=null || elem.type=='15'){
    			switch(elem.type){
    				case '1':
    					// Date
    					var valordato=values.getData(elem.id).getValue();
    					var randid=Math.floor((Math.random() * (9999999)) + 100000);
    					var newTmpElem='<input type="text" id="r_'+randid+'"></div>';
    					LMJQ('#lmdatepicker_calcs').append(newTmpElem);
    					LMJQ('#r_'+randid).datepicker({dateFormat: this.getDateFormat()});    	        					
    					LMJQ('#r_'+randid).val(valordato);
    					//console.log(valordato+'    '+this.getDateFormat());
    					var jsdate=LMJQ('#r_'+randid).datepicker("getDate");
    					//console.log(jsdate);
    					valordato=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);
    					//console.log(valordato);
    					//console.log('---------------');
    					valTemp=valordato;
    					LMJQ('#r_'+randid).remove();
    					break;    	        			
        			case '6':
    					// User
    					var dataTempUser=values.getData('_struser').getValue();
        				if (LMJQ.type(dataTempUser)!=='null')
        					valTemp=dataTempUser;
    					break;
    				case '7':
    					var valordato=values.getData(elem.id).getValue();
    					valTemp=valordato.replace(/\n\r?/g, '<br/>');
        				break;
    				case '8':
    					// Progress
    				   var valordato=values.getData(elem.id).getValue(); 
	                       var intervalo=elem.getIntervalo();
	                       var valorreal=valordato-elem.limit0;
	                       var porc=parseInt((valorreal*100)/intervalo); 	    	                       
	                       valTemp="<div class='lm_progress'><div class='lm_progresson' style='width:"+porc+"px'>"+porc+"%</div></div>";
    					break;
    				case '2':
    				case '10':
    				case '11':
    				case '16':
    					var valordato=values.getData(elem.id+'m').getValue();    	        					
    					valTemp=valordato.replace(/#/g, '<br/>');
    					break; 
    				case '15':
    					var rate_wrapper=LMJQ('<div>',{'class':'lm_rate_wrapper'});
    					var valtemp=0
    					if (values.getData(elem.id)!=null)
    						valtemp=values.getData(elem.id).getValue();
    					var rate=LMJQ('<div>',{'id':'l'+elem.idlisting+'r'+values.idrecord+'f'+elem.id+'_detail',
    						'class':'rateit',
    						'data-rateit-value':valtemp,
    						'data-rateit-ispreset':'true',
    						'data-idlisting':elem.idlisting,
    						'data-idfield':elem.id,
    						'data-rateit-readonly':true,
    						'data-idrecord':values.idrecord,
    						'title':getLangTradsLM('LM_RATE')});
    					LMJQ(rate).css('width','100px');
    					/*var rate_text=new Element('div',{'class':'lm_rate_text'})
    					$(rate_text).set('html',getNumber(valtemp,null,null).toFixed(2)+' / 5');
    					rate_wrapper.adopt(rate_text);*/
    					LMJQ(rate_wrapper).append(rate);		        					
    					valTemp=LMJQ(rate_wrapper).html();
    					break;
    				default:
    					valTemp=values.getData(elem.id).getValue();
    					break;
    			}  
    			ret[elem.innername]=valTemp;
    		} 
    	}
    	return ret;
    };
    this.aclForm=function(form,type){
    	// Resetear all disabled except today
    	LMJQ(form).find('select, input,textarea').each(function(index,elem){
    		if (LMJQ(elem).attr('lmtype')!='today'){
    			LMJQ(elem).removeAttr('disabled');
    		}
    	});
    	var elements=LMJQ(form).find('select, input,textarea');
    	switch(type){
    	//case 'add':
    	case 'edit':    	
    		for(var i=0;i<elements.length;i++){
    			var tmpid=LMJQ(elements[i]).attr('id');
    			if (tmpid!=null&&tmpid!=''&&tmpid.startsWith('fld_')){
    				var tmpid=(replaceAll(tmpid, 'fld_', '').split('_'))[0];    				
        			if(!this.acl.isEdit(tmpid)){
        				//LMJQ(elements[i]).attr('disabled','disabled');
        				LMJQ(elements[i]).prop('disabled','disabled');
            		}	
        			if(LMJQ(elements[i]).prop('lmtype') && LMJQ(elements[i]).attr('lmtype')=='today'){
    					//LMJQ(elements[i]).attr('disabled','disabled');
    					LMJQ(elements[i]).prop('disabled','disabled');
    				}
    			}    			
    		}
    		break;    	
    	}
    };
    this.setContentInForm= function(form, contentId,seed){
    	//console.log('SetContentInForm');
    	var values=this.getRowById(contentId);
    	// Reset rates 
    	LMJQ('div[id=_rate]').each(function(index){
    		LMJQ(this).rateit('value',0);
    		LMJQ(this).rateit('idrecord',values.idrecord);    		
    	});    	
    	for(var i=0;i<this.getHeadersLength();i++){
    		var elem=this.getHeader(i);
    		if (LMJQ.type(values.getData(elem.id))==='null' || LMJQ.type(values.getData(elem.id).getValue())==='null'){
    			if (elem.type=='12'){    				
					/*var newdate=new Date();
					val_tmp=newdate.format(this.options.dateformat);*/					
					LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormatView()});    	        					
					var jsdate=new Date();
					val_tmp=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);	 
					LMJQ(form).find('input[name=fld_'+elem.id+']').val(val_tmp);
				}
    			/*if (elem.type==9){
    				var editor=LMJQ('#fld_'+elem.id).cleditor();
    				setTimeout(function(){
						LMJQ(editor)[0].updateFrame();
						LMJQ(editor)[0].refresh();
						//LMJQ(editor)[0].focus();
					},500);
    			}*/
    			continue;
    		}
    		//console.log('Header:'+elem.type+':'+LMJQ(form).find('[name=fld_'+elem.id+']'));
    		//if(elem.type!=6){    			
    			if (LMJQ(form).find('[name=fld_'+elem.id+']').length>0){
    				fieldTag=LMJQ(form).find('[name=fld_'+elem.id+']').prop('tagName');
    					switch(fieldTag){
	    					case 'SELECT':
	    						if(elem.type==16){ //Multiple
	    							var arrValues=values.getData(elem.id).getValue().split("#");
	    							LMJQ(form).find('select[name=fld_'+elem.id+']').find('option').each(function(index,opt){
		    							  if (arrValues.indexOf(LMJQ(opt).val())!=-1){
		    								  //LMJQ(opt).attr('selected','selected');
		    								  LMJQ(opt).prop('selected','selected');
		    					          }
		    					        });
	    						} else {	
	    							//console.log('Select fld_'+elem.id+':'+values.getData(elem.id).getValue());
	    							LMJQ(form).find('select[name=fld_'+elem.id+']').find('option').each(function(index,opt){
		    							  if (LMJQ(opt).val()==values.getData(elem.id).getValue()){
		    								  //console.log('Select value found:'+LMJQ(opt).val());
	    									//LMJQ(opt).attr('selected','selected');
		    								  LMJQ(opt).prop('selected','selected');
		    					             return true;
		    					          }
		    					        });
	    						}
	    						LMJQ(form).find('select[name=fld_'+elem.id+']').change();
	    						break;
	    					case 'TEXTAREA':	    						
	    						LMJQ(form).find('textarea[name=fld_'+elem.id+']').val(values.getData(elem.id).getValue());	    						
	    				          if (elem.type==9){
	    				        	  /*var data_replaced=replaceAll(values.getData(elem.id).getValue(),'"','&quot;');
	    				        	  data_replaced=replaceAll(data_replaced,"'",'&#39;').replace(/(\r\n|\n|\r)/gm,"");	    				        	  
	    				        	  Browser.exec("setContentEditor"+seed+"('"+'fld_'+elem.id+"','"+data_replaced+"')");*/
	    				        	  setContentEditor('fld_'+elem.id,values.getData(elem.id).getValue());	    				        	  
	    				          }
	    						break;
	    					case 'INPUT':
	    						var inelem=LMJQ(form).attr('input[name=fld_'+elem.id+']');
	    						var inelemcheck=LMJQ(form).attr('input[name=check_fld_'+elem.id+']');
	    						//var type=inelem.getProperty('type');
	    						if(inelemcheck!=null){
	    							if (getLangTradsLM('LM_YES_VALUE')==values.getData(elem.id).getValue()){	    								
	    								inelemcheck.setProperty('checked','checked');
	    								inelem.val(getLangTradsLM('LM_YES_VALUE'));
	    							} else {
	    								inelem.val(getLangTradsLM('LM_NO_VALUE'));
	    							}	    								
	    						} else {
	    							var val_tmp=values.getData(elem.id).getValue();
	    							if (elem.type=='12'&& (val_tmp.length<=0 || /modify/i.test(elem.styleclass))){
			    						/*var newdate=new Date();
			    						val_tmp=newdate.toDateString();*/
	    								LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormatView()});    	        					
	    								var jsdate=new Date();
	    								val_tmp=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);
	    							}
	    							LMJQ(form).find('input[name=fld_'+elem.id+']').val(val_tmp);
	    							if(elem.type=="14"){ // Slider	    								
	    								if (val_tmp.length>0)
	    									LMJQ('#fld_'+elem.id+'_slider').slider('option','value',val_tmp);
	    								else
	    									LMJQ('#fld_'+elem.id+'_slider').slider('option','value',LMJQ('#fld_'+elem.id+'_slider').slider('option','min'));
	    							}else if(elem.type=="15"){ // Rate	    									    								
	    								LMJQ('#fld_'+elem.id+'_rate').rateit('value',values.getData(elem.id).getValue());
	    								LMJQ('#fld_'+elem.id+'_rate').attr('data-rateit-value',values.getData(elem.id).getValue());
	    								LMJQ('#fld_'+elem.id+'_rate').rateit('idrecord',values.idrecord);
	    								LMJQ('#fld_'+elem.id+'_rate').attr('data-idrecord',values.idrecord);	    								
	    							}else if(elem.type=="10"){ // Radio	    							
	    								LMJQ(form).find('input[rad_name=fld_'+elem.id+'][value='+val_tmp+']').prop('checked',true);
	    								LMJQ(form).find('input[rad_name=fld_'+elem.id+'][value='+val_tmp+']').change();
	    							}
	    						}		    						
	    						break;	    		
	    					default:	    						
	    						LMJQ(form).find('input[name=fld_'+elem.id+']').val(values.getData(elem.id).getValue());
	    						break;
	    				}    				
    			}
    			// Multivalue option
    			if (LMJQ(form).find('[name="fld_'+elem.id+'[]"]')!=undefined){
    				LMJQ(form).find('[name="fld_'+elem.id+'[]"]').each(function(index,checkbox){
    					var arr_values=values.getData(elem.id).getValue().split('#');
    					var check_value_temp=LMJQ(checkbox).val();
    					//if (arr_values.contains(check_value_temp))
    					if (LMJQ.inArray(check_value_temp,arr_values)>-1){
    						LMJQ(checkbox).prop('checked',true);
    					}else{
    						LMJQ(checkbox).prop('checked',false);
    					}
    					LMJQ(checkbox).change();
    				});    			
    			}
    		//}
    	}
    	LMJQ(form).find('input[name=idrecord]').val(contentId); 
    };
    this.setDefaultContentInForm= function(form, seed){    	    	 
    	for(var i=0;i<this.getHeadersLength();i++){    		
    		var elem=this.getHeader(i);
    		var value=elem.getDefaultValue();
    		if (LMJQ.type(value)==='undefined') continue;
    		//if(elem.type!=6){    			
    			if (LMJQ(form).find('#fld_'+elem.id).length>0){
    				fieldTag=LMJQ(form).find('#fld_'+elem.id).prop('tagName');
    				switch(fieldTag){
    					case 'SELECT':
    						LMJQ(form).find('select[name=fld_'+elem.id+']').find('option').each(function(index,opt){
    							var value_tmp=value.split("#");
    							for(var k=0;k<value_tmp.length;k++){
    							  if (LMJQ(opt).attr('mv_option')==value_tmp[k]){
    								  //LMJQ(opt).attr('selected','selected');
    								  LMJQ(opt).prop('selected','selected');
    					             return true;
    					          }
    							}
    					    });
    						
    						break;
    					case 'TEXTAREA':
    						LMJQ(form).find('textarea[name=fld_'+elem.id+']').text(value);
    				          if (elem.type==9){
    				        	  setContentEditor('fld_'+elem.id,value);
    				        	  /*var data_replaced=replaceAll(value,'"','&quot;');
    				        	  data_replaced=replaceAll(data_replaced,"'",'&#39;');    				        	  
    				        	  */
    				        	  //Browser.exec("setContentEditor"+seed+"('"+'fld_'+elem.id+"','"+data_replaced+"')");
    				          }
    						break;
    					default:
    						if (elem.type=='12'){ // Today
    							LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormat()});    	        					
	    						var jsdate=LMJQ('#lmdatepicker_calcs').datepicker( "getDate" );
	    						value=LMJQ.datepicker.formatDate(this.getDateFormat(),jsdate);	    						
							} else if(elem.type=="14"){ // Slider	    								
								if (value.length>0)
									LMJQ('#fld_'+elem.id+'_slider').slider('option','value',value);
								else
									LMJQ('#fld_'+elem.id+'_slider').slider('option','value',LMJQ('#fld_'+elem.id+'_slider').slider('option','min'));
							} 
    						LMJQ(form).find('input[name=fld_'+elem.id+']').val(value);
    						break;
    				}    				
    			} else if (LMJQ(form).find('input[name="fld_'+elem.id+'[]"]')!=undefined){    				
    				if(elem.type=="11"){ // Checkbox	 
    					var value_tmp=value.split("#");
    					for(var k=0;k<value_tmp.length;k++){
    						LMJQ(form).find('input[name="fld_'+elem.id+'[]"][mv_check='+value_tmp[k]+']').prop('checked',true);
    						LMJQ(form).find('input[name="fld_'+elem.id+'[]"][mv_check='+value_tmp[k]+']').change();
    					}
    				}
    			}
    			if (LMJQ(form).find('input[name=rfld_'+elem.id+']')!=undefined){    				
    				if (elem.type=='10'){ // Radiobutton    					
    					LMJQ(form).find('input[rad_name=fld_'+elem.id+'][mv_radio='+value+']').prop('checked',true);
    					LMJQ(form).find('input[rad_name=fld_'+elem.id+'][mv_radio='+value+']').change();
					}
    			}
    			if (LMJQ(form).find('input[name=check_fld_'+elem.id+']')!=undefined){    				
    				if (elem.type=='3'){ // Yes/No
    					LMJQ(form).find('input[name=check_fld_'+elem.id+'][value='+value+']').prop('checked',true);
    					LMJQ(form).find('input[name=check_fld_'+elem.id+'][value='+value+']').change();
					}
    			}
    		//}
    	} 
    	LMJQ(form).find('input[name=idrecord]').val('');
    	var newdate=new Date();
		//value=newdate.format(this.options.dateformat);
    	//value=newdate.format(this.options.dateformat);
    	LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormatView()});    	        					
		var jsdate=new Date();
		val_tmp=LMJQ.datepicker.formatDate(this.getDateFormatView(),jsdate);	 
		LMJQ('input[lmtype="today"]').val(val_tmp);
    	/*LMJQ(form).find('input[lmtype="today"]').datepicker( "setDate", newdate );
    	LMJQ('#lmdatepicker_calcs').datepicker({dateFormat: this.getDateFormat(),defaultDate: newdate,}).val();
    	var jsdate=LMJQ.datepicker.formatDate(this.getDateFormat(), newdate)
		LMJQ(form).find('input[data-type="12"]').val(jsdate);*/
    };  
    this.printDataCard= function(isShop){
    	var containerWrapper=LMJQ('<div/>',{'class':'row'});
    	var csscols=boot_column_conversor(this.list_columns);
    	// Search for large or normal content
		var largecontent=false;
		var largecontentclass='';
		var uservalue=0;
    	for(var h=0; h<this.getHeadersLength();h++){
    		var header=this.getHeader(h);    	        	
        	if(header.isVisible() && header.isCardViewLarge()){
        		largecontent=true;
        		break;
        	}
    	}
    	if (largecontent) largecontentclass='largecontent';
    	var largecontentclassthumbnail='';
    	if (largecontent) largecontentclassthumbnail='largecontentthumbnail';
    	for(var i=0;i<this.data.length;i++){
	    	var itemWrapper=LMJQ('<div/>',{'class':csscols});
	    	var item=LMJQ('<div/>',{'class':'thumbnail '+largecontentclassthumbnail});
	    	var itemContent=LMJQ('<div/>',{'class':'caption row'});
	    	
	    	
	    	// Card Action Buttons
    		// Show only user data (Condition)	        		
    		if (this.options.access_type==1 && (this.options.uid!=uservalue && uservalue!=0)) continue;
    		
    		var idbtn=Math.floor((Math.random()*100000)+1);
    		var card_content_block=LMJQ('<div class="lm-card-button"></div>'); 
    		var btn_nav=LMJQ('<nav/>',{'class':'navbar navbar-default lm-navcard','role':'navigation'});
    		var btn_nav_wrapper=LMJQ('<div/>',{'class':'container-fluid'});
    		// Header
    		var btn_nav_header=LMJQ('<div/>',{'class':'navbar-header'});
    		var btn_nav_header_button=LMJQ('<button/>',{'type':'button','class':'navbar-toggle lm-uniform-height','data-toggle':'collapse','data-target':'#'+idbtn});
    		var btn_nav_header_button_content=LMJQ('<span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>');
    		LMJQ(btn_nav_header_button).append(btn_nav_header_button_content);
    		LMJQ(btn_nav_header).append(btn_nav_header_button);
    		LMJQ(btn_nav_wrapper).append(btn_nav_header);
    		// Content
    		var btn_nav_content=LMJQ('<div/>',{'class':'collapse navbar-collapse','id':idbtn});
    		var btn_nav_content_list=LMJQ('<ul/>',{'class':'nav navbar-nav'});	        		
    		var btn_nav_content_list_li=LMJQ('<li/>',{'class':'col-xs-12 btn-group'});
    		// Buttons    		
	        var imgedit = LMJQ('<button>',{'class':'btn btn-default btn-lg btn-link','type':'button','name':'imgedit','id':this.getRow(i).idrecord});
	        var imgedit_inner = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-pencil',                         
                'title':getLangTradsLM('LM_EDIT')+' :: '+getLangTradsLM('LM_EDIT_TOOLTIP')});
	        //LMJQ(imgedit).append(imgedit_inner,' '+getLangTradsLM('LM_EDIT'));
	        LMJQ(imgedit).append(imgedit_inner);
	        var imgdelete = LMJQ('<button>',{'class':'btn btn-default btn-lg btn-link','type':'button','name':'imgdelete','id':this.getRow(i).idrecord});
	        var imgdelete_inner = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-remove-circle',                         
                'title':getLangTradsLM('LM_DELETE')+' :: '+getLangTradsLM('LM_DELETE_TOOLTIP')});
	        //LMJQ(imgdelete).append(imgdelete_inner,' '+getLangTradsLM('LM_DELETE'));
	        LMJQ(imgdelete).append(imgdelete_inner);
	        var imgdetail = LMJQ('<button>',{'class':'btn btn-default btn-lg btn-link','type':'button','name':'imgdetail','id':this.getRow(i).idrecord});
	        var imgdetail_inner = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-list-alt',                         
                'title':getLangTradsLM('LM_DETAIL')+' :: '+getLangTradsLM('LM_DETAIL_TOOLTIP')});
	        //LMJQ(imgdetail).append(imgdetail_inner,' '+getLangTradsLM('LM_DETAIL'));
	      LMJQ(imgdetail).append(imgdetail_inner);
	        var imgdetailpdf = LMJQ('<button>',{'class':'btn btn-default btn-lg btn-link','type':'button','name':'imgdetailpdf','id':this.getRow(i).idrecord});
	        var imgdetailpdf_inner = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-save',                         
                'title':getLangTradsLM('LM_DETAILPDF')+' :: '+getLangTradsLM('LM_DETAILPDF_TOOLTIP')});
	      LMJQ(imgdetailpdf).append(imgdetailpdf_inner);
	      
	      var imgdetailrtf = LMJQ('<button>',{'class':'btn btn-default btn-lg btn-link','type':'button','name':'imgdetailrtf','id':this.getRow(i).idrecord});
	        var imgdetailrtf_inner = LMJQ('<span>',{'class':'lm_selectable lm_hasTip2 glyphicon glyphicon-save',                         
              'title':getLangTradsLM('LM_DETAILRTF')+' :: '+getLangTradsLM('LM_DETAILRTF_TOOLTIP')});
	      LMJQ(imgdetailrtf).append(imgdetailrtf_inner);
	        //var tdopt= LMJQ('<td>',{'class':'lm_lasttd lm_lasttdoff'});
	        var identify =LMJQ('<input>',{'type':'hidden','name':'identify','value':this.getRow(i).idrecord});
	        /*var spandelete=LMJQ('<li>',{'class':'lm_lasttd_delete col-xs-4'});    	        
	        var spanedit=LMJQ('<li>',{'class':'lm_lasttd_edit col-xs-4'});
	        var spandetail=LMJQ('<li>',{'class':'lm_lasttd_detail col-xs-4'});*/
	        //var spanwrapper=LMJQ('<div>',{'class':'lm_lasttd_wrapper'});
	        var oneatleast=false;
	        
	        var isEdit=false;	                
	        if (this.options.access_type==2){
	        	if(this.options.uid==uservalue){ 
	        		//LMJQ(btn_nav_content_list_li).append(imgedit);
	        		isEdit=true;
	        	}	
	        } else {
	        	//LMJQ(btn_nav_content_list_li).append(imgedit);
	        	isEdit=true;
	        }
	        //LMJQ(btn_nav_content_list_li).append(imgedit);
	        
	        // Edit
	        //LMJQ(spanedit).append(imgedit);
	        if (!this.options.viewonly && this.acl.isGlobalEdit() && isEdit){
	        	LMJQ(btn_nav_content_list_li).append(imgedit);
	        	oneatleast=true;
	        }    	
	        // Detail
	        if (this.acl.isDetail()) {
	        	//LMJQ(spandetail).append(imgdetail);
	        	LMJQ(btn_nav_content_list_li).append(imgdetail);
	        	oneatleast=true;
	        }
	        // Detail PDF
	        if (this.acl.isDetailpdf()) {
	        	//LMJQ(spandetail).append(imgdetail);
	        	LMJQ(btn_nav_content_list_li).append(imgdetailpdf);
	        	oneatleast=true;
	        }
	        // Detail PDF
	        if (this.acl.isDetailrtf()) {
	        	//LMJQ(spandetail).append(imgdetail);
	        	LMJQ(btn_nav_content_list_li).append(imgdetailrtf);
	        	oneatleast=true;
	        }
	        // Delete
	        if (!this.options.viewonly && this.acl.isDelete() && isEdit) {
	        	//LMJQ(spandelete).append(imgdelete);
	        	LMJQ(btn_nav_content_list_li).append(imgdelete); 
	        	oneatleast=true;
	        }
	        
	        LMJQ(btn_nav_content_list).append(btn_nav_content_list_li);
	        if (!this.options.viewonly && oneatleast) LMJQ(btn_nav_content).append(btn_nav_content_list);
    		
    		//LMJQ(btn_nav_content).append(btn_nav_content_list);
    		LMJQ(btn_nav_wrapper).append(btn_nav_content);
    		LMJQ(btn_nav).append(btn_nav_wrapper);
    		LMJQ(card_content_block).append(btn_nav);
    		LMJQ(itemContent).append(card_content_block);
    		
	    	// Card Content	    	
	    	for(var h=0; h<this.getHeadersLength();h++){
	        	var header=this.getHeader(h);    	        	
	        	if(header.isVisible()){
	        		var largecontentclassdata='';
	        		var cursorlarge='';
	        		var largecontentclassblock='lmcardcontentblock';
	        		if (largecontentclass && header.isCardViewLarge()){
	        			largecontentclassdata=largecontentclass;
	        			largecontentclassblock='lmcardcontentblock lmcardcontentblocklarge islarge';	        			
	        		}	        		
	        		if (largecontentclass){
	        			cursorlarge=' style="cursor:pointer" ';
	        		}
	        		var card_content_block=LMJQ('<div class="'+largecontentclassblock+' '+header.styleclass+'" '+cursorlarge+'></div>'); 
	        		var card_data_name=LMJQ('<div class="lmcarditem_name '+this.list_name_class+' '+largecontentclass+'">'+header.name+'</div>');
	        		if (header.type!='17') LMJQ(card_content_block).append(card_data_name);	        		
	        		var card_data=LMJQ('<div class="lmcarditem_data '+this.list_value_class+' '+largecontentclassdata+'"></div>');    
	        		if (header.type=='17') card_data=LMJQ('<div class="lmcarditem_data col-md-12" style="text-align:center;margin-top:4px;"></div>');
	        		if (this.getRow(i).getData(header.id)!=null || header.type=='15'){
	        			switch(header.type){    	        				
		        			case '4':
		        				if (header.readmore=='1'){
		        					LMJQ(card_data).addClass('expandable');
		        					LMJQ(card_data).attr('rm_word',header.readmore_word_count);
		        				}
		        				if(header.styleclass.indexOf('linkurl') !=-1) {	                                    
                                    LMJQ(card_data).html("<a href='"+this.getRow(i).getData(header.id).getValue()+"'>"+this.getRow(i).getData(header.id).getValue()+"</a>");
                                }                                   
                                if(header.styleclass.indexOf('linkmail') !=-1) {                                   
                                    LMJQ(card_data).html("<a href='mailto:"+this.getRow(i).getData(header.id).getValue()+"'>"+this.getRow(i).getData(header.id).getValue()+"</a>");
                                }
		        				LMJQ(card_data).html(this.getRow(i).getData(header.id).getValue());
		        				break;
		        			case '6':
	        					// User
	        					var dataTempUser=this.getRow(i).getData('_struser').getValue();
		        				if (LMJQ.type(dataTempUser)!==null){
		        					LMJQ(card_data).html(dataTempUser);
		        				}	    	        					
	        					uservalue=this.getRow(i).getData(header.id).getValue();
	        					if (LMJQ.type(uservalue)===null)uservalue=0;
	        					break;
	        				case '7':
	        					var valordato=this.getRow(i).getData(header.id).getValue();
	        					LMJQ(card_data).html(valordato.replace(/\n\r?/g, '<br />'));
		        				break;
	        				case '8':
	        					// Progress
	        				   var valordato=this.getRow(i).getData(header.id).getValue(); 		        				   		    	                   
	    	                       var intervalo=header.getIntervalo();
	    	                       var valorreal=valordato-header.limit0;
	    	                       var porc=parseInt((valorreal*100)/intervalo); 	    	                       
	    	                       LMJQ(card_data).html("<div class='lm_progress'><div class='lm_progresson' prc='"+valorreal+"' inte='"+intervalo+"'>"+porc+"%</div></div>");
	        					break;		        				
	        				case '2':
	        				case '10':
	        				case '11':
	        				case '16':
	        					var valordato=this.getRow(i).getData(header.id+'m').getValue();    	        					
	        					LMJQ(card_data).html(valordato.replace(/#/g, '<br/>'));
	        					break; 
	        				case '15':		        					
	        					var rate_wrapper=LMJQ('<div>',{'class':'lm_rate_wrapper'});
	        					var valtemp=0
	        					if (this.getRow(i).getData(header.id)!=null)
	        						valtemp=this.getRow(i).getData(header.id).getValue();
	        					if(valtemp.length<=0) valtemp=0;
	        					var rate=LMJQ('<div>',{'id':'l'+header.idlisting+'r'+this.getRow(i).idrecord+'f'+header.id,
	        						'class':'rateit',
	        						'data-rateit-value':valtemp,
	        						'data-rateit-ispreset':'true',
	        						'data-idlisting':header.idlisting,		        						
	        						'data-idfield':header.id,
	        						'data-idrecord':this.getRow(i).idrecord,
	        						'title':getLangTradsLM('LM_RATE')});
	        					LMJQ(rate).css('width','75px');
	        					var rate_text=LMJQ('<div>',{'class':'lm_rate_text'})
	        					LMJQ(rate_text).html(getNumber(valtemp,null,null).toFixed(2)+' / 5');
	        					LMJQ(rate_wrapper).append(rate_text);
	        					LMJQ(rate_wrapper).append(rate);		        					
	        					LMJQ(card_data).append(rate_wrapper);
	        					break;
	        				case '17':
	        					var pp_var=this.getRow(i).getData(header.id).getValue();
	        					if (pp_var.length>0){
	        						var pp_data=LMJQ('<button>',{'type':'button','class':'btn paypal_btn btn-default','pp_var': pp_var});
		        					var pp_data_inner=LMJQ('<span>',{'class':'glyphicon glyphicon-shopping-cart'});
		        					//$(pp_data).setProperty('html',getLangTradsLM('LM_PAYPAL_BUTTON'));
		        					LMJQ(pp_data).append(pp_data_inner,' '+getLangTradsLM('LM_PAYPAL_BUTTON'));
		        					LMJQ(card_data).append(pp_data);
	        					}
	        					break;
	        				case '18':
	        					var lmlll='<div class="lmlinklist" seed="'+this.seed+'" headerid="'+header.id+'">'+this.getRow(i).getData(header.id).getValue()+'</div>'
	        					LMJQ(card_data).append(lmlll);
	        					break;
	        				default:
	        					LMJQ(card_data).html(this.getRow(i).getData(header.id).getValue());
	        					break;
	        			}    	        			
	        		} 
	        		LMJQ(card_content_block).append(card_data);	        		
	        		LMJQ(itemContent).append(card_content_block);
	        	}else if(header.type=='6'&& this.getRow(i).getData('_struser')!=null){ // non-visible user
	        		// User    	        		
	        		uservalue=this.getRow(i).getData(header.id).getValue();
					if (LMJQ.type(uservalue)===null)uservalue=0;			
	        	}
	    	}
        	LMJQ(item).append(itemContent);
	    	LMJQ(itemWrapper).append(item);
	    	LMJQ(containerWrapper).append(itemWrapper);
    	}
    	//LMJQ(containerWrapper).append(itemWrapper);
    	return containerWrapper;
    	
    };    
    this.printAutofilterModule= function(){
    	var isFiltro=false;
    	for(var j=0;j<this.headers.length;j++){
    		var header=this.headers[j];
    		if(header.isAutofilter() && header.isVisible()){
    			isFiltro=true;
    			break;
    		}    				
    	}
    	if (isFiltro){
    		var seedTmp=this.seed;
    		var headers_number=this.headers.length+1;
	    	if (this.options.viewonly) headers_number=this.headers.length;
    		var randIdModule='md_'+randomIntFromInterval(0,10000);
	    	var containerWrapper=LMJQ('<div class="lm_bootstrap" id="'+randIdModule+'"></div>');
	    	var containerFluid=LMJQ('<div class="lmcontainer"></div>');	    	
    		var container=LMJQ('<div class="" style="min-height:0px;"></div>');
    		
    		var title=LMJQ('<div class="span2" style="min-height:0px;">'+getLangTradsLM('LM_VIEW_FILTER')+'</div>');
    		//LMJQ(container).append(title);
    		
    		//var tools=LMJQ('<div class="span9"></div>');
    		var tools=LMJQ('<div class="btn-group btn-group-justified" style="margin-bottom:8px;"></div>');
    		var tools_apply=LMJQ('<a class="btn btn-default" title="'+getLangTradsLM('LM_FILTER_APPLY')+'"><span class="glyphicon glyphicon glyphicon-log-in"></span> '+getLangTradsLM('LM_FILTER_APPLY_MOD')+'</a>');    		
    		LMJQ(tools_apply).click(function(){eval("executeFilters"+seedTmp+"('"+randIdModule+"');");});
    		var tools_disable=LMJQ('<a class="btn btn-default" title="'+getLangTradsLM('LM_DISABLE_FILTER')+'"><span class="glyphicon glyphicon glyphicon-log-out"></span> '+getLangTradsLM('LM_DISABLE_FILTER_MOD')+'</a>');
    		LMJQ(tools_disable).click(function(){eval("clearAutofilter"+seedTmp+"();");});
    		
    		LMJQ(tools).append(tools_apply);
    		LMJQ(tools).append(tools_disable);
    		LMJQ(container).append(tools);
    		
    		
    		var filter=LMJQ('<div class=" lmfilter"></div>');
    		
    		
    		var isAnyFilter=false;    	
        	for(var j=0;j<this.headers.length;j++){
        		var header=this.headers[j];
        		var card=LMJQ('<div class="lm_modfilter_content"></div>');
        		var card_name=LMJQ('<div class="lm_modfilter_content_name">'+header.name+'</div>');
        		//LMJQ(card).append(card_name);
        		var valueselected=this.getFilterValueById(header.id);
        		var arrayvalueselected=this.getFilterValueByIdRaw(header.id);
        		if(header.isAutofilter() && header.isVisible()){  
        			isAnyFilter=true;
        			var arr_filtrables=new Array();
        			arr_filtrables=this.filtrableData[header.id];
        			
        			switch(header.autofilter){
        				case '0': // Select    					
        					if(header.type!=15){
        						LMJQ(card).append(af_default_select_wrapper(header,arr_filtrables,this.seed,valueselected,this.options.filter_automatic,header.name));
        					} else {
        						LMJQ(card).append(af_rate_select_wrapper(header,this.seed,valueselected,this.options.filter_automatic,header.name));
        					}
        					break;
        				case '1': // Text
        					LMJQ(card).append(af_default_text_wrapper(header,this.seed,valueselected,this.options.filter_automatic,header.name));
        					break;
        				case '2': // Multiple
        					if(header.type!=15){
        						LMJQ(card).append(af_default_multivalue_mod(header,arr_filtrables,this.seed,arrayvalueselected,this.options.filter_automatic,header.name));
        					}else{
        						LMJQ(card).append(af_rate_multivalue(header,arr_filtrables,this.seed,arrayvalueselected,this.options.filter_automatic));
        					}
        					break;
        				case '3': //Range
	    					switch(header.type){
	    						case '1': //Date    							
	    							LMJQ(card).append(af_default_daterange(header,this.seed,arrayvalueselected,this.options.filter_automatic,header.name));  
	    							break;
	    						case '0': // Number
	    						case '14': // Number slider
	    						case '19': // Autoincrement
	    							LMJQ(card).append(af_default_numberrange(header,this.seed,arrayvalueselected,this.options.filter_automatic,header.name));
	    							break;
	    						case '4': // Text
	    							LMJQ(card).append(af_default_textrange(header,this.seed,arrayvalueselected,this.options.filter_automatic,header.name));
	    							break;
	    					}
    						break;       			
	    				
    				}
        			LMJQ(filter).append(card);
        		} 
        	}
        	if(isAnyFilter) LMJQ(container).append(filter);
        	
    		LMJQ(containerFluid).append(container);
    		LMJQ(containerWrapper).append(containerFluid);    		
    		//LMJQ(".nano").nanoScroller();
    		//LMJQ('.nano').lionbars();
    		return LMJQ(containerWrapper);    		
    	}
    	return '';
    };
    this.getDateFormat=function(){
    	return window['dateformat'+this.seed];
    };
    this.getDateFormatView=function(){
    	return window['dateformatview'+this.seed];
    };
    this.reinitializeHTMLEditors=function(){
    	/*if (typeof(tinyMCE) != "undefined") {
		  if (tinyMCE.activeEditor == null || tinyMCE.activeEditor.isHidden() != false) {
		    tinyMCE.editors=[]; // remove any existing references
		  }
		}
    	for(var i=0;i<this.headers.length;i++){
    		var header=this.headers[i];    		
    		if (header.type=='9'){
    			var editor_id='fld_'+header.id;
    			tinyMCE.EditorManager.execCommand('mceRemoveControl',true, editor_id);
    			tinyMCE.EditorManager.execCommand('mceAddControl',true, editor_id);
    			tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, editor_id);
    			tinyMCE.EditorManager.execCommand('mceAddEditor',true, editor_id);
    			tinyMCE.triggerSave();
    			
    		}
    	}*/
    	for(var i=0;i<this.headers.length;i++){
    		var header=this.headers[i];    		
    		if (header.type=='9'){
    			var editor_id='#fld_'+header.id;
    			LMJQ(editor_id).focus();
    			
    		}
    	}
    }
};
(function($){
	$.fn.reset = function(){
		 $(this).find('input:text, input:password, input:file, select, textarea').val('');
		 $(this).find('input:radio, input:checkbox') .removeAttr('checked').removeAttr('selected');
    };  
    $.fn.getAttributes = function() {
        var attributes = {}; 

        if( this.length ) {
            $.each( this[0].attributes, function( index, attr ) {
                attributes[ attr.name ] = attr.value;
            } ); 
        }

        return attributes;
    };
    $.fn.uniformHeight = function () {
        var maxHeight = 0,
            wrapper,
            wrapperHeight;
        return this.each(function () {
            // Applying a wrapper to the contents of the current element to get reliable height
            wrapper = $(this).wrapInner('<div class="wrapper" />').children('.wrapper');
            //wrapperHeight = wrapper.outerHeight();
            wrapperHeight=$(wrapper).height()+parseInt($(this).css("padding-top"))+parseInt($(this).css("padding-bottom"));
            maxHeight = Math.max(maxHeight, wrapperHeight);
            // Remove the wrapper
            wrapper.children().unwrap();
        }).height(maxHeight);                
    }; 
})(LMJQ);
function lm_sort(header, data,dateformat){
	return lm_sort_order(header, data, header.order, dateformat);
}

if (typeof String.prototype.startsWith != 'function' ) {
  String.prototype.startsWith = function( str ) {
    return this.substring( 0, str.length ) === str;
  }
};

function lm_sort_order(header, data, isDesc, dateformat){
	var numeric=false;
	var isdate=false;
	var headerId=header.id;
	var order='';
	switch(parseInt(header.type)){
		case 1:
			isdate=true;
			order=dateformat.toUpperCase().replace(/[^YDM]+/g,'');
		case 0:
			numeric=true;
			break;			
	}	
	if (isDesc){
		data.sort(function(a,b){
			var nameA="";
	        var nameB="";
	        if (a.getData(headerId)!=null && a.getData(headerId).getValue()!=null) nameA=a.getData(headerId).getValue();        
	        if (b.getData(headerId)!=null && b.getData(headerId).getValue()!=null) nameB=b.getData(headerId).getValue();
	        
	        if(isdate){
	        	if(nameA!=""){
		        	var newdateA=Date.fromString(nameA,{'order':order});	        	
		        	nameA=newdateA.get('year')+''+String.from(newdateA.get('month')).pad(2,'0','left')+''+String.from(newdateA.get('date')).pad(2,'0','left');		        	
	        	} else {
	        		var newdateA=new Date(0);	        	
		        	nameA=newdateA.get('year')+''+String.from(newdateA.get('month')).pad(2,'0','left')+''+String.from(newdateA.get('date')).pad(2,'0','left');
	        	}
	        	if(nameB!=""){
		        	var newdateB=Date.fromString(nameB,{'order':order});
		        	nameB=newdateB.get('year')+''+String.from(newdateB.get('month')).pad(2,'0','left')+''+String.from(newdateB.get('date')).pad(2,'0','left');
	        	}else {
	        		var newdateB=new Date(0);
		        	nameB=newdateB.get('year')+''+String.from(newdateB.get('month')).pad(2,'0','left')+''+String.from(newdateB.get('date')).pad(2,'0','left');
	        	}	        	
	        }
	        
	        if (numeric){
	        	nameA=parseFloat(nameA);
	            nameB=parseFloat(nameB);
	        } else {
	        	nameA=nameA.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
	        	nameB=nameB.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
	        }
	        if (nameA < nameB) {
	        	return 1;
	        }
	        if (nameA > nameB){
	        	return -1;
	        }
	        return 0 //default return value (no sorting)
		});
	} else {
		data.sort(function(a,b){
			var nameA="";
	        var nameB="";
	        if (a.getData(headerId)!=null && a.getData(headerId).getValue()!=null) nameA=a.getData(headerId).getValue();        
	        if (b.getData(headerId)!=null && b.getData(headerId).getValue()!=null) nameB=b.getData(headerId).getValue();
	        
	        if(isdate){
	        	if(nameA!=""){
		        	var newdateA=Date.fromString(nameA,{'order':order});	        	
		        	nameA=newdateA.get('year')+''+String.from(newdateA.get('month')).pad(2,'0','left')+''+String.from(newdateA.get('date')).pad(2,'0','left');
	        	}else {
	        		var newdateA=new Date(0);	        	
		        	nameA=newdateA.get('year')+''+String.from(newdateA.get('month')).pad(2,'0','left')+''+String.from(newdateA.get('date')).pad(2,'0','left');
	        	}
	        	if(nameB!=""){
		        	var newdateB=Date.fromString(nameB,{'order':order});
		        	nameB=newdateB.get('year')+''+String.from(newdateB.get('month')).pad(2,'0','left')+''+String.from(newdateB.get('date')).pad(2,'0','left');
	        	}else {
	        		var newdateB=new Date(0);
		        	nameB=newdateB.get('year')+''+String.from(newdateB.get('month')).pad(2,'0','left')+''+String.from(newdateB.get('date')).pad(2,'0','left');
	        	}
	        }
	        
	        if (numeric){	        	
	        	nameA=parseFloat(nameA);
	            nameB=parseFloat(nameB);
	        } else {
	        	nameA=nameA.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
	        	nameB=nameB.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
	        }
	        
	        if (nameA < nameB) {
	        	return -1;
	        }
	        if (nameA > nameB){
	        	return 1;
	        }
	        return 0 //default return value (no sorting)
		});
	}
	return data;
}


/*function replaceAll( text, searchtext, replacetext ){
  while (text.toString().indexOf(searchtext) != -1)
      text = text.toString().replace(searchtext,replacetext);
  return text;
}*/
function replaceAll(text, searchtext, replacetext) {
  return text.replace(new RegExp(searchtext, 'g'),replacetext);
}

function setContentEditor(editor,value,seed){
	Browser.exec("setContentEditor"+seed+"('"+editor+"','"+value+"')");
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

function changeCheck(elem){
	if (LMJQ(elem).is(':checked')) LMJQ(elem).parent().find('input[type=hidden]').val(getLangTradsLM('LM_YES_VALUE'));
	else LMJQ(elem).parent().find('input[type=hidden]').val(getLangTradsLM('LM_NO_VALUE'));
	return;
}
function setCalendarLM(inputf, buttonfield,seed){
	//Calendar.setup({'inputField':inputf,'ifFormat':getDateFormat(),'button':buttonfield});
	//console.log('date format Calendar:'+window['dateformat'+seed]);	
	LMJQ('#'+inputf).datepicker({ 
		dateFormat: window['dateformat'+seed], 
		onSelect: function() {
			LMJQ(".ui-datepicker a").removeAttr("href");
			LMJQ(this).change();
		}	
	}); 
}
function goToPaypal(pp_var){	
	var gtpp=LMJQ('<form action="https://www.paypal.com/cgi-bin/webscr" target="_blank" method="post">'+
			'<input type="hidden" name="cmd" value="_s-xclick"/>'+
			'<input type="hidden" name="hosted_button_id" value="'+pp_var+'"/></form>').appendTo('body');
	LMJQ(gtpp).submit();
	LMJQ(gtpp).remove();
}
function gotoList(elem){
	var headerid=LMJQ(elem).attr('headerid');
	//var seed=LMJQ(elem).closest('table').attr('seed');
	var seed=LMJQ(elem).attr('seed');
	var header=eval('listManager'+seed+'.getHeaderById('+headerid+');');
	var urllist=header.link_url;
	var listid=header.link_id;	
	var listdetail=header.link_detail;
	var listval=LMJQ(elem).html();
	var urlget=urllist;
	var urlgetmodal=urlget+'?tmpl=component';
	if (listid!=null && listid!='0'){
		urlget=urllist+'?lmhid='+listid+'&lmhval='+listval+'&lmhdetail='+listdetail;
		urlgetmodal=urlget+'&tmpl=component';
	}
	if (header!=null){
		switch(header.link_type){
			case '-1':
				var f = LMJQ('<form method="post" action="' + urllist + '"></form>');
				if (listid!=null && listid!='0'){
					f.html('<input type="hidden" name="lmhid" value="'+listid+'" /><input type="hidden" name="lmhval" value="'+listval+'" /><input type="hidden" name="lmhdetail" value="'+listdetail+'" />');
				}
			    f.appendTo(LMJQ('body')); 
			    f.submit();
				break;
			case '0':
				var f = LMJQ('<form method="post" action="' + urllist + '" target="_blank"></form>');
				if (listid!=null && listid!='0'){
					f.html('<input type="hidden" name="lmhid" value="'+listid+'" /><input type="hidden" name="lmhval" value="'+listval+'" /><input type="hidden" name="lmhdetail" value="'+listdetail+'" />');
				}
			    f.appendTo(LMJQ('body')); 
			    f.submit();
				break;
			case '1':
				var NWin = window.open(urlgetmodal, '', 'height='+header.link_height+',width='+header.link_width);
				if (window.focus){
					NWin.focus();
				}
				break;		
		}
	}
	return false;
}

function boot_column_conversor(numColumns){
	/*var ret=1;
	switch(numColumns){
		case '1': ret='span12';break;
		case '2': ret='span6';break;
		case '3': ret='span4';break;
		case '4': ret='span3';break;
		case '5': case '6': ret='span2';break;	
	}
	return ret;*/
	var ret='col-xs-12';
	switch(numColumns){
		case '1': ret='col-xs-12';break;
		case '2': ret='col-xs-12 col-sm-6';break;
		case '3': ret='col-xs-12 col-sm-6 col-md-4';break;
		case '4': ret='col-xs-12 col-sm-4 col-md-3';break;
		case '5': case '6': ret='col-xs-12 col-sm-6 col-md-2';break;	
	}
	return ret;
}

// Autofilter generation
function af_default_select(header,arr_filtrables,seed,valueselected,filterautomatic){
	return af_default_select_wrapper(header,arr_filtrables,seed,valueselected,filterautomatic,null)
}
function af_default_select_wrapper(header,arr_filtrables,seed,valueselected,filterautomatic,selectText){
	var select=LMJQ('<select>');
	LMJQ(select).attr('id','autofilter_'+header.id);
	LMJQ(select).attr('lm_fid',header.id);
	LMJQ(select).attr('seed',seed);
	LMJQ(select).attr('lm_fa',filterautomatic);
	LMJQ(select).addClass('lm_filter_select');
	LMJQ(select).addClass('form-control');
	for(var index=0;index<arr_filtrables.length;index++){
		var option=arr_filtrables[index];
		if(index==0){
			var optionElem=LMJQ('<option>');
			if(selectText!=null)
				LMJQ(optionElem).html(selectText);
			else
				LMJQ(optionElem).html(getLangTradsLM('LM_SELECT'));
			LMJQ(optionElem).val('');
			LMJQ(select).append(optionElem);
		}
		var optionElem=LMJQ('<option>');
		LMJQ(optionElem).html(option['name']);
		LMJQ(optionElem).val(option['fval']);
		if (valueselected!=null && valueselected==option['fval']){
			//LMJQ(optionElem).attr('selected','selected');
			LMJQ(optionElem).prop('selected','selected');
		}
		LMJQ(select).append(optionElem);
	}
	LMJQ(select).change(function(event){
		var selectElem=event.target;
		var idtable=LMJQ(selectElem).attr('seed');
		var headerid=LMJQ(selectElem).attr('lm_fid');		
		var filterautomatic=LMJQ(selectElem).attr('lm_fa');
		//eval("addFilter"+idtable+"("+headerid+", '"+LMJQ(selectElem).find('option:selected').val()+"',false)");
		eval("addFilter"+idtable+"("+headerid+", "+JSON.stringify(LMJQ(selectElem).find('option:selected').val())+",false)");
		/*if (filterautomatic==1)
			eval("executeFilters"+idtable+"()");*/
	});
	return select;
}
function af_rate_select(header,seed,valueselected,filterautomatic){
	return af_rate_select_wrapper(header,seed,valueselected,filterautomatic,null);
}
function af_rate_select_wrapper(header,seed,valueselected,filterautomatic,selectText){
	var select=LMJQ('<select>');
	LMJQ(select).attr('id','autofilter_'+header.id);
	LMJQ(select).attr('lm_fid',header.id);
	LMJQ(select).attr('seed',seed);
	LMJQ(select).attr('lm_fa',filterautomatic);
	LMJQ(select).addClass('lm_filter_select');
	LMJQ(select).addClass('lm_select_mm');
	LMJQ(select).addClass('form-control');
	LMJQ(select).addClass('input-sm');
	for(var index=0;index<5;index++){
		if(index==0){
			var optionElem=LMJQ('<option>');
			if(selectText!=null)
				LMJQ(optionElem).html(selectText);
			else
				LMJQ(optionElem).html(getLangTradsLM('LM_SELECT'));
			LMJQ(optionElem).val('');
			LMJQ(select).append(optionElem);
		}
		var optionElem=LMJQ('<option>');
		LMJQ(optionElem).html(getLangTradsLM('LM_STAR_'+index));    	    				
		LMJQ(optionElem).val(index);
		if (valueselected!=null && valueselected==index){
			//LMJQ(optionElem).attr('selected','selected');
			LMJQ(optionElem).prop('selected','selected');
		}
		LMJQ(select).append(optionElem);
	}
	LMJQ(select).change(function(event){
		var selectElem=event.target;
		var idtable=LMJQ(selectElem).attr('seed');
		var headerid=LMJQ(selectElem).attr('lm_fid');
		var filterautomatic=LMJQ(selectElem).attr('lm_fa');
		//eval("addFilter"+idtable+"("+headerid+", '"+LMJQ(selectElem).find('option:selected').val()+"',false)");
		eval("addFilter"+idtable+"("+headerid+", "+JSON.stringify(LMJQ(selectElem).find('option:selected').val())+",false)");
		/*if (filterautomatic==1)
			eval("executeFilters"+idtable+"()");*/
	});
	return select;
}

function af_default_text(header,seed,valueselected,filterautomatic){
	return af_default_text_wrapper(header,seed,valueselected,filterautomatic,null);
}
function af_default_text_wrapper(header,seed,valueselected,filterautomatic,placeholder){
	var text=LMJQ('<input>',{type:'text',size:8 ,id:'autofilter_'+header.id,'lm_fid':header.id});    
	LMJQ(text).addClass('lm_filter_input');
	LMJQ(text).addClass('input-small');
	LMJQ(text).addClass('form-control');
	LMJQ(text).attr('seed',seed);	
	LMJQ(text).attr('placeholder',header.filterplaceholder);
	LMJQ(text).attr('lm_fa',filterautomatic);
	if (valueselected!=null) LMJQ(text).val(valueselected);
	//if(placeholder!=null) LMJQ(text).attr('placeholder',placeholder);
	LMJQ(text).change(function(){
		//if (event.key=='enter'){
			var textElem=this;
			var idtable=LMJQ(textElem).attr('seed');
			var headerid=LMJQ(textElem).attr('lm_fid');	
			var filterautomatic=LMJQ(textElem).attr('lm_fa');
			//eval("addFilter"+idtable+"("+headerid+", '"+LMJQ(textElem).val()+"',false)");
			eval("addFilter"+idtable+"("+headerid+", "+JSON.stringify(LMJQ(textElem).val())+",false)");
			/*if (filterautomatic==1)
				eval("executeFilters"+idtable+"()");*/
		//}
	});
	return text;
}

function af_default_daterange(header,seed,valueselected,filterautomatic,name){
	return af_default_range_wrapper(header,seed,valueselected,1,filterautomatic,name);
}
function af_default_numberrange(header,seed,valueselected,filterautomatic,name){
	return af_default_range_wrapper(header,seed,valueselected,0,filterautomatic,name);
}
function af_default_textrange(header,seed,valueselected,filterautomatic,name){
	return af_default_range_wrapper(header,seed,valueselected,6,filterautomatic,name);
}
function af_default_range_wrapper(header,seed,valueselected,type,filterautomatic,name){
	var nameTmp='';
	if (name!=null) nameTmp='<h5>'+name+'</h5>';
	var text=LMJQ('<input>',{type:'text',size:8 ,id:'autofilter_'+header.id,'lm_fid':header.id});    
	LMJQ(text).addClass('lm_filter_input');
	LMJQ(text).addClass('input-small');
	LMJQ(text).addClass('form-control');
	LMJQ(text).attr('seed',seed);
	LMJQ(text).attr('data-type',type);
	LMJQ(text).attr('data-range','0');
	var randIdRange='rg_'+randomIntFromInterval(0,10000);
	LMJQ(text).attr('data-range-id',randIdRange);
	var randId='rg_'+randomIntFromInterval(0,10000);
	LMJQ(text).prop('id',randId);	
	if (valueselected!=null) LMJQ(text).val(valueselected.f);
	//LMJQ(text).attr('placeholder',getLangTradsLM('LM_FROM'));
	LMJQ(text).attr('placeholder',header.filterplaceholderfrom);
	if(filterautomatic==1){
		LMJQ(text).change(function(){
			var textElem=this;
			var idtable=LMJQ(textElem).attr('seed');
			var headerid=LMJQ(textElem).attr('lm_fid');	
			//eval("addFilter"+idtable+"("+headerid+", '"+JSON.stringify(LMJQ(textElem).val())+"',false);");
			eval("executeFilters"+idtable+"()");
		});
	}
	var text2=LMJQ(text).clone();
	var randId='rg_'+randomIntFromInterval(0,10000);
	LMJQ(text2).prop('id',randId);
	LMJQ(text2).attr('data-range','1');
	if (valueselected!=null) LMJQ(text2).val(valueselected.t);
	//LMJQ(text2).attr('placeholder',getLangTradsLM('LM_TO'));
	LMJQ(text2).attr('placeholder',header.filterplaceholderto);
	if(filterautomatic==1){
		LMJQ(text2).change(function(){
			var textElem=this;
			var idtable=LMJQ(textElem).attr('seed');
			var headerid=LMJQ(textElem).attr('lm_fid');	
			//eval("addFilter"+idtable+"("+headerid+", '"+JSON.stringify(LMJQ(textElem).val())+"',false);");
			eval("executeFilters"+idtable+"()");
		});
	}
	return LMJQ(nameTmp).add(text).add(text2);
	// Events in apply function
}


function af_default_multivalue(header,arr_filtrables,seed,arrayvalueselected,filterautomatic){
	var div_multiple=LMJQ('<div>',{id:'autofilter_multi_'+header.id});
	LMJQ(div_multiple).addClass('lm_autofilter_multi_div');
	//LMJQ(div_multiple).addClass('nano');
	var div_wrapper=LMJQ('<div>');
	LMJQ(div_wrapper).addClass('content');
	for(var index=0;index<arr_filtrables.length;index++){
		var option=arr_filtrables[index];    						
		var div_int=LMJQ('<div>',{'class':'checkbox'});
		var checkbox=LMJQ('<input>',{type:'checkbox',name:'autofilter_multi_values_'+header.id,
											id:'afmv_'+header.id+'_'+index, 'value':option['fval'],
											'cb_fid':header.id,'lm_fa':filterautomatic});    	
		LMJQ(checkbox).attr('seed',seed);
		if (arrayvalueselected!=null){
			for(var k=0;k<arrayvalueselected.length;k++){
				var elem_selected=arrayvalueselected[k];
				if (elem_selected==option['fval']){
					//LMJQ(checkbox).attr('checked','checked');
					LMJQ(checkbox).prop('checked','checked');
				} 
			}
		}
		LMJQ(checkbox).click(function(){
			var idtable=LMJQ(this).attr('seed');
			var headerid=LMJQ(this).attr('cb_fid');
			var filterautomatic=LMJQ(this).attr('lm_fa');
			var selected_elem=[];
			LMJQ(this).closest('.content').find('input[name=autofilter_multi_values_'+headerid+']:checked').each(function(index,elem){ 
				selected_elem.push(LMJQ(elem).attr('value'));
			});    						
			if (selected_elem.length<=0) selected_elem=''; 
			eval("addFilter"+idtable+"("+headerid+", '"+JSON.stringify(selected_elem)+"',true);");
			/*if (filterautomatic==1)
				eval("executeFilters"+idtable+"()");*/
		});		
		var label=LMJQ('<label>',{'for':'afmv_'+header.id+'_'+index});
		LMJQ(label).append(checkbox,option['name']);
		LMJQ(div_int).append(label);
		LMJQ(div_wrapper).append(div_int);
	}  
	LMJQ(div_multiple).append(div_wrapper);
	//new Fx.Reveal(div_multiple, {duration: 2000, mode: 'horizontal'});
	return div_multiple;
}

function af_default_multivalue_mod(header,arr_filtrables,seed,arrayvalueselected,filterautomatic,name){
	var div_multiple_wrapper=LMJQ('<div>');
	LMJQ(div_multiple_wrapper).addClass('lm_autofilter_multi_div_wrapper');
	var div_multiple_name=LMJQ('<div>');
	LMJQ(div_multiple_name).addClass('lm_autofilter_multi_div_name btn btn-default');
	LMJQ(div_multiple_name).html(name);
	var div_multiple_name_i=LMJQ('<i>',{'class':'glyphicon glyphicon-circle-arrow-down'});	
	LMJQ(div_multiple_name).append(div_multiple_name_i);
	LMJQ(div_multiple_wrapper).append(div_multiple_name);
	LMJQ(div_multiple_name).click(function(){
		if (LMJQ(this).find('i').hasClass('glyphicon-circle-arrow-down')){
			LMJQ(this).closest('.lm_autofilter_multi_div_wrapper').find('.lm_autofilter_multi_div_mod').slideDown('slow');
			LMJQ(this).find('i').removeClass('glyphicon glyphicon-circle-arrow-down');
			LMJQ(this).find('i').addClass('glyphicon glyphicon-circle-arrow-up');
		} else {
			LMJQ(this).closest('.lm_autofilter_multi_div_wrapper').find('.lm_autofilter_multi_div_mod').slideUp('slow');
			LMJQ(this).find('i').removeClass('glyphicon glyphicon-circle-arrow-up');
			LMJQ(this).find('i').addClass('glyphicon glyphicon-circle-arrow-down');
		}
		//LMJQ(this).closest('.lm_autofilter_multi_div_wrapper').find('.lm_autofilter_multi_div_mod').slideDown('slow');
	});
	
	var div_multiple=LMJQ('<div>',{id:'autofilter_multi_'+header.id});
	LMJQ(div_multiple).addClass('lm_autofilter_multi_div_mod');
	//div_multiple.addClass('nano');
	LMJQ(div_multiple).css('display','none');
	var div_wrapper=LMJQ('<div>');
	LMJQ(div_wrapper).addClass('content');
	for(var index=0;index<arr_filtrables.length;index++){
		var option=arr_filtrables[index];    						
		var div_int=LMJQ('<div>');
		var checkbox=LMJQ('<input>',{type:'checkbox',name:'autofilter_multi_values_'+header.id,
											id:'afmv_'+header.id+'_'+index, 'value':option['fval'],
											'cb_fid':header.id,'lm_fa':filterautomatic});    	
		LMJQ(checkbox).attr('seed',seed);
		if (arrayvalueselected!=null){
			for(var k=0;k<arrayvalueselected.length;k++){
				var elem_selected=arrayvalueselected[k];
				if (elem_selected==option['fval']){
					//LMJQ(checkbox).attr('checked','checked');
					LMJQ(checkbox).prop('checked','checked');
				} 
			}
		}
		LMJQ(checkbox).click(function(){
			var idtable=LMJQ(this).attr('seed');
			var headerid=LMJQ(this).attr('cb_fid');
			var filterautomatic=LMJQ(this).attr('lm_fa');
			var selected_elem=[];
			LMJQ(this).closest('.content').find('input[name=autofilter_multi_values_'+headerid+']:checked').each(function(index,elem){ 
				selected_elem.push(LMJQ(elem).attr('value'));
			});    						
			if (selected_elem.length<=0) selected_elem=''; 
			eval("addFilter"+idtable+"("+headerid+", '"+JSON.stringify(selected_elem)+"',true);");
			/*if (filterautomatic==1)
				eval("executeFilters"+idtable+"()");*/
		});
		var label=LMJQ('<label>',{'for':'afmv_'+header.id+'_'+index});
		LMJQ(label).html(option['name']);
		LMJQ(div_int).append(checkbox);
		LMJQ(div_int).append(label);
		LMJQ(div_wrapper).append(div_int);
	}  
	LMJQ(div_multiple).append(div_wrapper);
	//new Fx.Reveal(div_multiple, {duration: 2000, mode: 'horizontal'});
	LMJQ(div_multiple_wrapper).append(div_multiple);
	return div_multiple_wrapper;
}


function af_rate_multivalue(header,arr_filtrables,seed,arrayvalueselected,filterautomatic){
	var div_multiple=LMJQ('<div>',{id:'autofilter_multi_'+header.id});
	LMJQ(div_multiple).addClass('lm_autofilter_multi_div');
	//LMJQ(div_multiple).addClass('nano');
	var div_wrapper=LMJQ('<div>');
	LMJQ(div_wrapper).addClass('content');
	for(var index=0;index<5;index++){		
		var div_int=LMJQ('<div>',{'class':'checkbox'});
		var checkbox=LMJQ('<input>',{type:'checkbox',name:'autofilter_multi_values_'+header.id,
											id:'afmv_'+header.id+'_'+index,
											'cb_fid':header.id, 'value':index,'lm_fa':filterautomatic});    
		LMJQ(checkbox).attr('seed',seed);
		if (arrayvalueselected!=null){    							
			for(var k=0;k<arrayvalueselected.length;k++){
				var elem_selected=arrayvalueselected[k];
				if (elem_selected==index){
					//LMJQ(checkbox).attr('checked','checked');
					LMJQ(checkbox).prop('checked','checked');
				} 
			}
		}
		LMJQ(checkbox).click(function(event){
			var selectElem=this;
			var idtable=LMJQ(selectElem).attr('seed');
			var headerid=LMJQ(selectElem).attr('cb_fid');
			var filterautomatic=LMJQ(this).attr('lm_fa');
			var selected_elem=[];
			LMJQ('input[name=autofilter_multi_values_'+headerid+']:checked').each(function(index,elem){ 
				selected_elem.push(LMJQ(elem).val());
			});    						
			/*$$('input[name=autofilter_multi_values_'+headerid+']:checked').each(function(elem){ 
				selected_elem.include($(elem).getProperty('value'));
			});*/
			if (selected_elem.length<=0) selected_elem=''; 
			eval("addFilter"+idtable+"("+headerid+", '"+JSON.stringify(selected_elem)+"',true);");
		});
		var label=LMJQ('<label>',{'for':'afmv_'+header.id+'_'+index});
		LMJQ(label).append(checkbox).append('<img title="'+getLangTradsLM('LM_STAR_'+index)+'" src="'+pathImagenes+'stars_'+(index+1)+'.png"/>');
		//LMJQ(div_int).append(checkbox);
		LMJQ(div_int).append(label);
		LMJQ(div_wrapper).append(div_int);
	}  
	LMJQ(div_multiple).append(div_wrapper);
	//new Fx.Reveal(div_multiple, {duration: 2000, mode: 'horizontal'});
	return div_multiple;
}

function bulkcheckAll(target){
	if (LMJQ(target).is(':checked')){
		LMJQ(target).closest('table').find('input[name="chkbulk"]').prop('checked','checked');
	} else{
		LMJQ(target).closest('table').find('input[name="chkbulk"]').prop('checked','');	
	}	
}

function randomIntFromInterval(min,max){
    return Math.floor(Math.random()*(max-min+1)+min);
}

function postAutoFilter(filterpos){	
	LMJQ(filterpos).find('input[data-type=1]').each(function(index,elem){
		setCalendarLM(LMJQ(elem).attr('id'), null,LMJQ(elem).attr('seed'));		
	});
}
function cloneTo(from, to){
	LMJQ('[type=text], textarea').each(function(){ this.defaultValue = this.value; });
	LMJQ('[type=checkbox], [type=radio]').each(function(){ this.defaultChecked = this.checked; });
	LMJQ('select option').each(function(){ this.defaultSelected = this.selected; });
    var html_content = LMJQ(from).html();
    LMJQ(to).html(html_content);    
}