// Init

//window.addEvent('load',function(){
jQuery(document).ready(function(){
	    jQuery('#navmenu a').click(function (e) {
	        e.preventDefault();
	        //jQuery(this).tab('show');
	    })
		//jQuery('#navmenu a:first').tab('show');       
	    //jQuery('#field_nav a:first').tab('show'); 
		jQuery('#navmenu a[data-toggle="tab"]').on('shown', function (e) {        
	        //e.relatedTarget // previous tab
	        var activate=e.target // activated tab        
	        if ($(activate).getAttribute('href')=='#code'){ setCodeHelper();}
	        if ($(activate).getAttribute('href')=='#events'){ setJSCodeHelper();}	        
	        if ($(activate).getAttribute('href')=='#fields'){ 
	        	jQuery('#fields').find('.multivalue:first').sortable();
	    		jQuery('#fields').find('.multivalue:first').disableSelection();
	        }    	        
	        Cookie.write('cb_sep_id',$(activate).getAttribute('href'));	        
	    })
	    jQuery('#field_nav a[data-toggle="tab"]').on('shown', function (e) {
	    	var activate=e.target;
	    	refreshMultivalues($(activate).getAttribute('href'));	
	    });
		jQuery('#addsection').bind('click',function(event){ addSection(); });
		jQuery('#delsection').bind('click',function(event){ delSection(); });		
		//Get Cookies
		if(typeOf(Cookie.read('cb_sep_id'))!=null){
			var ind=Cookie.read('cb_sep_id');			
			//jQuery('#navmenu a[href="'+ind+'"]').tab('show');		
		}
		
		jQuery('#field_nav .tab-pane').each(function(index,elem){
			checkFields(elem);
		});
		
		jQuery('#addfield').bind('click',function(){addField();});
		jQuery('#delfield').bind('click',function(){delfield();});
		jQuery('.sqlpreview_close').bind('click',function(event){
			event.preventDefault();
			event.stopPropagation()
			jQuery('#sqlpreview').modal('hide');
		});					
		
		//jQuery('#section_nav a:first').tab('show');
		jQuery('.cb_radio button').click(function(){			
			jQuery(this).closest('.cb_radio').find('.radio_hidden').val(jQuery(this).attr('aria-label'));
		});
		jQuery('#cof').find('button').click(function(){			
			if(jQuery(this).attr('aria-label')=='1'){
				jQuery('#cofY').show();
			} else {
				jQuery('#cofY').hide();
			}
		});		
		jQuery('.mask-input .dropdown-menu a').click(function(){
			var mask_input=jQuery(this).attr('mask');
			jQuery(this).closest('.mask-input').find('input[name="mask"]').val(mask_input);
		});
		jQuery('.emailfield-input .dropdown-menu a').click(function(){
			var emailfield_input=jQuery(this).attr('emailfield');
			var input = jQuery(this).closest('.emailfield-input').find('input[name="sendemailform"]');
			var append="";
			if(jQuery(input).val().length>0){append=";";}
			jQuery(input).val(jQuery(input).val()+append+emailfield_input);
		});		
		jQuery('.lmlinkselect').change(function(elem){
			var elem=jQuery(this);
			getListFields(elem);			
		});
		jQuery('.fieldlist').change(function(elem){			
			jQuery(this).closest('.controls').find('input[name="link_id"]').val(jQuery(this).val());
		});
		/*jQuery('body').tooltip({
			selector:'[rel=tooltip]',
			placement:'right',
			html:true,
			start:function(){alert('start');}
		});*/		
		jQuery( "#fields1, #fields2" ).sortable({
			 connectWith: ".connectedSortable"
		}).disableSelection();
		jQuery(".multivalue").sortable();
		/*
		editAreaLoader.init({
			id : "code_ta"		// textarea id
			,syntax: "php"			// syntax to be uses for highgliting
			,start_highlight: true		// to display with highlight mode on start-up
			,allow_resize: "y"
			,min_height: 350
		});		
		var tas=['js_ta_A','js_ta_B','js_ta_C','js_ta_D','js_ta_E'];
		for(var i=0;i<tas.length;i++){
			editAreaLoader.init({
				id : tas[i]		// textarea id
				,syntax: "js"			// syntax to be uses for highgliting
				,start_highlight: true		// to display with highlight mode on start-up
				,allow_resize: "y"
				,min_height: 350
			});
		}	*/
    jQuery('#sfadd').click(function(){
      var selected=jQuery('#specialfilterscombo').find('option:selected').val();
      var cloned=jQuery('#sf'+selected+' >div').clone();
      jQuery(cloned).find('input[name="sfid"]').val(jQuery('.sfitem').length);
      jQuery(cloned).find(".sfitem-delete").click(function(event){
        jQuery(event.target).closest('.sfitem').remove();
      });
      jQuery(cloned).appendTo(jQuery('#sf'));
    });                    
    jQuery(".sfitem-delete").click(function(event){
      jQuery(event.target).closest('.sfitem').remove();
    });
    if (see_editor) setHTMLCodeHelper();
});

	function setHTMLCodeHelper(){
		var tas=['html_ta_A'];
		for(var i=0;i<tas.length;i++){
			/*editAreaLoader.init({
				id : tas[i]		// textarea id
				,syntax: "html"			// syntax to be uses for highgliting
				,start_highlight: true		// to display with highlight mode on start-up
				,allow_resize: "y"
				,min_height: 350
				,display:'later'
			});*/
			jQuery('#'+tas[i]).markItUp(HTMLSettings);
		}
	}

	function refreshMultivalues(dest){
		dest=dest.substring(1);
		//jQuery('#'+dest).find('.multivalue').sortable("option", "cancel", ':input,button');		
		//jQuery('#'+dest).find('.multivalue').disableSelection();		
	} 

