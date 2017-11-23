<?php
/*
 * @component List Manager 
 * @copyright Copyright (C) November 2017. 
 * @license GPL 3.0 
 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details. 
 * See <http://www.gnu.org/licenses/>. 
 * More info www.moonsoft.es 
 * gestion@moonsoft.es
 */
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$module_name="mod_listmanager";

jimport('joomla.environment.browser');
jimport( 'joomla.html.editor' );

require_once(JPATH_ROOT.DS.'modules'.DS.$module_name.DS.'helper.php');

$helper = new ModListManagerHelper();

//JHTML::_('behavior.formvalidation'); 
JHTML::_('behavior.calendar');
JHTML::_('behavior.tooltip');

$browser = JBrowser::getInstance();
$editor1 =JFactory::getEditor();

if(($browser->getBrowser() == 'msie') && ($browser->getMajor() < 8)){
  JHTML::script('json2.js', JURI::base().'modules/'.$module_name.'/assets/js/', true);
}

$pathImagenes=JURI::base().'modules/'.$module_name.'/assets/img/';

$scriptbase=JURI::base().'modules/'.$module_name.'/assets/js/';

$pathImagen=JURI::base().'modules/'.$module_name.'/assets/img/pdf_button.png';
$pathImagenFiltered=JURI::base().'modules/'.$module_name.'/assets/img/pdf_button_filtered.png';

$pathImagenAdd=JURI::base().'modules/'.$module_name.'/assets/img/btadd.png';
$pathImagenAll=JURI::base().'modules/'.$module_name.'/assets/img/show_all.png';
$pathImagenEx=JURI::base().'modules/'.$module_name.'/assets/img/excel_button.png';  
$pathImagenExFiltered=JURI::base().'modules/'.$module_name.'/assets/img/excel_button_filtered.png';
$pathImagenInfo=JURI::base().'modules/'.$module_name.'/assets/img/info.png';
$pathImagenEdit=JURI::base().'modules/'.$module_name.'/assets/img/btedit.png';

$pathImagenNext=JURI::base().'modules/'.$module_name.'/assets/img/next.png';
$pathImagenBack=JURI::base().'modules/'.$module_name.'/assets/img/back.png';

$pathImagenFilter=JURI::base().'modules/'.$module_name.'/assets/img/filter.png';

$pathImagenFirst=JURI::base().'modules/'.$module_name.'/assets/img/first.png';
$pathImagenLast=JURI::base().'modules/'.$module_name.'/assets/img/last.png';

$pathImagenDel=JURI::base().'modules/'.$module_name.'/assets/img/btdelete.png';
$pathImagenDetail=JURI::base().'modules/'.$module_name.'/assets/img/detail.png';
$pathImagenCardDetailOpen=JURI::base().'modules/'.$module_name.'/assets/img/tools_card.png';
$pathImagenOrder=JURI::base().'modules/'.$module_name.'/assets/img/order_asc_n.png';
$pathImagenOrderDesc=JURI::base().'modules/'.$module_name.'/assets/img/order_desc_n.png';
$pathImagenCalendar=JURI::base().'modules/'.$module_name.'/assets/img/calendar.png';
$pathImagenEmail=JURI::base().'modules/'.$module_name.'/assets/img/send.png';
$document = JFactory::getDocument();
$version=new JVersion();
$seed='_'.rand();
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/default2.css','text/css','screen'); 
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/jquery-ui-1.10.0.custom.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/rateit.css','text/css','screen');
//$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/lmbootstrap.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/lmbootstrap-'.$params->get('theme').'.css','text/css','screen');


//$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/nanoscroller.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/lionbars.css','text/css','screen');
$document->addStyleSheet(JURI::base().'modules/'.$module_name.'/assets/css/jquery.cleditor.css','text/css','screen');
//$document->addScript(JURI::base().'modules/'.$module_name.'/assets/js/observer.js');
$user = JFactory::getUser();

$session = JFactory::getSession();
$session->set('lm_'.$params->get('prefsids'), array('acl'=>$acl,'access_type'=>$params->get('access_type'),'viewonly'=>$params->get('viewonly')));

$document->addScript(JURI::base(true).'/index.php?option=com_listmanager&controller=assets&task=js&format=raw');
?>
<script>
var lm_version='2.5.5';
var userAgent = navigator.userAgent.toLowerCase();
LMJQ.browser = {
    version: (userAgent.match( /.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/ ) || [])[1],
    chrome: /chrome/.test( userAgent ),
    safari: /webkit/.test( userAgent ) && !/chrome/.test( userAgent ),
    opera: /opera/.test( userAgent ),
    msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),
    mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent )
};
var pathImagenes='<?php echo $pathImagenes;?>';
var arrDatos<?php echo $seed;?>;
var arrCabeceras<?php echo $seed;?>;
var altoform<?php echo $seed;?>;

var arrEditores<?php echo $seed;?>="";
//var arrEditoresSetters<?php echo $seed;?>="";
var uservalue=<?php echo $user->guest?'0':$user->id;?>;

var dateformat<?php echo $seed;?>='<?php echo $listing["date_format_bbdd"]?>';
var dateformatview<?php echo $seed;?>='<?php echo $listing["date_format"]!=null?$listing["date_format"]:$listing["date_format_bbdd"]?>';

function validateForm<?php echo $seed;?>(f) { 
   //if (document.formvalidator.isValid(f)) {
   LMJQ(f).validate({
	  debug:true,
	  submitHandler: function(form) {},
   	  invalidHandler: function(event, validator) {},
	  showErrors: function(errorMap, errorList) {this.defaultShowErrors();}
	});
    if (LMJQ(f).valid()){
        return true;
    } else {
    	/*var msg = '<?php echo JText::_( "LM_INCORRECT_VALUES" ); ?>';
		alert(msg);*/
		 return false;
    }   
}

function editValue<?php echo $seed;?>(key){
  //$('<?php echo $seed;?>list_adminForm').reset();  
  LMJQ('#<?php echo $seed;?>list_adminForm').reset();
  listManager<?php echo $seed;?>.aclForm(LMJQ('#<?php echo $seed;?>list_adminForm'),'edit');
  showForm<?php echo $seed;?>();
  listManager<?php echo $seed;?>.setContentInForm(LMJQ('#<?php echo $seed;?>list_adminForm'),key,'<?php echo $seed;?>');  
  <?php if ($listing['editor']==0): ?>
	  LMJQ('.cleditorMain').click(function(event){
		  var target=event.target;
		  var editor=LMJQ(target).find('textarea').cleditor();	  
		  //editor[0].updateFrame(LMJQ(editor[0]));
		  LMJQ(target).find('iframe').css('width','auto');
		  LMJQ(editor[0]).focus();	  
		});  
  <?php endif; ?>
}

function addValue<?php echo $seed;?>(){
  	//$('<?php echo $seed;?>list_adminForm').reset();
  	LMJQ('#<?php echo $seed;?>list_adminForm').reset();
  	listManager<?php echo $seed;?>.aclForm(LMJQ('#<?php echo $seed;?>list_adminForm'),'add');
  	showForm<?php echo $seed;?>();    
  	listManager<?php echo $seed;?>.setDefaultContentInForm(LMJQ('#<?php echo $seed;?>list_adminForm'),'<?php echo $seed;?>');
  	 <?php if ($listing['editor']==0): ?>
  	LMJQ('.cleditorMain').click(function(event){
  	  var target=event.target;
  	  var editor=LMJQ(target).find('textarea').cleditor();	  
  	  //editor[0].updateFrame(LMJQ(editor[0]));
  	  LMJQ(target).find('iframe').css('width','auto');
  	  LMJQ(editor[0]).focus();	  
  	});  
  	<?php endif; ?>
}
//var editorGlobal=false;
function setContentEditor(editor,value){
	 <?php if ($listing['editor']==0): ?>
	LMJQ('#'+editor).html(value);
	var editorGlobal=LMJQ('#'+editor).cleditor();
	setTimeout(function(){
		LMJQ(editorGlobal)[0].$area.val(value);
		LMJQ(editorGlobal)[0].updateFrame();
		LMJQ(editorGlobal)[0].refresh();
			  
	},1000);
	/*if (!editorGlobal){
		editorGlobal=LMJQ('#'+editor).cleditor();
	}			
	setTimeout(function(){
		LMJQ(editorGlobal)[0].$area.val(value);
		LMJQ(editorGlobal)[0].updateFrame();
		LMJQ(editorGlobal)[0].refresh();
	},500);*/
	<?php else:	
	  	$value='value';
	  	$editor='editor';
	  	echo str_replace("'","",$editor1->setContent($$editor,$$value));
  	endif;
  	?>	
}

function deleteValue<?php echo $seed;?>(key){	
  if (confirm("<?php echo JText::_('LM_CONFIRM_DELETE')?>")) {
	  LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=idrecord]').attr('value',key);
	  submitAjaxForm<?php echo $seed;?>('delete',false,2);
	  LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=idrecord]').attr('value','');
   } 
   return true;
}

function viewDetailpdf<?php echo $seed;?>(key){
	LMJQ('#<?php echo $seed;?>detailpdfForm').find('input[name="idrecord"]').val(key);
	LMJQ('#<?php echo $seed;?>detailpdfForm').submit();
}

function viewDetailrtf<?php echo $seed;?>(key){
	LMJQ('#<?php echo $seed;?>detailrtfForm').find('input[name="idrecord"]').val(key);
	LMJQ('#<?php echo $seed;?>detailrtfForm').submit();
}

function viewDetail<?php echo $seed;?>(key){
	<?php
	if (JRequest::getVar('lmhdetail',0)==1): ?>
		var contentValues=listManager<?php echo $seed;?>.setContentInDetail(key);
		var contentHTML=LMJQ('#<?php echo $seed;?>cb_detail_base').html();	
		LMJQ(contentValues).each(function(k,v){
			LMJQ.each(v, function(key,value) {
				contentHTML=replaceAll(contentHTML, '##'+key+'##', value);
			})		
		});
		contentHTML=contentHTML.replace(new RegExp("[#]{2}[0-9a-zA-Z-_]*[#]{2}", "g"), "");
		
		LMJQ('#allcontent-wrapper-<?php echo $seed;?>').after(contentHTML);
		
		<?php
	else:
		switch($listing['detailmode']):
			case 0: //Popup
			?>
			var contentValues=listManager<?php echo $seed;?>.setContentInDetail(key);
			var contentHTML=LMJQ('#<?php echo $seed;?>cb_detail_base').html();	
			LMJQ(contentValues).each(function(k,v){
				LMJQ.each(v, function(key,value) {
					contentHTML=replaceAll(contentHTML, '##'+key+'##', value);
				})		
			});
			contentHTML=contentHTML.replace(new RegExp("[#]{2}[0-9a-zA-Z-_]*[#]{2}", "g"), "");
			
			//contentHTML='<iframe id="iframeid" src="http://www.moonsoft.es"></iframe>';
			LMJQ('#<?php echo $seed;?>cb_detail').html(contentHTML);
			if (LMJQ.browser.msie && parseInt(LMJQ.browser.version)<8) {
			  	var width_content=LMJQ('#<?php echo $seed;?>cb_detail').width();
			  	var newDialog=LMJQ( "#<?php echo $seed;?>cb_detail" ).clone().appendTo('body');
			  	newDialog.dialog({
					modal: true,
					zIndex: 90,
					width:width_content+'px',				
					buttons: {
						<?php if ($params->get('showprintdetail')):?>
						<?php echo JText::_('LM_PRINT')?>: function (e) {
							var target=e.target;			
							var newHTML=LMJQ(target).closest('.ui-dialog').find('.lm_detail').clone().find('script').remove().end().html();
							LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:true});
	                    }
	                    <?php endif; ?>
	                },
	                open: function() {
	                	LMJQ(this).closest(".ui-dialog")
	                    .find(".ui-dialog-titlebar-close")
	                    .removeClass("ui-dialog-titlebar-close")
	                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
	                }
				});
		  	} else {
		  		if (listManager<?php echo $seed;?>.isEffects()) {
		  			var newDialog=LMJQ( "#<?php echo $seed;?>cb_detail" ).clone().appendTo('body');
		  		  	newDialog.dialog({
						modal: true,
						zIndex: 90,
						show: 'slide',
						hide: 'slide',
						width:formWidth,
						buttons: {
							<?php if ($params->get('showprintdetail')):?>
							<?php echo JText::_('LM_PRINT')?>: function (e) {
								var target=e.target;			
								var newHTML=LMJQ(target).closest('.ui-dialog').find('.lm_detail').clone().find('script').remove().end().html();
								LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:true});
		                    }
							<?php endif; ?>
		                } ,
		                open: function() {
		                	LMJQ(this).closest(".ui-dialog")
		                    .find(".ui-dialog-titlebar-close")
		                    .removeClass("ui-dialog-titlebar-close")
		                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
		                }
					});  		  			
		  		} else {
			  		var newDialog=LMJQ( "#<?php echo $seed;?>cb_detail" ).clone().appendTo('body');
				  	newDialog.dialog({
						modal: false,
						zIndex: 90,
						width:formWidth,
						buttons: {
							<?php if ($params->get('showprintdetail')):?>
							<?php echo JText::_('LM_PRINT')?>: function (e) {
								var target=e.target;			
								var newHTML=LMJQ(target).closest('.ui-dialog').find('.lm_detail').clone().find('script').remove().end().html();
								LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:true});
		                    }
							<?php endif; ?>
		                } ,
		                open: function() {
		                	LMJQ(this).closest(".ui-dialog")
		                    .find(".ui-dialog-titlebar-close")
		                    .removeClass("ui-dialog-titlebar-close")
		                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
		                }
					});
		  		}
		  	}
			setRated<?php echo $seed;?>();	
			//LMJQ('#iframeid').reload();
			<?php 
				break;
			case 1: //new Page
				?>
				LMJQ('#<?php echo $seed;?>detailForm').attr('target','_blank');
				<?php
			case 2: //same Page
				?>
				LMJQ('#<?php echo $seed;?>detailForm').find('[name="idrecord"]').val(key);
				LMJQ('#<?php echo $seed;?>detailForm').submit();
				<?php
				break;
		endswitch;	 
	endif;
	?>	
}


function changePage<?php echo $seed;?>(dir){
  var nuevapag=parseInt(LMJQ('.<?php echo $seed;?>pagactual').html())+dir;  
  if(nuevapag>0&&nuevapag<=parseInt(LMJQ('#<?php echo $seed;?>pagtotales').html())){  
	  LMJQ('.<?php echo $seed;?>pagactual').html(nuevapag);
    submitAjaxForm<?php echo $seed;?>('show',false,0);                                        
  }                                                                            
}

function goToPage<?php echo $seed;?>(page){	
  if(page==1)   
	  LMJQ('.<?php echo $seed;?>pagactual').html("1");
  else                                          
	  LMJQ('.<?php echo $seed;?>pagactual').html(LMJQ('.<?php echo $seed;?>pagtotales').html());
  submitAjaxForm<?php echo $seed;?>('show',false,0);                                   
}

function repaginar<?php echo $seed;?>(){
	LMJQ('.<?php echo $seed;?>pagactual').html("1");
}

                                                 
function orderForColumn<?php echo $seed;?>(ordercol,order){
  listManagerSorter<?php echo $seed;?>.setOrder(ordercol,order);
  submitAjaxForm<?php echo $seed;?>('show',false,0);
}

/** Init **/
var listManager<?php echo $seed;?>=new ListManager({	
	<?php if($params->get('viewonly')=='1'){ ?>viewonly:true<?php }else{ ?>viewonly:false<?php } ?>	,
	<?php if($params->get('show_totals')=='1'){ ?>showtotals:true<?php }else{ ?>showtotals:false<?php } ?>,
	<?php if($params->get('items_view')!=''){ ?>items_view:<?php echo $params->get('items_view'); } ?>,
	dateformat:'<?php echo $listing['date_format_bbdd'];?>',	
	dateformatview:'<?php echo $listing["date_format"]!=null?$listing["date_format"]:$listing["date_format_bbdd"]?>',
	access_type:'<?php echo $params->get('access_type');?>',
	uid:'<?php echo  JFactory::getUser()->id;?>',
	effects:'<?php echo $params->get('show_effects');?>',
	show_filter:'<?php echo $listing['show_filter'];?>',
	filter_automatic:'<?php echo $listing['filter_automatic'];?>'
});
listManager<?php echo $seed;?>.setSeed('<?php echo $seed;?>');
listManager<?php echo $seed;?>.setCardColumns('<?php echo $listing['list_columns'];?>');
listManager<?php echo $seed;?>.setCardColumnsHeight('<?php echo $listing['list_height'];?>');
listManager<?php echo $seed;?>.setCardNameClass('<?php echo $listing['list_name_class'];?>');
listManager<?php echo $seed;?>.setCardValueClass('<?php echo $listing['list_value_class'];?>');
listManager<?php echo $seed;?>.setToolComlumnPosition('<?php echo $listing['tool_column_position'];?>');
listManager<?php echo $seed;?>.setToolComlumnName('<?php echo $listing['tool_column_name'];?>');
listManager<?php echo $seed;?>.setAcl('<?php echo $acl;?>');
var listManagerFilter<?php echo $seed;?>=new LmFilterManager();
var listManagerSorter<?php echo $seed;?>=new LmSorterManager();
var FormSlide;

function getPathImagenOrder(){return '<?php echo $pathImagenOrder; ?>';}
function getPathImagenOrderDesc(){return '<?php echo $pathImagenOrderDesc; ?>';}
function getPathImagenDel(){return '<?php echo $pathImagenDel; ?>';}
function getPathImagenEdit(){return '<?php echo $pathImagenEdit; ?>';}
function getPathImagenDetail(){return '<?php echo $pathImagenDetail; ?>';}
function getPathImagenCardDetailOpen(){return '<?php echo $pathImagenCardDetailOpen; ?>';}

function getLangTradsLM(trad){
	switch(trad){
		case 'LM_EDIT': return '<?php echo JText::_('LM_EDIT')?>';
		case 'LM_EDIT_TOOLTIP': return '<?php echo JText::_('LM_EDIT_TOOLTIP')?>';
		case 'LM_DELETE': return '<?php echo JText::_('LM_DELETE')?>';
		case 'LM_DELETE_TOOLTIP': return '<?php echo JText::_('LM_DELETE_TOOLTIP')?>';
		case 'LM_DETAIL': return '<?php echo JText::_('LM_DETAIL')?>';
		case 'LM_DETAIL_TOOLTIP': return '<?php echo JText::_('LM_DETAIL_TOOLTIP')?>';
		case 'LM_DETAILPDF': return '<?php echo JText::_('LM_DETAILPDF')?>';
		case 'LM_DETAILPDF_TOOLTIP': return '<?php echo JText::_('LM_DETAILPDF_TOOLTIP')?>';
		case 'LM_TOTAL': return '<?php echo JText::_('LM_TOTAL')?>';
		case 'LM_SELECT': return '<?php echo JText::_('LM_SELECT')?>';		
		case 'LM_VIEW_FILTER': return '<?php echo JText::_('LM_VIEW_FILTER')?>';
		case 'LM_APPLY': return '<?php echo JText::_('LM_APPLY')?>';
		case 'LM_ORDER_TOOLTIP': return '<?php echo JText::_('LM_ORDER_TOOLTIP')?>';
		case 'LM_ORDER': return '<?php echo JText::_('LM_ORDER')?>';
		case 'LM_ORDER_DESC_TOOLTIP': return '<?php echo JText::_('LM_ORDER_DESC_TOOLTIP')?>';
		case 'LM_ORDER_DESC': return '<?php echo JText::_('LM_ORDER_DESC')?>';
		case 'LM_DISABLE_FILTER': return '<?php echo JText::_('LM_DISABLE_FILTER')?>';
		case 'LM_DISABLE_FILTER_TOOLTIP': return '<?php echo JText::_('LM_DISABLE_FILTER_TOOLTIP')?>';
		case 'LM_YES_VALUE': return '<?php echo JText::_('LM_YES_VALUE')?>';
		case 'LM_NO_VALUE': return '<?php echo JText::_('LM_NO_VALUE')?>';
		case 'LM_STAR_0': return '<?php echo JText::_('LM_STAR_0')?>';
		case 'LM_STAR_1': return '<?php echo JText::_('LM_STAR_1')?>';
		case 'LM_STAR_2': return '<?php echo JText::_('LM_STAR_2')?>';
		case 'LM_STAR_3': return '<?php echo JText::_('LM_STAR_3')?>';
		case 'LM_STAR_4': return '<?php echo JText::_('LM_STAR_4')?>';		
		case 'LM_RATE': return '<?php echo JText::_('LM_RATE')?>';
		case 'LM_FILTER_SHOW': return '<?php echo JText::_('LM_FILTER_SHOW')?>';
		case 'LM_FILTER_APPLY': return '<?php echo JText::_('LM_FILTER_APPLY')?>';		
		case 'LM_PAYPAL_BUTTON': return '<?php echo JText::_('LM_PAYPAL_BUTTON')?>';
		case 'LM_CARD_OPEN': return '<?php echo JText::_('LM_CARD_OPEN')?>';
		case 'LM_CARD_OPEN_TOOLTIP': return '<?php echo JText::_('LM_CARD_OPEN_TOOLTIP')?>';
		case 'LM_DISABLE_FILTER_MOD': return '<?php echo JText::_('LM_DISABLE_FILTER_MOD')?>';
		case 'LM_FILTER_APPLY_MOD': return '<?php echo JText::_('LM_FILTER_APPLY_MOD')?>';
		case 'LM_TOGGLE_NAVIGATION': return '<?php echo JText::_('LM_TOGGLE_NAVIGATION')?>';		
		case 'LM_FILTER_HIDE': return '<?php echo JText::_('LM_FILTER_HIDE')?>';
		case 'LM_DETAILRTF_TOOLTIP': return '<?php echo JText::_('LM_DETAILRTF_TOOLTIP')?>';
		case 'LM_DETAILRTF': return '<?php echo JText::_('LM_DETAILRTF')?>';		
		case 'LM_FROM': return '<?php echo JText::_('LM_FROM')?>';
		case 'LM_TO': return '<?php echo JText::_('LM_TO')?>';
		default: return '';	
	}
}