// Field Editor

	function changeType(selectElem){
		checkFields(jQuery(selectElem).closest('.tab-pane'));
	}
	
	function checkFields(tabContent){
		var type=jQuery(tabContent).find('.type option:selected').val();
		if (!jQuery(tabContent).find('.mv_block').hasClass('hide'))
			jQuery(tabContent).find('.mv_block').addClass('hide');
		var enableFields=new Array();		
		switch(type){
			case '-1': break;
			case '0': enableFields=[".css",".decimal",".limit0",".limit1",".default",".placeholder",".total",".lmlink"]; break;
			case '14': enableFields=[".css",".decimal",".limit0",".limit1",".default",".placeholder",".lmlink"]; break;
			case '1': enableFields=[".css",".limit0",".limit1",".default",".placeholder",".lmlink"]; break;
			case '12': case '19': enableFields=[".css",".lmlink"]; break;
			case '2': case '11': case '16': case '10':  enableFields=[".css",".default",".lmlink"]; jQuery(tabContent).find('.mv_block').removeClass('hide'); break;
			case '3': enableFields=[".css",".default",".lmlink"]; break;
			case '4': enableFields=[".css",".default",".placeholder",".validation",".readmore",".lmlink"]; break;
			case '7': enableFields=[".css",".default",".placeholder",".readmore",".lmlink"]; break;
			case '9': enableFields=[".css",".default",".placeholder"]; break;
			case '6': case '15': break;
			case '8': enableFields=[".css",".limit0",".limit1",".default"]; break;						
			case '17': enableFields=[".paypal"]; break;
			case '18': enableFields=[".lmlink",".default"]; break;
		}
		jQuery(tabContent).find('.css').hide();
		jQuery(tabContent).find('.decimal').hide();
		jQuery(tabContent).find('.limit0').hide();
		jQuery(tabContent).find('.limit1').hide();
		jQuery(tabContent).find('.default').hide();
		jQuery(tabContent).find('.placeholder').hide();
		jQuery(tabContent).find('.total').hide();
		jQuery(tabContent).find('.validation').hide();
		jQuery(tabContent).find('.paypal').hide();
		jQuery(tabContent).find('.readmore').hide();
		jQuery(tabContent).find('.lmlink').hide();		
		jQuery(enableFields).each(function(index,elem){
			jQuery(tabContent).find(elem).show('slow');
			//jQuery(tabContent).find(elem).val('');
		});		
		// Autofilter
		var autofilterenabled=false;
		switch(type){
			case '-1': break;
			case '0': 
			case '14': 
			case '1': 
			case '4': 
			case '19':
				autofilterenabled=true;
				break;
		}
		if (autofilterenabled){			
			if (!jQuery(tabContent).find('#autofilter option [value="3"]')){
				jQuery(tabContent).find('#autofilter').append('<option value="3">'+getLangTrads('AF_RANGE')+'</option>');
			}
		} else {
			jQuery(tabContent).find('#autofilter option[value="3"]').each(function(i,e){jQuery(e).remove()});
		}
	} 

	function addField(){		
		var rand=Math.floor((Math.random()*100000)+1);
		var li=jQuery('<li> <a data-toggle="tab" href="#_'+rand+'">'+getLangTrads('NEW')+'</a></li>');
		jQuery('#field_nav .nav ').append(li);
		var dupli_tab_content=jQuery('#input_field_duplicate').find('div:first').clone(true,true);
		jQuery(dupli_tab_content).attr('id','_'+rand);
		jQuery('#field_nav .tab-content').append(dupli_tab_content);
		jQuery('#field_nav .nav a:last').tab('show');	
		
	}
	
	function delfield(){		
		var liactive=jQuery('#field_nav .nav li.active a').attr('href');
		var contentId=liactive.substring(1);
		jQuery('#field_nav .nav li.active').remove();
		jQuery('#field_nav .tab-content').find('#'+contentId).remove();		
		jQuery('#field_nav .nav a:last').tab('show');
	}

	function switchValue(elem,target){
		if (jQuery(elem).is(':checked')){ jQuery(elem).parent().find('#'+target).val('1') ;}
		else {jQuery(elem).parent().find('#'+target).val('0');}
	}
	
	function addMultivalue(elem){		
		/*var type=jQuery(elem).closest('.tab-pane').find('.type option:selected').val();
		if (type!=2 && type!=6 && type!=7 && type!=10){
	        alert(getLangTrads( 'ADD_VALUES' ));
	        return false;
	    }*/
		addMultivalueWrapper(elem,'','');
	}
	
	function addMultivalueWrapper(elem,name,value){
		var multivalue_div=jQuery(elem).closest('.tab-pane').find('.multivalue');
		var multi_html=jQuery('<div class="form-inline multivalue_elem">'+ 
				'<label class="cb_input_label">'+getLangTrads('NAME')+'</label>'+
				'<input type="text" class="input-small" id="mvname" name="mvname" value="'+name+'">'+							
				'<label class="cb_input_label">'+getLangTrads('VALUE')+'</label>'+
				'<input type="text" class="input-small" id="mvval" name="mvval" value="'+value+'">'+
				'<input type="hidden" id="mvid" name="mvid">'+
				'<div class="btn-group cb_mv_buttons">'+
				'	<a class="btn cb_cmove" href="#"><i class="icon-mvmove">&nbsp;</i></a>'+
				'	<a class="btn btn-danger"onclick="javascript:mv_remove(this);"><i class="icon-mvremove">&nbsp;</i></a>'+
				'</div>'+
			'</div>');		
		jQuery(multivalue_div).append(multi_html);		
		//jQuery(multivalue_div).sortable("option", "cancel", ':input,button');
		jQuery(multivalue_div).sortable();
		//jQuery(multivalue_div).disableSelection();
		jQuery(multivalue_div).on( 'click', 'input', function () {    
		    $(this).focus();
		});
	}
	
	function mv_remove(elem){
		jQuery(elem).closest('.multivalue_elem').remove();
	}
	
	var lastModalElem=null;
	function importCSV(elem){
		// Reset Data
		lastModalElem=elem;
		jQuery('#csvimport .csvdata').val('');
		jQuery('#csvimport').modal({});
	}
	
	function deleteAllMultivalue(elem){
		jQuery(elem).closest('.tab-pane').find('.multivalue .multivalue_elem').remove();		
	}
	
	function addCSVData(){
		var csvdata=jQuery('#csvimport .csvdata').val();
		jQuery.csv.toArrays(csvdata).each(function(elem){
			addMultivalueWrapper(lastModalElem,elem[0],elem[1]);
		});		
	}
	
	function addSQL(elem){
		lastModalElem=elem;
		jQuery('#sqlimport .sqldata').val(jQuery(elem).closest('.btn-group').find('.sqltext').val());
		jQuery('#sqlimport').modal({});
	}
	
	function delSQL(elem){
		lastModalElem=elem;
		jQuery('#sqldel').modal({});
	}
	
	function previewSQL(elem){		
		jQuery.ajax({
		  type: "POST",
		  url: "index.php",
		  data: {'option': 'com_listmanager',
					'controller': 'helper',
					'task':'sqlquery',
					'format':'raw',
					'rstring':jQuery('#sqlimport .sqldata').val()},
		  success: function(data){
				jQuery('#sqlpreview .result').html(data);
				jQuery('#sqlpreview').modal({});
		  },
		  error: function(XMLHttpRequest, textStatus, errorThrown) {
			  jQuery('#sqlpreview .result').html('<div class="alert alert-error">'+getLangTrads('ERROR_SQL')+'</div>');
			  jQuery('#sqlpreview').modal({});
		  }
		});		
	}	
	function addSQLQuery(){
		jQuery(lastModalElem).closest('.tab-pane').find('.sqltext').val(jQuery('#sqlimport .sqldata').val());
	}
	function delSQLQuery(){
		jQuery(lastModalElem).closest('.tab-pane').find('.sqltext').val('');
	}
	
	
	function setCodeHelper(){
		jQuery(function() {
			jQuery( "#code_tweaks" )
	            .accordion({
	                header: "> div > .heading",
	                collapsible: true,
	                active:false
	            })
	            .sortable({
	                axis: "y",
	                handle: ".heading",
	                stop: function( event, ui ) {
	                    // IE doesn't register the blur when sorting
	                    // so trigger focusout handlers to remove .ui-state-focus
	                    ui.item.children( ".heading" ).triggerHandler( "focusout" );
	                }
	            });
	    });
		editAreaLoader.init({
			id : "code_ta"		// textarea id
			,syntax: "php"			// syntax to be uses for highgliting
			,start_highlight: true		// to display with highlight mode on start-up
			,allow_resize: "y"
			,min_height: 350
		});	
	}
	
	function setJSCodeHelper(){
		jQuery(function() {
			jQuery( "#js_code_tweaks" )
	            .accordion({
	                header: "> div > .heading",
	                collapsible: true,
	                active:false
	            })
	            .sortable({
	                axis: "y",
	                handle: ".heading",
	                stop: function( event, ui ) {
	                    // IE doesn't register the blur when sorting
	                    // so trigger focusout handlers to remove .ui-state-focus
	                    ui.item.children( ".heading" ).triggerHandler( "focusout" );
	                }
	            });
	    });
		var tas=['js_ta_A','js_ta_B','js_ta_C','js_ta_D','js_ta_E'];
		for(var i=0;i<tas.length;i++){
			editAreaLoader.init({
				id : tas[i]		// textarea id
				,syntax: "js"			// syntax to be uses for highgliting
				,start_highlight: true		// to display with highlight mode on start-up
				,allow_resize: "y"
				,min_height: 350
				,display:"later"
			});
		}
	}
	
	// Section	
	function addSection(){
		var rand=Math.floor((Math.random()*100000)+1);
		var li=jQuery('<li> <a data-toggle="tab" href="#_'+rand+'">'+getLangTrads('NEW')+'</a></li>');
		jQuery('#section_nav .nav ').append(li);
		var dupli_tab_content=jQuery('#section_duplicate').find('div:first').clone(true,true);
		jQuery(dupli_tab_content).attr('id','_'+rand);
		jQuery('#section_nav .tab-content').append(dupli_tab_content);
		jQuery('#section_nav .nav a:last').tab('show');
	}
	function delSection(){		
		var liactive=jQuery('#section_nav .nav li.active a').attr('href');
		var contentId=liactive.substring(1);
		jQuery('#section_nav .nav li.active').remove();
		jQuery('#section_nav .tab-content').find('#'+contentId).remove();		
		jQuery('#section_nav .nav a:last').tab('show');
	}

	
   /* 1.5 */
   function submitbutton(pressbutton) {	   
       var f = document.adminForm;
       if(validateForm($('adminForm'))){
           createForm();
       } else {
           var msg = getLangTrads( "INCORRECT_VALUES" );
           alert(msg);
       }    
   }
   jQuery(document).ready(function(){
   //window.addEvent('domready', function() {
	   /*jQuery('#adminForm').submit(function(event){
		   var retorno=validateForm(jQuery('#adminForm'));         
	       if(retorno){
	    	   createForm();
	       }else{
	    	   jQuery(event).stopPropagation();
	       } 
	   });
	   */
     /*$('adminForm').addEvent('submit',function(evnt){
    	 createForm();    
       });
       */
	   jQuery('#adminForm').submit(function(event){
	    	 createForm();    
	       });
   });
   
   Joomla.submitbutton = function(task){	
		if (task == ''){
			return false;    
		} else {		
			createForm();
			Joomla.submitform(task);
	        return true;
	    }
	}
   
   function createForm(){
	   //saveEditor();
	   var newFields=new Array();
	   // HTML Editors
	   /*if(jQuery.type(Joomla.editors.instances['info'])==="undefined"){		   
	   } else {
		   Joomla.editors.instances['info'].save();
	   }*/
	   
	   /*var tas=['code_ta','js_ta_A','js_ta_B','js_ta_C','js_ta_D','js_ta_E'];
	   for(var i=0;i<tas.length;i++){
		   jQuery('#'+tas[i]).val(editAreaLoader.getValue(tas[i]));
		}		   
	   */
	   // main inputs
	   jQuery('#general input').each(function(index,elem){
		   if(typeOf(jQuery(elem).attr('name'))!='null'){
			   var tmp=new Object();
			   tmp.name=jQuery(elem).attr('name');
			   tmp.val=jQuery(elem).val();
			   newFields.push(tmp);
		   }
	   });
	   jQuery('#general textarea').each(function(index,elem){		   
		   if(typeOf(jQuery(elem).attr('name'))!='null'){
			   var tmp=new Object();
			   tmp.name=jQuery(elem).attr('name');
			   tmp.val=escapeHtml(jQuery(elem).val());
			   newFields.push(tmp);
		   }
	   });
	   
	   // Fields
	   var a_fields=new Array();
	   jQuery('#field_nav').find('.tab-pane').each(function(i,fields){
		   var tmp=new Object();
		   var a_mvname=new Array();
		   var a_mvval=new Array();
		   var a_mvid=new Array();
		   jQuery(fields).find('input, select').each(function(index,elem){
			   var name=jQuery(elem).attr('name');
			   var val=jQuery(elem).val();			   
			   if(typeOf(name)!='null'){
				   if (name=='mvname'){
					   a_mvname.push(val);
				   } else if(name=='mvval'){
					   a_mvval.push(val);
				   } else if(name=='mvid'){
					   a_mvid.push(val);
				   } else {					   
					   eval('tmp.'+name+'="'+escapeHtml(val)+'";');					   
				   }
			   }
		   });
		   tmp.mvname=a_mvname;
		   tmp.mvval=a_mvval;
		   tmp.mvid=a_mvid;
		   a_fields.push(tmp);
	   });
	   var tmp=new Object();
	   tmp.name='fields';
	   tmp.val=jQuery.stringify(a_fields);	   
	   newFields.push(tmp);
	   
	   jQuery('#adminForm input[variable=true]').remove();
	   
	   // Key fields
	   var keyfields='';
	   jQuery('#fields2').find('li').each(function(index,elem){
		   if (index>0)keyfields+=',';
		   keyfields+=jQuery(elem).attr('attr-id');
	   });
	   var tmp=new Object();
	   tmp.name='keyfields';
	   tmp.val=keyfields;	   
	   newFields.push(tmp);
     
     // Special Filters
     var sf=new Array();
     jQuery('#sf').find('.sfitem').each(function(index,elem){
       var tmp=new Object();
       jQuery(elem).find('input:not([type="checkbox"]),select').each(function(i,e){
            tmp[jQuery(e).attr('name')]=jQuery(e).val();
       });
       sf.push(tmp);
     });
     var tmp=new Object();
	   tmp.name='specialfilters';
	   tmp.val=JSON.stringify(sf);	   
	   newFields.push(tmp);
	   
	   // Add to form
	   for(var i=0;i<newFields.length;i++){
		   var elem=newFields[i];
		   jQuery('#adminForm').append( jQuery( "<input>", {
			    "type": "hidden",
			    "name": elem.name,
			    "value":elem.val,
			    "variable":"true"
			}), "" );
	   }
	   
   }
   
   function validateForm(f) {
	   var valido=true;
	   jQuery('#field_nav').find('.required').each(function(){
		   if (jQuery(this).val().length<=0){
			   valido=false;
			   jQuery(this).focus();
			   return;
		   }
	   });	   
	   jQuery('#field_nav').find(".basic_letters").each(function() {
		   var value=jQuery(this).val();
		   var rtext="^[a-zA-Z0-9]*$";
		   var regex=new RegExp(rtext, 'g');
		   if(!regex.test(value)){ 
			   valido=false;
			   jQuery(this).focus();
			   return;
		   }
		});
	    if (valido) {
	       //f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
	       return true; 
	    }
	    else {
	       var msg = getLangTrads( "INCORRECT_VALUES" );
	       alert(msg);
	    }
	    return false;
	 }
   function openModalCode(elemTo){
	   jQuery('#'+elemTo).modal('show');
   }
   function getListFields(idlist){
	   jQuery.post("index.php", { 
		   		"option": "com_listmanager", "controller": "helper",
		   		"task": "fieldList", "format":"raw", "idlist":jQuery(idlist).val()})
		.done(function(data) {
			var selectElem=jQuery(idlist).closest('.controls').find('.fieldlist');
			jQuery(selectElem).empty();
			jQuery(selectElem).append(data);
			jQuery(selectElem).find('option:first').attr('selected','selected');
			jQuery(selectElem).closest('.controls').find('input[name="link_id"]').val(jQuery(selectElem).find('option:first').val());
	   });
	   if (jQuery(idlist).val()=='-1'){
		   jQuery(idlist).closest('.controls').find('input[name="link_id"]').val(jQuery(idlist).closest('.controls').find('input[name="link_id_original"]').val());
	   }
	   
   }
   function link_remove(elem){
	   jQuery(elem).closest('.controls').find('input[name="link_id"]').val(null);
	   jQuery(elem).closest('label').remove();
   }
   jQuery.extend({
	    stringify  : function stringify(obj) {
	        var t = typeof (obj);
	        if (t != "object" || obj === null) {
	            // simple data type
	            if (t == "string") obj = '"' + obj + '"';
	            return String(obj);
	        } else {
	            // recurse array or object
	            var n, v, json = [], arr = (obj && obj.constructor == Array);	
	            for (n in obj) {
	                v = obj[n];
	                t = typeof(v);
	                if (obj.hasOwnProperty(n)) {
	                    if (t == "string") v = '"' + v + '"'; else if (t == "object" && v !== null) v = jQuery.stringify(v);
	                    json.push((arr ? "" : '"' + n + '":') + String(v));
	                }
	            }
	            return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
	        }
    	}
   });   
   var entityMap = {
		    "&": "&amp;",
		    "<": "&lt;",
		    ">": "&gt;",
		    '"': '&quot;',
		    "'": '&#39;',
		    "/": '&#x2F;'
		  };

  function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
  }