function recogeDatos<?php echo $seed;?>(){
  listManager<?php echo $seed;?>.reset();	
  var arrCabeceras<?php echo $seed;?>=JSON.parse(LMJQ(LMJQ('#<?php echo $seed;?>cb_resultado').children()[0]).val());
  if (arrCabeceras<?php echo $seed;?>!=''){  	
	  listManager<?php echo $seed;?>.setHeaders(arrCabeceras<?php echo $seed;?>);
	  if (LMJQ(LMJQ('#<?php echo $seed;?>cb_resultado').children()[1]).val().length>0){
	  	var arrDatos<?php echo $seed;?>=JSON.parse(LMJQ(LMJQ('#<?php echo $seed;?>cb_resultado').children()[1]).val());	  	  
	  	listManager<?php echo $seed;?>.setData(arrDatos<?php echo $seed;?>);
	  } else {
		listManager<?php echo $seed;?>.setData('');
	  }
	  if (LMJQ.type(LMJQ('#<?php echo $seed;?>cb_resultado').children()[2])!=='null' &&
			  LMJQ(LMJQ('#<?php echo $seed;?>cb_resultado').children()[2]).val().length>0){
	  	var arrPrefs<?php echo $seed;?>=JSON.parse(LMJQ(LMJQ('#<?php echo $seed;?>cb_resultado').children()[2]).val());
	  	listManager<?php echo $seed;?>.setPrefs(arrPrefs<?php echo $seed;?>);
	  	// Test actual page
	  	if (listManager<?php echo $seed;?>.getTotalPages()<LMJQ('#<?php echo $seed;?>pagactual').html()){
	  		goToPage<?php echo $seed;?>(LMJQ('.<?php echo $seed;?>pagactual').html()-1);
	  	}
	  }   
  }                                                                      
}

function filtraDatos<?php echo $seed;?>(val){
	filtraDatosAutofilter<?php echo $seed;?>('search', val, false);	
}

function addFilter<?php echo $seed;?>(headerid, value, isArray,noExec,isSpecial){
	if (isArray) value=JSON.parse(value);	
	listManagerFilter<?php echo $seed;?>.setFilter(headerid,value);	
	listManager<?php echo $seed;?>.setFilter(listManagerFilter<?php echo $seed;?>);
	if ((listManager<?php echo $seed;?>.options.filter_automatic=='1' && noExec==null)||isSpecial){
		executeFilters<?php echo $seed;?>();    
	}
}

function removeFilter<?php echo $seed;?>(headerid){
	if (isArray) value=JSON.parse(value);	
	listManagerFilter<?php echo $seed;?>.delFilter(headerid);	
	listManager<?php echo $seed;?>.setFilter(listManagerFilter<?php echo $seed;?>);
	executeFilters<?php echo $seed;?>();
}

function addFilterLoaded<?php echo $seed;?>(headerid, value){
	listManagerFilter<?php echo $seed;?>.setFilterLoaded(headerid,value);	
	listManager<?php echo $seed;?>.setFilter(listManagerFilter<?php echo $seed;?>);
}

function filtraDatosAutofilter<?php echo $seed;?>(headerid, value, isArray){
	addFilter<?php echo $seed;?>(headerid, value, isArray);
	executeFilters<?php echo $seed;?>();			
}
function executeFilters<?php echo $seed;?>(moduleid){
	getOtherValues<?php echo $seed;?>(moduleid);
	repaginar<?php echo $seed;?>();
	//submitAjaxForm<?php echo $seed;?>('show',false,<?php echo $params->get('autofilter');?>);
	submitAjaxForm<?php echo $seed;?>('show',false,1);
	setTimeout(function(){LMJQ('#filternav').css('display','block');},500);	
}
function getOtherValues<?php echo $seed;?>(moduleid){
	if (moduleid==null){	
		LMJQ('#<?php echo $seed;?>cb_result_filter_content').find('[data-type]').each(function(index,elem){
			if (LMJQ(elem).attr('data-range')=='0'){
				var valuerange=new Object();					
				valuerange.f=LMJQ(elem).val();
				valuerange.t=LMJQ('[data-range-id="'+LMJQ(elem).attr('data-range-id')+'"][data-range="1"]').val();
				//if ((valuerange.f!='' || valuerange.t!='') && listManager<?php echo $seed;?>.options.filter_automatic=='0'){
				if ((valuerange.f!='' || valuerange.t!='')){
					addFilter<?php echo $seed;?>(LMJQ(elem).attr('lm_fid'), JSON.stringify(valuerange), true,true);					
				}
			}
		});
	} else {		
		LMJQ('#'+moduleid).find('[data-type]').each(function(index,elem){
			if (LMJQ(elem).attr('data-range')=='0'){
				var valuerange=new Object();					
				valuerange.f=LMJQ(elem).val();
				valuerange.t=LMJQ('#'+moduleid).find('[data-range-id="'+LMJQ(elem).attr('data-range-id')+'"][data-range="1"]').val();												
				//if ((valuerange.f!='' || valuerange.t!='') && listManager<?php echo $seed;?>.options.filter_automatic=='0'){
				if ((valuerange.f!='' || valuerange.t!='')){
					addFilter<?php echo $seed;?>(LMJQ(elem).attr('lm_fid'), JSON.stringify(valuerange), true,true);					
				}
			}
		});
	}
}
function clearAutofilter<?php echo $seed;?>(headerid, value){
	listManagerFilter<?php echo $seed;?>.clearAllFilters();
	listManager<?php echo $seed;?>.setFilter(listManagerFilter<?php echo $seed;?>);
	repaginar<?php echo $seed;?>();
  	submitAjaxForm<?php echo $seed;?>('show',false,<?php echo $params->get('autofilter');?>);  	
}


function createCardLayout<?php echo $seed;?>(isShop){
	//var autofilter=listManager<?php echo $seed;?>.printAutofilterToolCard();
	//LMJQ('#<?php echo $seed;?>cb_result_wrapper').append(listManager<?php echo $seed;?>.printAutofilterToolCard());
	// Filter
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').empty();
	LMJQ('#<?php echo $seed;?>cb_result_filter_content').empty();	
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').append(listManager<?php echo $seed;?>.printAutofilterTool());
	if (listManager<?php echo $seed;?>.options.show_filter=='1'){
		LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').find('.panel-body').show({
			complete: function(){
				LMJQ(this).closest('.panel').find('.panel-body').find('.result_filter_wrapper').isotope({
    		    	itemSelector : '.lm_filter_col'
    		    });	
			}					    			
		});    	
	}
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').find(".navbar-toggle").click(function(event) {  	
	  	event.preventDefault()    
	    var target=event.target;
	    var datatarget=LMJQ(target).attr('data-target');
	    LMJQ(datatarget).slideToggle();
	    event.stopPropagation();
	  });    
	
	LMJQ('#<?php echo $seed;?>cb_result_filter_content').append(listManager<?php echo $seed;?>.printAutofilter());
	
	LMJQ('#<?php echo $seed;?>cb_result_wrapper').append(listManager<?php echo $seed;?>.printDataCard(isShop));

	
	setTimeout(function(){
		/*LMJQ('.lmcardtoolinside').each(function(){
			LMJQ(this).animate({ left: (35-LMJQ(this).closest('.lmcardtoolinside').width())}, 300, function() {
				LMJQ(this).attr('lmview','hd');
			});				
		});
		LMJQ('.lm-tools-slider-btn').click(function(e){		
			var attrvisibility=LMJQ(this).closest('.lmcardtoolinside').attr('lmview');
			if (attrvisibility=='hd'){
				LMJQ(this).closest('.lmcardtoolinside').animate({ left: '0'}, 800, function() {
					LMJQ(this).attr('lmview','sh');
				});
			} else {
				LMJQ(this).closest('.lmcardtoolinside').animate({ left: (35-LMJQ(this).closest('.lmcardtoolinside').width())}, 500, function() {
					LMJQ(this).attr('lmview','hd');
				});				
			}			
		});*/
		LMJQ('.lm_progresson').each(function(){
			inte=LMJQ(this).attr('inte');
			prc=LMJQ(this).attr('prc');
			var porc=parseInt((prc*(LMJQ(this).closest('.lm_progress').width()))/inte); 
			LMJQ(this).css('width',porc);
		});
	},500)
	

	// Links
	LMJQ('.lm-card-button').find('button[name=imgedit]').each( function(index,elem){
		LMJQ(elem).click(function(){			
        	editValue<?php echo $seed;?>(LMJQ(this).attr('id'));
    	});
  	});

	LMJQ('.lm-card-button').find('button[name=imgdelete]').each( function(index,elem){
		LMJQ(elem).click(function(){
	    	deleteValue<?php echo $seed;?>(LMJQ(this).attr('id'));
	    });
	});
	LMJQ('.lm-card-button').find('button[name=imgdetail]').each( function(index,elem){
		LMJQ(elem).click(function(){
		    viewDetail<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});
	LMJQ('.lm-card-button').find('button[name=imgdetailpdf]').each( function(index,elem){
		LMJQ(elem).click(function(){
		    viewDetailpdf<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});
	LMJQ('.lm-card-button').find('button[name=imgdetailrtf]').each( function(index,elem){
		LMJQ(elem).click(function(){
		    viewDetailrtf<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});

	LMJQ('.thumbnail').find('div[pp_var]').each( function(index,elem){
		LMJQ(elem).click(function(){
		    goToPaypal(LMJQ(this).attr('pp_var'));
		});
	});  
	LMJQ('.thumbnail').find('.lmlinklist').each( function(index,elem){
		LMJQ(elem).click(function(event){
		  	gotoList(LMJQ(this));
		});
	});
	

	//LMJQ(".nano").nanoScroller();	
	
	setRated<?php echo $seed;?>();
	setExpander<?php echo $seed;?>();
	//observable.publish('createDataTable',{'seed':'<?php echo $seed;?>','list':'<?php echo $params->get('prefsids');?>'});
	var carItemValueClass=null;
	LMJQ('.largecontentthumbnail').each(function(index,elem){
		LMJQ(elem).find('.largecontent').hide();
		LMJQ(elem).find('.lmcardcontentblock').unbind('click');
		/*LMJQ(elem).find('.lmcarditem_data').each(function(i,e){
			LMJQ(e).removeClass('col-xs-6');
			LMJQ(e).addClass('col-xs-12');
		});*/
		LMJQ(elem).find('.lmcarditem_data').each(function(i,e){
			LMJQ(e).removeClass('span6');
			LMJQ(e).addClass('span12');
		});
		LMJQ(elem).find('.lmcardcontentblock').click(function(event){
			var target=event.target;
			LMJQ(target).closest('.largecontentthumbnail').find('.lmcardcontentblock').each(function(i,e){
				if(LMJQ(e).hasClass('islarge')){
					if(LMJQ(e).hasClass('lmcardcontentblocklarge')){
						LMJQ(e).removeClass('lmcardcontentblocklarge');
					} else {						
						LMJQ(e).addClass('lmcardcontentblocklarge');
					}
										
				} 
				/*if (LMJQ(e).find('.lmcarditem_data').hasClass('col-xs-12')){
					LMJQ(e).find('.lmcarditem_data').removeClass('col-xs-12');
					LMJQ(e).find('.lmcarditem_data').addClass('col-xs-6');
				} else {
					LMJQ(e).find('.lmcarditem_data').removeClass('col-xs-6');
					LMJQ(e).find('.lmcarditem_data').addClass('col-xs-12');
				}	*/
				if (LMJQ(e).find('.lmcarditem_data').hasClass('span12')){
					LMJQ(e).find('.lmcarditem_data').removeClass('span12');
					LMJQ(e).find('.lmcarditem_data').addClass('span6');
				} else {
					LMJQ(e).find('.lmcarditem_data').removeClass('span6');
					LMJQ(e).find('.lmcarditem_data').addClass('span12');
				}				
			});
			LMJQ(target).closest('.largecontentthumbnail').find('.largecontent').toggle('slow',function(){				
				LMJQ('.row').find('.thumbnail').uniformHeight();
			});		
			
			//LMJQ(target).closest('.largecontentthumbnail').uniformHeight();
		});
	});
	//largecontent
	LMJQ('.thumbnail').find('.lm-uniform-height').click(function(){
		setTimeout(function(){LMJQ('.row').find('.thumbnail').uniformHeight()},100);
	});
	LMJQ('.row').find('.thumbnail').uniformHeight();
	LMJQ(window).resize(function () {
		LMJQ('.row').find('.thumbnail').uniformHeight();
	});
	
}

function filterOptions<?php echo $seed;?>(){
	if (listManager<?php echo $seed;?>.options.show_filter=='1'){
		LMJQ('#<?php echo $seed;?>cb_result_filter_content').closest('.panel-body').show({
			complete: function(){
				LMJQ(this).closest('.panel').find('.panel-body').find('.result_filter_wrapper').isotope({
    		    	itemSelector : '.lm_filter_col'
    		    });	
			}					    			
		});    	
	}
}

function createTableLayout<?php echo $seed;?>(){

	// Filter
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').empty();
	LMJQ('#<?php echo $seed;?>cb_result_filter_content').empty();	
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').append(listManager<?php echo $seed;?>.printAutofilterTool());
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').find(".navbar-toggle").click(function(event) {  	
	  	event.preventDefault()    
	    var target=event.target;
	    var datatarget=LMJQ(target).attr('data-target');
	    LMJQ(datatarget).slideToggle();
	    event.stopPropagation();
	  });    	
	LMJQ('#<?php echo $seed;?>cb_result_filter_wrapper').find(".navbar-toggle span").click(function(event) {  	
	  	event.preventDefault()    
	    var target=LMJQ(event.target).closest('.navbar-toggle');
	    var datatarget=LMJQ(target).attr('data-target');
	    LMJQ(datatarget).slideToggle();
	    event.stopPropagation();
	  });    	
	LMJQ('#<?php echo $seed;?>cb_result_filter_content').append(listManager<?php echo $seed;?>.printAutofilter());
	filterOptions<?php echo $seed;?>();
	postAutoFilter('#<?php echo $seed;?>cb_result_filter_content');
	var table=LMJQ('<table id="<?php echo $seed;?>list_table" class="table table-responsive <?php echo $params->get('table_striped')?'table-striped':'';?> <?php echo $params->get('table_hover')?'table-hover':'';?> <?php echo $params->get('table_condensed')?'table-condensed':'';?> <?php echo $params->get('table_bordered')?'table-bordered':'';?>"  seed="<?php echo $seed;?>"></table>');
	var thead=LMJQ('<thead></thead>');	
	//LMJQ(thead).append(listManager<?php echo $seed;?>.printAutofilterTool());		
	//LMJQ(thead).append(listManager<?php echo $seed;?>.printAutofilter());  
	//LMJQ(thead).append(listManager<?php echo $seed;?>.printAutofilter2());
	LMJQ(thead).append(listManager<?php echo $seed;?>.printHeader());
	LMJQ(table).append(thead);
	LMJQ(table).append(listManager<?php echo $seed;?>.printData());
	LMJQ(table).append(listManager<?php echo $seed;?>.printTotales()); 

	var table_wrapper=LMJQ('<div>',{'class':'table-responsive','style':'overflow-x:auto;overflow-y:hidden;'});
	LMJQ(table_wrapper).append(table);
	LMJQ('#<?php echo $seed;?>cb_result_wrapper').append(table_wrapper);
	
	//var total=LMJQ('<table id="<?php echo $seed;?>total_table" class="lm_list_table"></table>');

	LMJQ(table).find('[name=imgorder]').each(function(index,elem){
		LMJQ(elem).click(function(){orderForColumn<?php echo $seed;?>(LMJQ(elem).attr('idorder'),'asc');});        
	});
	LMJQ(table).find('[name=imgorderdesc]').each( function(index,elem){
		LMJQ(elem).click(function(){orderForColumn<?php echo $seed;?>(LMJQ(elem).attr('idorder'),'desc');});        
	});
	  
	LMJQ(table).find('[name=imgedit]').each( function(index,elem){
		LMJQ(elem).click(function(event){
			event.stopPropagation();
        	editValue<?php echo $seed;?>(LMJQ(this).attr('id'));
    	});
  	});

	LMJQ(table).find('[name=imgdelete]').each( function(index,elem){
		LMJQ(elem).click(function(event){
			event.stopPropagation();
	    	deleteValue<?php echo $seed;?>(LMJQ(this).attr('id'));
	    });
	});
	LMJQ(table).find('[name=imgdetail]').each( function(index,elem){
		LMJQ(elem).click(function(event){
			event.stopPropagation();
		    viewDetail<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});
	LMJQ(table).find('[name=imgdetailpdf]').each( function(index,elem){
		LMJQ(elem).click(function(event){
			event.stopPropagation();
		    viewDetailpdf<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});
	LMJQ(table).find('[name=imgdetailrtf]').each( function(index,elem){
		LMJQ(elem).click(function(event){
			event.stopPropagation();
		    viewDetailrtf<?php echo $seed;?>(LMJQ(this).attr('id'));
		});
	});
	<?php if($listing['detail_onclick']) : ?>
	LMJQ(table).find('td').each( function(index,elem){
		if (LMJQ(elem).has('.lm_rate_wrapper').length + LMJQ(elem).has('.lm_lasttd_wrapper').length<=0){
			LMJQ(elem).click(function(event){
				var target=event.target;
				if (!LMJQ(target).hasClass('lmlinklist'))			
			    	viewDetail<?php echo $seed;?>(LMJQ(this).closest('tr').find('[name=identify]').val());
			});
			LMJQ(elem).css('cursor','pointer');
		}
	});<?php endif; ?>	
	LMJQ(table).find('div[pp_var]').each( function(index,elem){
		LMJQ(elem).click(function(){
		    goToPaypal(LMJQ(this).attr('pp_var'));
		});
	});  
	LMJQ(table).find('.lmlinklist').each( function(index,elem){
		LMJQ(elem).click(function(event){
		  	gotoList(LMJQ(this));
		});
	});
	LMJQ('#<?php echo $seed;?>cb_result_filter_content').find('.result_filter_wrapper').isotope({
    	itemSelector : '.lm_filter_col'
    });
	setRated<?php echo $seed;?>();
	//setWidthTotales<?php echo $seed;?>();
	setExpander<?php echo $seed;?>();
	setIMGCombo<?php echo $seed;?>();
}

function setIMGCombo<?php echo $seed;?>(){
	var isimg=false;
	LMJQ('.lmtoolfilter').find('select').each(function(index,elem){
		var isimg=false;
		LMJQ(elem).find('option').find('img').each(function(indeximg,elemimg){
			LMJQ(elemimg).closest('option').attr('data-imagesrc',LMJQ(elemimg).attr('src'));
			LMJQ(elem).addClass('imgcombo');
			LMJQ(elemimg).remove();
			isimg=true;			 
		});
		if (isimg){
			LMJQ(elem).closest('.lm_autofilter').append('<input type="hidden" lm_fid="'+LMJQ(elem).attr('lm_fid')+'" seed="'+LMJQ(elem).attr('seed')+'" id="'+LMJQ(elem).attr('id')+'">');
		}
	});	
	LMJQ('.imgcombo').ddslick({
	    selectText: "",
	    width:130,
	    background: "#fff",
	    onSelected: function (data) {
	    	var hddn=LMJQ(data.selectedItem).closest('.lm_autofilter').find('input').filter('[lm_fid]');
	        LMJQ(hddn).val(data.selectedData.value);
	    	addFilter<?php echo $seed;?>(LMJQ(hddn).attr('lm_fid'), data.selectedData.value,false);
	    }
	});
}

function createTableData<?php echo $seed;?>(){	
  var arrtotales=new Array();  
  //$('<?php echo $seed;?>list_table').empty();
  
  //listManager<?php echo $seed;?>.setPagAct(LMJQ('#<?php echo $seed;?>pagactual').html());
  //listManager<?php echo $seed;?>.setPorPag(LMJQ('#<?php echo $seed;?>porpag').val());
  //LMJQ('#<?php echo $seed;?>pagtotales').html(listManager<?php echo $seed;?>.getTotalPages());
  listManager<?php echo $seed;?>.setPagAct(LMJQ('.<?php echo $seed;?>pagactual').html());
  listManager<?php echo $seed;?>.setPorPag(LMJQ('.<?php echo $seed;?>porpag').val());
  LMJQ('.<?php echo $seed;?>pagtotales').html(listManager<?php echo $seed;?>.getTotalPages());
  LMJQ('#<?php echo $seed;?>cb_result_wrapper').empty();
  <?php if($listing['list_type']=='0'){ ?>
  // Table Layout
  createTableLayout<?php echo $seed;?>();
  <?php } elseif($listing['list_type']=='1'){ ?>
  // Card Layout
  createCardLayout<?php echo $seed;?>(false);
  <?php } elseif($listing['list_type']=='2'){ ?>
  // Shop Layout
  createCardLayout<?php echo $seed;?>(true);
  <?php } ?>
  observable.publish('createDataTable',{'seed':'<?php echo $seed;?>','list':'<?php echo $params->get('prefsids');?>'});
  
}
function setExpander<?php echo $seed;?>(){
	LMJQ('.expandable').each(function(index,elem){
		if (LMJQ(elem).text().trim().length>0)
			LMJQ(elem).readmore({substr_len:LMJQ(elem).attr('rm_word')});
	});	
	
}

function submitAjaxForm<?php echo $seed;?>(param,validate,recalc){
	// Validar formulario
  if (!validate||validateForm<?php echo $seed;?>(LMJQ('#<?php echo $seed;?>list_adminForm'))){  
	  LMJQ('#<?php echo $seed;?>list_adminForm').find('[name=task]').val(param);
	  if (param=='show')
		  LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=controller]').val('serverpages');
	  else
		  LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=controller]').val('listmanager');
      
      // Filtros
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=filter]').val(listManagerFilter<?php echo $seed;?>.getJSONFilter());
      // Sort
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
	  // Recalc
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=recalc]').val(recalc);
      // Pages
      var pagact=LMJQ('.<?php echo $seed;?>porpag').val();
      var from=(LMJQ('.<?php echo $seed;?>pagactual').html()-1)*pagact;
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=from]').val(from);      
      // Guardamos la consulta
      LMJQ('#<?php echo $seed;?>queryString').val(LMJQ('#<?php echo $seed;?>list_adminForm').serialize());
      if (LMJQ('#<?php echo $seed;?>lm_intform')!=null){
	       //$('<?php echo $seed;?>lm_intform').destroy();
	       LMJQ('#<?php echo $seed;?>lm_intform').empty();
	       LMJQ('#<?php echo $seed;?>newForm').empty();
      }      
       // Create form element
      var form = LMJQ('<form>',{
        action :"index.php",
        'id' :'<?php echo $seed;?>lm_intform'
      });

      if(validate) eval(arrEditores<?php echo $seed;?>);
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name^=fld]').each(function(index,elem){
        elemTag=LMJQ(elem).prop('tagName');
        elemName="";
        elemValue=""; 
        if (elemTag=='SELECT' && LMJQ(elem).attr('multiple')!==undefined){            
        	elemName=LMJQ(elem).attr('name');
          	elemValue='';              
          	LMJQ(elem).find('option:selected').each(function(ind,opt){
	          if (ind>0) elemValue+='#';	            
        	  elemValue+=LMJQ(opt).val();
          	});
          	elemName=LMJQ(elem).attr('name')+'[]';
          	var elemTemp = LMJQ('<input>',{
                  type:'hidden',
                  name:elemName,         
                  value:elemValue
              });
          	LMJQ(elemTemp).appendTo(form);          
        } else {
        	if (LMJQ(elem).attr('type')=='checkbox'){
                if(LMJQ(elem).is(':checked')){
	            	elemName=LMJQ(elem).attr('name');          
		            elemValue=LMJQ(elem).val();
                }
            } else {
	            elemName=LMJQ(elem).attr('name');          
	            elemValue=LMJQ(elem).val();                
            }              
        }
        if (!(elemTag=='SELECT' && LMJQ(elem).attr('multiple')=='multiple')){	        
	        var elemTemp = LMJQ('<input>',{
	          type:'hidden',
	          name:elemName,         
	          value:elemValue
	        });         
	        LMJQ(elemTemp).appendTo(form);
        }
      }); 
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[type=hidden]').each(function(index,elem){
        var elemTemp = LMJQ('<input>',{
          type:'hidden',
          name:LMJQ(elem).attr('name'),
          value:LMJQ(elem).val()
        });
        LMJQ(elemTemp).appendTo(form);           
      });
      <?php // Security field
    			$session = JFactory::getSession();
    			$randSS=rand(0, PHP_INT_MAX);
    			$session->set('LM_SS_225541',$randSS);
    			?>
      LMJQ('#<?php echo $seed;?>list_adminForm').find('[name="<?php echo $randSS;?>"]').each(function(index,elem){
          var elemTemp = LMJQ('<input>',{
            type:'hidden',
            name:LMJQ(elem).attr('name'),
            value:LMJQ(elem).val()
          });
          LMJQ(elemTemp).appendTo(form);           
        });
      var elemTemp = LMJQ('<input>',{
      	type:'hidden',
        name:'rp',
        value:LMJQ('#<?php echo $seed;?>porpag').val()
      });
      LMJQ(elemTemp).appendTo(form);      
        
       //form.set('html',code);
      LMJQ(form).appendTo(LMJQ('#<?php echo $seed;?>newForm'));
      // class lm_spinner
      formSpinnerStart<?php echo $seed;?>();      
      LMJQ.post("<?php echo JURI::base();?>index.php", LMJQ('#<?php echo $seed;?>lm_intform').serialize())
	      .done(function( data ) {
	    	  LMJQ('#<?php echo $seed;?>cb_resultado').html(data);
	    	  if (param!='show'){
		    	  submitAjaxForm<?php echo $seed;?>("show",false,2);
		      } else {
			      recogeDatos<?php echo $seed;?>();
		          createTableData<?php echo $seed;?>();
		          formSpinnerEnd<?php echo $seed;?>();		          
		      }	    
	      }).fail(function() {
	    	  formSpinnerEnd<?php echo $seed;?>();
      }); 
      if(validate){
        ocultaForm<?php echo $seed;?>();
      }
      return true;
  } 
  return false;  
}
function formSpinnerStart<?php echo $seed;?>(){
	/*LMJQ('#<?php echo $seed;?>spinner').addClass('lm_spinner_on');
	//LMJQ('#<?php echo $seed;?>spinner').html('loading');
	LMJQ('#<?php echo $seed;?>list_table').fadeTo("300", 0.05);*/
	LMJQ('#<?php echo $seed;?>cb_result_wrapper').addClass('lm_loader');	
}
function formSpinnerEnd<?php echo $seed;?>(){
	/*LMJQ('#<?php echo $seed;?>spinner').removeClass('lm_spinner_on');
	LMJQ('#<?php echo $seed;?>spinner').html('');
    LMJQ('#<?php echo $seed;?>list_table').fadeTo("600", 1);*/
	LMJQ('#<?php echo $seed;?>cb_result_wrapper').removeClass('lm_loader');
}

function ocultaForm<?php echo $seed;?>(){  
	LMJQ('#<?php echo $seed;?>list_adminForm').reset();
  //resetear textarea  
   LMJQ('#<?php echo $seed;?>list_adminForm').find('[textarea]').each(function(index,elem){
	   LMJQ(elem).attr("text","");        
   });  
   <?php if($listing['modalform']=='0'){ ?>
   if (listManager<?php echo $seed;?>.isEffects()){ 
	   //FormSlide<?php echo $seed;?>.slideOut();
	   var options = {};
	   LMJQ( "#<?php echo $seed;?>cb_form" ).hide('blind',options,1000);
   } else {   
	   //LMJQ('#<?php echo $seed;?>cb_form').css('display', 'none');
	   LMJQ('#<?php echo $seed;?>cb_form').hide();
   }
   <?php } else { ?>
   	LMJQ("#<?php echo $seed;?>cb_form").dialog('close');
   <?php } ?>
}
function ocultaBulkForm<?php echo $seed;?>(){  
	if (listManager<?php echo $seed;?>.isEffects()){ 
	   var options = {};
	   LMJQ( "#<?php echo $seed;?>cb_bulkform" ).hide('blind',options,1000);
   } else {   
	   LMJQ('#<?php echo $seed;?>cb_bulkform').hide();
   }   
	LMJQ("#<?php echo $seed;?>cb_bulkform").dialog('close');
}
var formWidth=0;
function showForm<?php echo $seed;?>(){
	<?php if($listing['modalform']=='0'){ ?>
		var options = {};	   
	  //LMJQ( "#<?php echo $seed;?>cb_form" ).show('blind',options,1000);
	  LMJQ( "#<?php echo $seed;?>cb_form" ).show();	  	  	 
	  <?php if ($params->get('showprintform')):	?>	
		LMJQ('#<?php echo $seed;?>printform').click(function(e){
			//$('#divToPrint').html($('#<?php echo $seed;?>divPrint')[0].innerHTML).printArea({mode:"popup",popClose:true});
			//LMJQ('#divToPrint').html(LMJQ('#<?php echo $seed;?>divPrint').clone().find('script').remove().end().html()).printArea({mode:"popup",popClose:true,retainAttr:keepAttr});
			cloneTo(LMJQ('#<?php echo $seed;?>divPrint').find('script').remove().end(),LMJQ('#divToPrint'));
			LMJQ('#divToPrint').printArea({mode:"popup",popClose:true,retainAttr:"value"});
		});	
		<?php endif; ?>	  
	  <?php } else { ?>	
	  if (LMJQ.browser.msie && parseInt(LMJQ.browser.version)<8) {
		  	var width_content=LMJQ('#<?php echo $seed;?>cb_form').width();
		  	LMJQ( "#<?php echo $seed;?>cb_form" ).dialog({
				modal: true,
				zIndex: 90,
				width:width_content+'px',
				buttons: {
					<?php echo JText::_('LM_PRINT')?>: function (e) {
						var target=e.target;			
						/*var newHTML=LMJQ(target).closest('.ui-dialog').find('.divPrint').clone().find('script').remove().end().html();
						LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:true,retainAttr:"value"});*/
						cloneTo(LMJQ(target).closest('.ui-dialog').find('.divPrint').find('script').remove().end(),LMJQ('#divToPrint'));
						LMJQ('#divToPrint').printArea({mode:"popup",popClose:true,retainAttr:"value"});
                    }
                } ,
                open: function() {
                	LMJQ(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .removeClass("ui-dialog-titlebar-close")
                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
                }
			});
	  	} else {
	  		if (listManager<?php echo $seed;?>.isEffects()) {
		  		LMJQ( "#<?php echo $seed;?>cb_form" ).dialog({
					modal: true,
					zIndex: 90,
					show: 'slide',
					hide: 'slide',
					width:formWidth,
	                open: function() {
	                	LMJQ(this).closest(".ui-dialog")
	                    .find(".ui-dialog-titlebar-close")
	                    .removeClass("ui-dialog-titlebar-close")
	                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
	                },
					buttons: {
						<?php echo JText::_('LM_PRINT')?>: function (e) {							
							var target=e.target;			
							/*var newHTML=LMJQ(target).closest('.ui-dialog').find('.divPrint').clone().find('script').remove().end().html();
							LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:true,retainAttr:"value"});*/
							cloneTo(LMJQ(target).closest('.ui-dialog').find('.divPrint').find('script').remove().end(),LMJQ('#divToPrint'));
							LMJQ('#divToPrint').printArea({mode:"popup",popClose:true,retainAttr:"value"});          	
	                    }
	                } 		
				});
	  		}
	  		LMJQ( "#<?php echo $seed;?>cb_form" ).dialog({
				modal: true,
				zIndex: 90,
				width:formWidth,
				buttons: {
					<?php echo JText::_('LM_PRINT')?>: function (e) {
						var target=e.target;			
						/*var newHTML=LMJQ(target).closest('.ui-dialog').find('.divPrint').clone().find('script').remove().end().html();
						LMJQ('#divToPrint').html(newHTML).printArea({mode:"popup",popClose:false,retainAttr:"value"});*/
						cloneTo(LMJQ(target).closest('.ui-dialog').find('.divPrint').find('script').remove().end(),LMJQ('#divToPrint'));
						LMJQ('#divToPrint').printArea({mode:"popup",popClose:true,retainAttr:"value"});
                    }
                } ,
                open: function() {
                	LMJQ(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .removeClass("ui-dialog-titlebar-close")
                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
                }
			});
	  	}

	  	<?php if ($listing['editor']==1): ?>	  	
	  		listManager<?php echo $seed;?>.reinitializeHTMLEditors();
	  	<?php endif; ?>
	<?php } ?>
	//eval(arrEditoresSetters<?php echo $seed;?>);			
}
function showBulkForm<?php echo $seed;?>(){
	 if (LMJQ.browser.msie && parseInt(LMJQ.browser.version)<8) {
	  	var width_content=LMJQ('#<?php echo $seed;?>cb_bulkform').width();
	  	LMJQ( "#<?php echo $seed;?>cb_form" ).dialog({
			modal: true,
			zIndex: 90,
			width:width_content+'px'
		});
  	} else {
  		if (listManager<?php echo $seed;?>.isEffects()) {
	  		LMJQ( "#<?php echo $seed;?>cb_bulkform" ).dialog({
				modal: true,
				zIndex: 90,
				show: 'slide',
				hide: 'slide',
				width:formWidth,
                open: function() {
                	LMJQ(this).closest(".ui-dialog")
                    .find(".ui-dialog-titlebar-close")
                    .removeClass("ui-dialog-titlebar-close")
                    .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
                }				
			});
  		}
  		LMJQ( "#<?php echo $seed;?>cb_bulkform" ).dialog({
			modal: true,
			zIndex: 90,
			width:formWidth,
            open: function() {
            	LMJQ(this).closest(".ui-dialog")
                .find(".ui-dialog-titlebar-close")
                .removeClass("ui-dialog-titlebar-close")
                .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
            }
		});
  	}
}

function setPorpag<?php echo $seed;?>(target){
  var str=LMJQ(target).val(); 
  var val=str;
  if(val!='<?php echo JText::_('LM_ALL');?>'){
	  val = parseInt(str);
	  if (isNaN(val)||val==0) val=<?php echo $params->get('items_view')!=''?$params->get('items_view'):'10';?>;	  
  } else {
	  val = 999999;
  }   
  //LMJQ(target).val(val);
  LMJQ('.'+LMJQ(target).attr('id')).val(val);
  repaginar<?php echo $seed;?>();
  //createTableData<?php echo $seed;?>();  
  submitAjaxForm<?php echo $seed;?>('show',false,0);
} 

function showAll<?php echo $seed;?>(target){
	//var totalrecords= parseInt($('<?php echo $seed;?>pagtotales').value)*parseInt($('<?php echo $seed;?>porpag').value);
	LMJQ(target).closest('li').find('.<?php echo $seed;?>porpag').val('<?php echo JText::_('LM_ALL');?>');
	setPorpag<?php echo $seed;?>(LMJQ(target).closest('li').find('.<?php echo $seed;?>porpag'));			
}
//window.addEvent('load', function() {
	function sleep(delay) {
        var start = new Date().getTime();
        while (new Date().getTime() < start + delay);
      }
LMJQ(document).ready(function($) {	  
	// Form width
	//formWidth=LMJQ('#<?php echo $seed;?>list_tableform').width()+32;
	formWidth='auto';
	// items per page
	//LMJQ('#<?php echo $seed;?>porpag').val('<?php echo $params->get('items_view')!=''?$params->get('items_view'):'10';?>');
	LMJQ('.<?php echo $seed;?>porpag').val('<?php echo $params->get('items_view')!=''?$params->get('items_view'):'10';?>');
	// order
	//orderForColumnSort<?php echo $seed;?>();	
	// filter
	<?php if ($filterloaded!=null){ ?>	
	addFilterLoaded<?php echo $seed;?>('<?php echo $filterloaded['headerid'];?>','<?php echo $filterloaded['value'];?>', false);
	<?php }?>

	<?php /*if ($listing['default_order']!=null){ ?>	
	var deforder=JSON.parse('<?php echo $listing['default_order'];?>');
	for(var i=0;i<deforder.length;i++){
		var tmpdeforder=deforder[i];
		listManagerSorter<?php echo $seed;?>.setOrder(tmpdeforder.headerId,tmpdeforder.order);
	}	
	<?php }*/?>
	
	// first submit
	<?php if(!$listing['hidelist']) { ?>	
	submitAjaxForm<?php echo $seed;?>("show",false,2);
	<?php } ?>

	// slider
	//if (listManager<?php echo $seed;?>.isEffects()) 
		//FormSlide<?php echo $seed;?> = new Fx.Slide('<?php echo $seed;?>cb_form',{duration:2000});
		
	LMJQ('.radgroup').find('input[type=radio]').each(function(index,elem){
		LMJQ(elem).click(function(){
			LMJQ(this).closest('.radgroup').find('input[type=hidden][name='+LMJQ(this).attr('rad_name')+']').val(LMJQ(this).val());
		});
	});

	/*LMJQ('#<?php echo $seed;?>porpag').keyup(function(e) { 
	        str=LMJQ('#<?php echo $seed;?>porpag').val();
	        str = str.replace(/[^0-9]/g,''); // remove everything except the
	        LMJQ('#<?php echo $seed;?>porpag').val(str);	        
	        if (e.key == 'enter'){
	          setPorpag<?php echo $seed;?>();
	        }          
	  });
	LMJQ('#<?php echo $seed;?>porpag').keydown(function(e) { 
	        if (e.code=='9'){
	          setPorpag<?php echo $seed;?>();
	        }          
	  }); 
	  
	LMJQ('#<?php echo $seed;?>porpag').change(function(e) { setPorpag<?php echo $seed;?>();});
	*/
	LMJQ('.<?php echo $seed;?>porpag').keyup(function(e) {
		var target=e.target; 
        str=LMJQ(target).val();
        str = str.replace(/[^0-9]/g,''); // remove everything except the
        LMJQ(target).val(str);	        
        if (e.key == 'enter'){
          setPorpag<?php echo $seed;?>(target);
        }          
	  });
	LMJQ('.<?php echo $seed;?>porpag').keydown(function(e) { 
		var target=e.target; 
	        if (e.code=='9'){
	          setPorpag<?php echo $seed;?>(target);
	        }          
	  }); 
	  
	LMJQ('.<?php echo $seed;?>porpag').change(function(e) { var target=e.target; setPorpag<?php echo $seed;?>(target);});

	  <?php if($listing['modalform']=='0'){ ?>
	  //$('<?php echo $seed;?>cb_form').setStyle('display', 'none');
	  	LMJQ( "#<?php echo $seed;?>cb_form" ).hide();
	  <?php } ?>
	  	  
	  LMJQ('#<?php echo $seed;?>queryString').hide();
	  
	  LMJQ('#<?php echo $seed;?>exportpdf').click(function(e) {  
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showPdf');
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('epdf');
		  LMJQ('#<?php echo $seed;?>exportdocuments').submit();
	    return true;
	  });      
	  
	  LMJQ('#<?php echo $seed;?>exportrtf').click(function(e) {  
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showRtf');
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('ertf');
		  LMJQ('#<?php echo $seed;?>exportdocuments').submit();
	    return true;
	  });           
	 
	  LMJQ('#<?php echo $seed;?>exportexcel').click(function(e) {
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());  
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showExcel');
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('excel');
		  LMJQ('#<?php echo $seed;?>exportdocuments').submit();
	    return true;
	  });

	  LMJQ('#<?php echo $seed;?>exportexcelfiltered').click(function(e) {
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=filter]').val(listManagerFilter<?php echo $seed;?>.getJSONFilter());
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showExcelFiltered');
		  LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('excel');
		  LMJQ('#<?php echo $seed;?>exportdocuments').submit();
		    return true;
		});
	  LMJQ('#<?php echo $seed;?>exportpdffiltered').click(function(e) {
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=filter]').val(listManagerFilter<?php echo $seed;?>.getJSONFilter());
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showPdfFiltered');
			LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('epdf');
		  	LMJQ('#<?php echo $seed;?>exportdocuments').submit();
		    return true;
		});

	  LMJQ('#<?php echo $seed;?>exportrtffiltered').click(function(e) {
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name^=filter]').val(listManagerFilter<?php echo $seed;?>.getJSONFilter());
		  	LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="task"]').val('showRtfFiltered');
			LMJQ('#<?php echo $seed;?>exportdocuments').find('[name="format"]').val('ertf');
		  	LMJQ('#<?php echo $seed;?>exportdocuments').submit();
		    return true;
		});

	  var CreateSearchObserver = function (data) {
		  filtraDatos<?php echo $seed;?>(data);
	  };
	  observable.subscribe('search',CreateSearchObserver);
	  LMJQ('.<?php echo $seed;?>filtertext').change(function(e){
		  var elem=e.target;
		  LMJQ('.<?php echo $seed;?>filtertext').val(elem.value);
		  filtraDatos<?php echo $seed;?>(elem.value); 
		  // add observer
		  
	  });
	  LMJQ('.<?php echo $seed;?>filtertext').keydown(function(e){
		  if (e.key == 'enter'){
			var elem=e.target;
			LMJQ('.<?php echo $seed;?>filtertext').val(elem.value);
		  	filtraDatos<?php echo $seed;?>(elem.value);
		  } 
	  });

	  /* Email */
		LMJQ('#<?php echo $seed;?>email_button').click(function(e) { sendEmail<?php echo $seed;?>(false);  });
		LMJQ('#<?php echo $seed;?>emailfiltered_button').click(function(e) { sendEmail<?php echo $seed;?>(true);  });
	  
	<?php
		if($params->get('viewonly')!='1'){
			if (strrpos($acl,'#add#')!==false):?>	
				LMJQ('.<?php echo $seed;?>btadd').click(function(e) { addValue<?php echo $seed;?>();  });
			<?php endif;?>
	LMJQ('#<?php echo $seed;?>saveform').click(function(e){
	      submitAjaxForm<?php echo $seed;?>("save",true,2);	      
	  });
	  
	LMJQ('#<?php echo $seed;?>cancelform').click(function(e){
	    ocultaForm<?php echo $seed;?>(); 
	  });
	  
	LMJQ('#<?php echo $seed;?>list_adminForm').find('input[type=text]').each(function(index,el) {
		LMJQ(el).keydown(function(e) {
	      if (e.key=='enter'){
	        e.stop();
	        e.stopPropagation();
	      }
	    });
	  }); 
	  
	 <?php } ?>

	 // Validator	 
	LMJQ(LMJQ('#<?php echo $seed;?>list_adminForm')).validate({
		  debug:true,
		  submitHandler: function(form) {},
	   	  invalidHandler: function(event, validator) {},
		  showErrors: function(errorMap, errorList) {this.defaultShowErrors();}
		});
	//Validator
	LMJQ.validator.addMethod("2limits", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);		
	}, LMJQ.validator.format('<?php echo JText::_('LM_VALUE_BETWEEN');?>'));
	LMJQ.validator.addMethod("1limit", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);
	}, LMJQ.validator.format('<?php echo JText::_('LM_VALUE_GREATER');?>'));
	LMJQ.validator.addMethod("limit1", function(value, element, params) {
		value_temp=replaceAll(value, params[6], '' );
		return testLimit(value_temp, params[0], params[1], params[2], params[3], params[4], params[5]);
	}, LMJQ.validator.format('<?php echo JText::_('LM_VALUE_SMALLER');?>'));
	LMJQ.validator.addMethod("lmnumber", function(value, element, params) {
		//var patron = new RegExp("^[0-9]{1,3}([\\"+params[0]+"]*[0-9]{3})*(\\"+params[2]+"[0-9]{"+params[1]+"})?$");
		if(value.length<=0) return true;
		var patron = new RegExp('^[0-9]{1,3}(['+'\\'+params[0]+']*[0-9]{3})*('+'\\'+params[2]+'[0-9]{'+params[1]+'})?$');
		//var patron = new RegExp("^[0-9]{1,3}(\\"+params[0]+"[0-9]{3})*(\\"+params[2]+"[0-9]{"+params[1]+"})?$"); // estricto con el thousand
		return patron.test(value);
	}, LMJQ.validator.format('<?php echo JText::_('LM_VALUE_DECIMAL');?>'));

	// Bulk
	LMJQ('#<?php echo $seed;?>bulkbutton').click(function(){
		if(LMJQ('input[name="chkbulk"]:checked').length<=0){
			alert('<?php echo JText::_('LM_CHECK_ROWS_BULK')?>');
		} else {
			showBulkForm<?php echo $seed;?>();
		}
	});
	LMJQ('#<?php echo $seed;?>bulkdeleteform').click(function(){
		formSpinnerStart<?php echo $seed;?>();
		var arrChecked=new Array();
	      LMJQ('input[name="chkbulk"]:checked').each(function(index,elem){		      
	    	  arrChecked.push(LMJQ(elem).attr('id'));
		  });		  
	    LMJQ('#<?php echo $seed;?>list_bulkForm').find('input[name="bulkids"]').val(JSON.stringify(arrChecked));
		LMJQ('#<?php echo $seed;?>list_bulkForm').find('input[name="delete"]').val('1');      
	    LMJQ.post("<?php echo JURI::base();?>index.php", LMJQ('#<?php echo $seed;?>list_bulkForm').serialize())
		      .done(function( data ) {
		    	  console.log(data);
		    	  submitAjaxForm<?php echo $seed;?>("show",false,2);
			      formSpinnerEnd<?php echo $seed;?>();
		      }).fail(function() {
			      console.log('fail');
		    	  formSpinnerEnd<?php echo $seed;?>();
	    });  
	    ocultaBulkForm<?php echo $seed;?>(); 
	});
	LMJQ('#<?php echo $seed;?>bulksaveform').click(function(e){
	      // Get 
	      var arrChecked=new Array();
	      LMJQ('input[name="chkbulk"]:checked').each(function(index,elem){		      
	    	  arrChecked.push(LMJQ(elem).attr('id'));
		  });		  
	      LMJQ('#<?php echo $seed;?>list_bulkForm').find('input[name="bulkids"]').val(JSON.stringify(arrChecked));
	      LMJQ('#<?php echo $seed;?>list_bulkForm').find('input[name="delete"]').val('0');
	      // Nuevos valores
	      var newVals=new Array();
	      LMJQ('#<?php echo $seed;?>cb_bulkform').find('[name^=fld]').each(function(index,elem){	    	  
	        elemTag=LMJQ(elem).prop('tagName');
	        elemName="";
	        elemValue=""; 
	        if (elemTag=='SELECT' && LMJQ(elem).attr('multiple')!==undefined){            
	        	elemName=LMJQ(elem).attr('name');
	          	elemValue='';              
	          	LMJQ(elem).find('option:selected').each(function(ind,opt){
		          if (ind>0) elemValue+='#';	            
	        	  elemValue+=LMJQ(opt).val();
	          	});	          		          	          
	        } else {
	        	if (LMJQ(elem).attr('type')=='checkbox'){
	                if(LMJQ(elem).is(':checked')){
		            	elemName=LMJQ(elem).attr('name');          
			            elemValue=LMJQ(elem).val();
	                }
	            } else {
		            elemName=LMJQ(elem).attr('name');          
		            elemValue=LMJQ(elem).val();                
	            }              
	        }	        
	        if(elemValue!=''){
		        var elemObj=new Object();
	          	elemObj.name=elemName;
	          	elemObj.value=elemValue;
	          	newVals.push(elemObj);
	        }
	      });
	      LMJQ('#<?php echo $seed;?>list_bulkForm').find('input[name="newval"]').val(JSON.stringify(newVals));
	      formSpinnerStart<?php echo $seed;?>();      
	      LMJQ.post("<?php echo JURI::base();?>index.php", LMJQ('#<?php echo $seed;?>list_bulkForm').serialize())
		      .done(function( data ) {
		    	  submitAjaxForm<?php echo $seed;?>("show",false,2);
			      formSpinnerEnd<?php echo $seed;?>();
		      }).fail(function() {
			      console.log('fail');
		    	  formSpinnerEnd<?php echo $seed;?>();
	      });  
	      ocultaBulkForm<?php echo $seed;?>(); 
	});  
	  
	LMJQ('#<?php echo $seed;?>bulkcancelform').click(function(e){
	    ocultaBulkForm<?php echo $seed;?>(); 
	  });	 

	 //Touch	
	 var ses_expire=<?php echo $session->getExpire();?>;
	 var ses_initial=(ses_expire*.8)*1000;
	 var ses_delay=(ses_expire*.9)*1000;	 
	 /*new Request({
		    method: 'get',
		    url: '<?php echo JURI::base()?>index.php?option=com_listmanager&controller=touch&format=raw',
		    initialDelay: ses_initial,
		    delay: ses_delay
		}).startTimer();*/
	 //LMJQ.get( "<?php echo JURI::base()?>index.php", { option: "com_listmanager", controller: "touch", format: "raw" }).delay(ses_delay,'lmtouch');
	 window.setInterval("interval<?php echo $seed;?>",ses_delay);
	 
	 /*LMJQ(document).on('focusin', function(e) {
	    if (LMJQ(e.target).closest(".mce-window, .moxman-window").length) {
			e.stopImmediatePropagation();
		}
	});	 */
	<?php if (JRequest::getVar('lmhdetail',0)==1):?>
		LMJQ('#allcontent-wrapper-<?php echo $seed;?>').hide();
		var interval=setInterval(function(){
			var ival=LMJQ('#<?php echo $seed;?>list_table').find('[name="identify"]').first().val(); 
			if(typeof(ival) !== "undefined"){
				viewDetail<?php echo $seed;?>(ival);
				clearInterval(interval);
			}			
		 }, 500);
				
	<?php endif;?>

	// JS Jquery-bootstrap conflict	
	LMJQ('[data-toggle=dropdown]').dropdown();

	LMJQ('.lm_search').find('input').find('input[type=text]').each(function(index,el) {
		LMJQ(el).keydown(function(e) {
		      if (e.key=='enter'){
		    	  $(this).parent().find('button').click();
		      }
		    });
		  }); 
	
});
function interval<?php echo $seed;?>(){
	LMJQ.get( "<?php echo JURI::base()?>index.php", { option: "com_listmanager", controller: "touch", format: "raw" })
}
function sendEmail<?php echo $seed;?>(filtered){
	var field=LMJQ('#<?php echo $seed;?>email');
	var emailValue=LMJQ('#<?php echo $seed;?>email').val();
	LMJQ('#<?php echo $seed;?>maildocuments').find('[name^=sort]').val(listManagerSorter<?php echo $seed;?>.getJSONSorter());
	LMJQ('#<?php echo $seed;?>maildocuments').find('[name^=filter]').val(listManagerFilter<?php echo $seed;?>.getJSONFilter());
	if (filtered){ 
		emailValue=LMJQ('#<?php echo $seed;?>emailfiltered').val();
		field=LMJQ('#<?php echo $seed;?>emailfiltered');
	}
	LMJQ(field).val('');  
	LMJQ('#<?php echo $seed;?>emailValueSend').val(emailValue);
	if (filtered){
		LMJQ('#<?php echo $seed;?>mail_ids_filtered').val(listManager<?php echo $seed;?>.getAllIdRecordList());
		LMJQ('#<?php echo $seed;?>taskSend').val('sendEmailFiltered');
	} else {
		LMJQ('#<?php echo $seed;?>taskSend').val('sendEmail');
	}
	emailregexp = new RegExp("[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?");
	if (emailregexp.exec(emailValue)){
		formSpinnerStart<?php echo $seed;?>();
  	  	LMJQ.post("<?php echo JURI::base();?>index.php", LMJQ('#<?php echo $seed;?>maildocuments').serialize())
		      .done(function( data ) {
		    	  LMJQ('#<?php echo $seed;?>lm_tools_sendResult').html(data);
		    	  resultEmail=LMJQ('#<?php echo $seed;?>lm_tools_sendResult').html();	
		  	    	if (resultEmail=='1')
						alert('<?php echo JText::_( "EMAIL_SENT_OK" )?>'); 
					else
						alert(resultEmail);
		  	    	formSpinnerEnd<?php echo $seed;?>();
		      }).fail(function() {
		    	  alert(error.status+" : "+error.statusText);
		    	  formSpinnerEnd<?php echo $seed;?>();
		  });    
	    return true;	
	} else {
		alert("<?php echo JText::_( "INCORRECT_EMAIL" ); ?>");
	}
}
function setRated<?php echo $seed;?>(){
	LMJQ('.rateit').each(function(elem){
		LMJQ(this).rateit({'resetable':false});
		if(!listManager<?php echo $seed;?>.acl.isEdit(LMJQ(elem).attr('data-idfield')))
			LMJQ(this).rateit('readonly', true);		
	});	
	LMJQ('.rateit').bind('rated reset', function (e) {		
	    var ri = LMJQ(this);
	    var type = 0;
	    if(e.type == 'reset') type=1;
	    //if the use pressed reset, it will get value: 0 (to be compatible with the HTML range control), we could check if e.type == 'reset', and then set the value to  null .
	    var value = ri.rateit('value');
	    var idlisting = ri.data('idlisting'); 
	    var idrecord = ri.data('idrecord');
	    var idfield = ri.data('idfield');
	    LMJQ.ajax({
	        url: 'index.php', //your server side script
	        data: {
	        	"option":"com_listmanager",
	        	"task":"rate",
	        	"controller":"rate",
	        	"format":"raw",
	        	"idlisting":idlisting,
	        	"idrecord":idrecord,
	        	"idfield":idfield,
	        	"rate":value,
	        	"type":type
	        }, //our data
	        type: 'POST',
	        success: function (data) {
	            ri.rateit('value',data);
	            //listManager<?php echo $seed;?>.setRate(idlisting,idrecord,idfield,data);
	            submitAjaxForm<?php echo $seed;?>("show",false,1);	             
	        },
	        error: function (jxhr, msg, err) {
	            alert('error: '+msg)
	        }
	    });
	});
}
</script>

<div style="width:100%;" id="lm_wrapper"  class="<?php echo $params->get('moduleclass_sfx');?>">

<div class="">
<div class="lm_name">
  <h3><?php echo $listing['name']; ?></h3>
</div>
<div class="lm_info">
  <?php echo $listing['info']; ?>
</div>
  <div class="lm_message" id="<?php echo $seed;?>lm_message">
    <div id="<?php echo $seed;?>divmessage" class="lm_divmessage" style="opacity:0"></div>
  </div>

<!-- Inicio div form--> 
<?php include 'form.php';?>

<!--  Actions -->
<?php include 'actions.php';?>

<!--  Inicio div detail -->
<div id="<?php echo $seed;?>cb_detail" style="display:none;" class="lm_detail lm_bootstrap" title="<?php echo $listing['name']; ?>">
</div>
<div id="<?php echo $seed;?>cb_detail_base" style="display:none;">
<?php echo isset($detail)?$detail:'';?>
</div>

<br>

<div id="allcontent-wrapper-<?php echo $seed;?>">	
	<!--Inicio div tools-->
	<div class="tool-up-wrapper" <?php echo !$listing['view_toolbar']?'style="display:none"':"";?>>	
		<?php include 'tools.php';?>
	</div>
	
	
	<!-- Inicio div listado-->
	<div id="<?php echo $seed;?>cb_result1" class="lm_result" seed="<?php echo $seed;?>">
  
  <div class="special-filter">	
		<?php include 'specialfilter.php';?>
	</div>
  
	<!-- Filter -->
	<div class="panel panel-default" <?php echo !$listing['view_filter']?'style="display:none"':"";?>>
		<div id="<?php echo $seed;?>cb_result_filter_wrapper" class="panel-heading navbar-default"></div>
		<div style="display:none;" class="panel-body">		
			<div id="<?php echo $seed;?>cb_result_filter_content"></div>
		</div>
	</div>
	
	<div id="<?php echo $seed;?>cb_result_wrapper"></div>  
	</div>
	<span id="<?php echo $seed;?>queryString"></span>
	<span id="<?php echo $seed;?>newForm"></span>
	<span id="<?php echo $seed;?>lm_tools_sendResult" style="display:none"></span>
	<br/>                                        
	<div id="<?php echo $seed;?>cb_resultado" >
	<input id="cabeceras" type="hidden" value=""/>
	</div>                                       
	<br/>
	<!--Inicio div tools 2-->
	<div class="tool-bottom-wrapper" <?php echo !$listing['view_toolbar_bottom']?'style="display:none"':"";?>>
		<?php include 'tools.php';?>
	</div>
	
	<!--Inicio div bottom tools-->
	<?php include 'bottomtools.php';?>

</div>
<div style="clear:both"></div>


<!--bulk form-->
<?php 
if($params->get('viewonly')!='1'):
			if (strrpos($acl,'#bulk#')!==false):?>	
<div class="col-md-12">
<button type="button" class="btn btn-default btn-sm" id="<?php echo $seed;?>bulkbutton"><span class="glyphicon glyphicon-tasks"></span> <?php echo JText::_('LM_BULK')?></button>
<?php include 'bulk.php';?>
</div>
<?php endif;
endif;?>
</div>
<div style="clear:both"></div>
<div id="lmdatepicker_calcs" style="display:none"></div>
</div>

<div style="clear:both"></div>
<form action="<?php echo JURI::base();?>index.php" method="post" target="_blank" name="<?php echo $seed;?>detailpdfForm" id="<?php echo $seed;?>detailpdfForm">
	<input type="hidden" name="check" value="post"/> 
	<input type="hidden" name="option" value="com_listmanager"/>
	<input type="hidden" name="controller" value="serverpages"/>
	<input type="hidden" name="format" value="epdf"/>
	<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
	<input type="hidden" name="idrecord" value=""/>
	<input type="hidden" name="task" value="detailpdf"/>
	<?php echo JHTML::_( 'form.token' ); ?>	
	<div class="lmsshide">
		<input type="text" class="lmsshide" name="<?php echo $randSS;?>" value=""/>
	</div>
</form>

<form action="<?php echo JURI::base();?>index.php" method="post" target="_blank" name="<?php echo $seed;?>detailrtfForm" id="<?php echo $seed;?>detailrtfForm">
	<input type="hidden" name="check" value="post"/> 
	<input type="hidden" name="option" value="com_listmanager"/>
	<input type="hidden" name="controller" value="serverpages"/>
	<input type="hidden" name="format" value="ertf"/>
	<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
	<input type="hidden" name="idrecord" value=""/>
	<input type="hidden" name="task" value="detailrtf"/>
	<?php echo JHTML::_( 'form.token' ); ?>	
	<div class="lmsshide">
		<input type="text" class="lmsshide" name="<?php echo $randSS;?>" value=""/>
	</div>
</form>

<form action="<?php echo JURI::base();?>index.php" method="post" target="_blank" name="<?php echo $seed;?>detailForm" id="<?php echo $seed;?>detailForm">
	<input type="hidden" name="check" value="post"/> 
	<input type="hidden" name="option" value="com_listmanager"/>
	<input type="hidden" name="controller" value="serverpages"/>
	<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
	<input type="hidden" name="idrecord" value=""/>
	<input type="hidden" name="task" value="detail"/>
	<?php echo JHTML::_( 'form.token' ); ?>	
	<div class="lmsshide">
		<input type="text" class="lmsshide" name="<?php echo $randSS;?>" value=""/>
	</div>
</form>
<?php if($params->get('viewonly')!='1'):
			if (strrpos($acl,'#bulk#')!==false):?>	
<form action="#" method="get" name="<?php echo $seed;?>list_bulkForm" id="<?php echo $seed;?>list_bulkForm" class="form-validate">
<input type="hidden" name="check" value="post"/> 
	<input type="hidden" name="option" value="com_listmanager"/>
	<input type="hidden" name="controller" value="serverpages"/>
	<input type="hidden" name="format" value="raw"/>
	<input type="hidden" name="id" value="<?php echo $params->get('prefsids');?>"/>
	<input type="hidden" name="idrecord" value=""/>
	<input type="hidden" name="task" value="bulk"/>	
	<input type="hidden" name="access_type" value="<?php echo $params->get('access_type');?>"/>
	<input type="hidden" name="user_on" value="<?php echo  JFactory::getUser()->id;?>"/>
	<input type="hidden" name="from" value="0"/>
	<input type="hidden" name="filter" value=""/>
	<input type="hidden" name="sort" value=""/>
	<input type="hidden" name="recalc" value="0"/>
	<input type="hidden" name="typemodule" value="1"/>
	<input type="hidden" name="bulkids" value=""/>
	<input type="hidden" name="newval" value=""/>
	<input type="hidden" name="delete" value=""/>
	<?php echo JHTML::_( 'form.token' ); ?>	
	<div class="lmsshide">
		<input type="text" class="lmsshide" name="<?php echo $randSS;?>" value=""/>
	</div>
	</form>
	<?php endif;
endif;?>
<div style="display:none;"><div id="divToPrint" style="background-color:#fff;"></div></div>
