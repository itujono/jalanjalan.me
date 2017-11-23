<?php 
  
 /**
 * @package Contact Form Maker
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');



class HTML_contact
{
    const first_css = ".wdform-page-and-images
{
font-size:14px;
font-weight:normal;
color:#000000;
width:100%;
}

.time_box
{
border-width:1px;
margin: 0px;
padding: 0px;
text-align:right;
width:30px;
vertical-align:middle
}

.mini_label
{
font-size:10px;
font-family: 'Lucida Grande', Tahoma, Arial, Verdana, sans-serif;
}

.ch-rad-label
{
display:inline;
margin-left:5px;
margin-right:15px;
float:none;
}

.label
{
border:none;
}




.input_deactive
{
color:#999999;
font-style:italic;
border-width:1px;
margin: 0px;
padding: 0px
}

.input_active
{
color:#000000;
font-style:normal;
border-width:1px;
margin: 0px;
padding: 0px
}

.required
{
border:none;
color:red
}

.captcha_img
{
border-width:0px;
margin: 0px;
padding: 0px;
cursor:pointer;


}

.captcha_refresh
{
width:30px;
height:30px;
border-width:0px;
margin: 0px;
padding: 0px;
vertical-align:middle;
cursor:pointer;
background-image: url(components/com_contactformmaker/images/refresh_black.png);
}

.captcha_input
{
height:20px;
border-width:1px;
margin: 0px;
padding: 0px;
vertical-align:middle;
}


.phone_area_code
{
width:50px;
}

.phone_number
{
width:100px;
}";



public static function global_options($row){
	JSubMenuHelper::addEntry(JText::_('Forms'), 'index.php?option=com_contactformmaker&amp;task=forms' );

	JSubMenuHelper::addEntry(JText::_('Submissions'), 'index.php?option=com_contactformmaker&amp;task=submits' );

	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_contactformmaker&amp;task=themes' );

	JSubMenuHelper::addEntry(JText::_('Blocked IPs'), 'index.php?option=com_contactformmaker&amp;task=blocked_ips' );
	JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options',true );


	$language = JFactory::getLanguage();

	$language->load('com_contactformmaker', JPATH_SITE, null, true);	
	?>
	<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table>
			<tr>
				<td>
					<label for="public_key">Recaptcha Public Key</label>
				</td>
				<td>
					<input type="text" id="public_key" name="public_key" value="<?php echo $row->public_key; ?>"/>
				</td>
				<td rowspan="2">
					<a href="https://www.google.com/recaptcha/admin#list" target="_blank">Get ReCaptcha Keys</a>
				</td>
			</tr>
			<tr>
				<td>
					<label for="private_key">Recaptcha Private Key</label>
				</td>
				<td>
					<input type="text" id="private_key" name="private_key" value="<?php echo $row->private_key; ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="map_key">Google Map Key</label>
				</td>
				<td>
					<input type="text" id="map_key" name="map_key" value="<?php echo $row->map_key; ?>"/>
				</td>
				<td>
					<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true&pli=1" target="_blank">Get Google Map Key</a>
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td>
					<div>It may take up to 5 minutes for <br />API key change to take effect.</div>
				</td>
				<td>
					
				</td>
			</tr>
		</table>	

    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="task" value="" />
</form>
	<?php
}

public static function form_layout(&$row, &$fields){
		JRequest::setVar( 'hidemainmenu', 1 );
		$document = JFactory::getDocument();
 		$cmpnt_js_path = JURI::root(true).'/administrator/components/com_contactformmaker/js';

		$document->addScript($cmpnt_js_path.'/codemirror.js');
		$document->addScript($cmpnt_js_path.'/formatting.js');
		$document->addScript($cmpnt_js_path.'/css.js');
		$document->addScript($cmpnt_js_path.'/clike.js');
		$document->addScript($cmpnt_js_path.'/javascript.js');
		$document->addScript($cmpnt_js_path.'/jquery.min.js');
		$document->addScript($cmpnt_js_path.'/htmlmixed.js');
		$document->addScript($cmpnt_js_path.'/xml.js');
		$document->addScript($cmpnt_js_path.'/php.js');

		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_contactformmaker/css/codemirror.css');
		
		?>
<script>

Joomla.submitbutton= function (pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel') 
	{
		submitform( pressbutton );
		return;
	}
	
	if($('#autogen_layout').is(':checked'))
		$('#custom_front').val(custom_front.replace(/\s+/g, ' ').replace(/> </g, '><'));
	else
		$('#custom_front').val(editor.getValue().replace(/\s+/g, ' ').replace(/> </g, '><'));

	submitform( pressbutton );
}


var form_front ='<?php echo addslashes($row->form_front);?>';
var custom_front ='<?php echo addslashes($row->custom_front);?>';
if(custom_front=='')
	custom_front=form_front;

function insertAtCursor_form(myId, myLabel) 
{  
	if($('#autogen_layout').is(':checked'))
		return;
	myValue='<div wdid="'+myId+'" class="wdform_row">%'+myId+' - '+myLabel+'%</div>';

	line=editor.getCursor().line;
	ch=editor.getCursor().ch;
	
	text=editor.getLine(line);
	text1=text.substr(0,ch);
	text2=text.substr(ch);
	text=text1+myValue+text2;
	editor.setLine(line, text);
	editor.focus();
}


function autogen(status)
{

	if(status)
	{
		custom_front = editor.getValue();
		editor.setValue(form_front);
		editor.setOption('readOnly', true);
		autoFormat();
	}
	else
	{
		editor.setValue(custom_front);
		editor.setOption('readOnly', false);
		autoFormat();
	}
	
}

function autoFormat() {
	CodeMirror.commands["selectAll"](editor);
	editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
	editor.scrollTo(0,0);
}

</script>        

<style>
button.submit {
	width: 100%;
	padding: 10px 0;
	cursor: pointer;
	margin: 0;
}
button.submit em {
	font-size: 11px;
	font-style: normal;
	color: #999;
}
label {
	cursor: pointer;
	display: inline-block;
}
.CodeMirror {
	border: 1px solid #ccc;
	font-size: 12px;
	margin-bottom: 6px;
	background: white;
}
.field_buttons
{
max-width:200px;
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis; 
word-break: break-all; 
word-wrap: break-word;
padding: 4px 15px;
font-weight:bold;
}

p
{
font-size: 14px;
font-family: segoe ui;
text-shadow: 0px 0px 1px rgb(202, 202, 202);
}
</style>
<h2> Description</h2>
<p>To customize the layout of the form fields uncheck the Auto-Generate Layout box and edit the HTML.</p>
<p>You can change positioning, add in-line styles and etc. Click on the provided buttons to add the corresponding field.<br/> This will add the following line:
 
 <b><span class="cm-tag">&lt;div</span> <span class="cm-attribute">wdid</span>=<span class="cm-string">"example_id"</span> <span class="cm-attribute">class</span>=<span class="cm-string">"wdform_row"</span><span class="cm-tag">&gt;</span>%example_id - Example%<span class="cm-tag">&lt;/div&gt;</span></b>
 
 , where <b><span class="cm-tag">&lt;div&gt;</span></b> is used to set a row.</p>
<p>To return to the default settings you should check Auto-Generate Layout box.</p>


<h3 style="color:red"> Notice</h3>
<p>Make sure not to publish the same field twice. This will cause malfunctioning of the form.</p>

<hr/>

<form action="index.php" method="post" name="adminForm" id="adminForm"enctype="multipart/form-data">
	<label for="autogen_layout" style="font-size:20px; line-height:45px; margin-left:10px">Auto Generate Layout? </label>
	<input type="checkbox" value="1" name="autogen_layout" id="autogen_layout" <?php  if($row->autogen_layout) echo 'checked="checked"'?> />
    <input type="hidden" name="custom_front" id="custom_front" value="" />
    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="id" value="<?php echo $row->id?>" />
    <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="task" value="" />
</form>


<br/>

<?php 
	$ids 	= $fields['ids'];
	$types 	= $fields['types'];
	$labels = $fields['labels'];
	



	foreach($ids as $key => $id)
	{
		if($types[$key]!="type_section_break")
		{
		?>
		<button class="btn" onClick="insertAtCursor_form('<?php echo $ids[$key]; ?>','<?php echo $labels[$key]; ?>')" class="field_buttons" title="<?php echo $labels[$key]; ?>"><?php echo $labels[$key]; ?></button>
		<?php
		}

	}


  ?>
<button  class="submit btn" onclick="autoFormat()"><strong>Apply Source Formatting</strong>  <em>(ctrl-enter)</em></button>
<textarea id="source" name="source" style="display: none;"></textarea>


<script>
var editor = CodeMirror.fromTextArea(document.getElementById("source"), {
    lineNumbers: true,
    lineWrapping: true,
    mode: "htmlmixed",
	value: form_front
    });
	
if($('#autogen_layout').is(':checked'))
{
	editor.setOption('readOnly',  true);
	editor.setValue(form_front);
}
else
{
	editor.setOption('readOnly',  false);
	editor.setValue(custom_front);
}

$('#autogen_layout').click(function(){autogen($(this).is(':checked'))});

autoFormat();

</script>


		<?php
}

public static function form_options(&$row, &$themes){
		
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('behavior.switcher');
		JHtml::_('formbehavior.chosen', 'select');
		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');

		JRequest::setVar( 'hidemainmenu', 1 );
		
		$is_editor=false;
		
		$plugin = JPluginHelper::getPlugin('editors', 'tinymce');
		if (isset($plugin->type))
		{ 
			$editor	= JFactory::getEditor('tinymce');
			$is_editor=true;
		}
		
		$editor	= JFactory::getEditor('tinymce');

		$value="";

		$article = JTable::getInstance('content');
		if ($value) {
			$article->load($value);
		} else {
			$article->title = JText::_('Select an Article');
		}
		
			$label_id= array();		
			$label_label= array();		
			$label_type= array();			
			$label_all	= explode('#****#',$row->label_order_current);		
			$label_all 	= array_slice($label_all,0, count($label_all)-1);   	
			
		foreach($label_all as $key => $label_each) 		
		{			
			$label_id_each=explode('#**id**#',$label_each);			
			array_push($label_id, $label_id_each[0]);					
		
		$label_order_each=explode('#**label**#', $label_id_each[1]);				
			array_push($label_label, $label_order_each[0]);		
			array_push($label_type, $label_order_each[1]);		
		}			
		
		$disabled_fields = explode(',', $row->disabled_fields);
		$disabled_fields 	= array_slice($disabled_fields,0, count($disabled_fields)-1); 
		?>

<script language="javascript" type="text/javascript">
Joomla.submitbutton= function (pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') 
	{
		submitform( pressbutton );
		return;
	}
	
	if(form.mail.value!='')
	{
		subMailArr=form.mail.value.split(',');
		emailListValid=true;
		for(subMailIt=0; subMailIt<subMailArr.length; subMailIt++)
		{
		trimmedMail = subMailArr[subMailIt].replace(/^\s+|\s+$/g, '') ;
		if (trimmedMail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1)
		{
					alert( "This is not a list of valid Email addresses." );	
					emailListValid=false;
					break;
		}
		}
		if(!emailListValid)	
		return;
	}	

	submitform( pressbutton );
}

function check_isnum(e)
{
	
   	var chCode1 = e.which || e.keyCode;
    	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

function jSelectArticle(id, title, object) {
			document.getElementById('article_id').value = id;
			document.getElementById(object + '_name').value = title;
			document.getElementById('sbox-window').close();
			}
			
function remove_article()
{
	document.getElementById('id_name').value="Select an Article";
	document.getElementById('article_id').value="";
}

function set_type(type)
{
	switch(type)
	{
		case '2':
			document.getElementById('article').removeAttribute('style');
			document.getElementById('custom1').setAttribute('style','display:none');
			document.getElementById('url').setAttribute('style','display:none');
			document.getElementById('none').setAttribute('style','display:none');
			break;
			
		case '3':
			document.getElementById('article').setAttribute('style','display:none');
			document.getElementById('custom1').removeAttribute('style');
			document.getElementById('url').setAttribute('style','display:none');
			document.getElementById('none').setAttribute('style','display:none');
			break;
			
		case '4':
			document.getElementById('article').setAttribute('style','display:none');
			document.getElementById('custom1').setAttribute('style','display:none');
			document.getElementById('url').removeAttribute('style');
			document.getElementById('none').setAttribute('style','display:none');
			break;
			
		case '1':
			document.getElementById('article').setAttribute('style','display:none');
			document.getElementById('custom1').setAttribute('style','display:none');
			document.getElementById('url').setAttribute('style','display:none');
			document.getElementById('none').removeAttribute('style');
			break;
	}
}

function insertAtCursorform(myField, myValue) {  
	if(myField.style.display=="none")
	{

		tinyMCE.execCommand('mceInsertContent',false,"%"+myValue+"%");
		return;
	}

	
	   if (document.selection) {      
	   myField.focus();      
	   sel = document.selection.createRange();    
	   sel.text = myValue;    
	   }    
   else
		if (myField.selectionStart || myField.selectionStart == '0') {     
		   var startPos = myField.selectionStart;       
		   var endPos = myField.selectionEnd;      
		   myField.value = myField.value.substring(0, startPos)           
		   +  "%"+myValue+"%"        
		   + myField.value.substring(endPos, myField.value.length);   
		} 
   else {     
   myField.value += "%"+myValue+"%";    
   }


   }

function wdhide(id)
{
	document.getElementById(id).style.display="none";
}
function wdshow(id)
{
	document.getElementById(id).style.display="block";
}
   
   
function set_preview()
{
	appWidth			=parseInt(document.body.offsetWidth);
	appHeight			=parseInt(document.body.offsetHeight);

document.getElementById('modalbutton').href='<?php echo JURI::root(true) ?>/index.php?option=com_contactformmaker&amp;id=<?php echo $row->id ?>&tmpl=component&amp;test_theme='+document.getElementById('theme').value;
//document.getElementById('modalbutton').setAttribute("rel","{handler: 'iframe', size: {x:"+(appWidth-100)+", y: "+(appHeight-100)+"}}");

}

document.switcher = null;
			window.addEvent('domready', function(){
				toggler = document.id('submenu');
				element = document.id('config-document');
				if (element) {
					document.switcher = new JSwitcher(toggler, element, {cookieName: toggler.getProperty('class')});
				}
			});

gen="<?php echo $row->counter; ?>";
form_view_max=20;
</script>
<style>
.borderer
{
border-radius:5px;
padding-left:5px;
background-color:#F0F0F0;
height:19px;
width:153px;
}

fieldset.adminform1 {
border-radius: 7px;
border: 1px solid #CCC;
padding: 13px;
margin-top: 20px;
}

label
{
display:inline;
}

.btn-group.btn-group-yesno > .btn {
min-width: 84px;
padding: 2px 12px;
}

.admintable tr
{
margin-bottom: 18px;
}
#theme_chzn{
vertical-align: top;
}

.email_labels
{
position: absolute;
background: #fff;
border: solid 1px #c7c7c7;
top: 0;
left: 0;
z-index: 1000;
}

.email_labels a
{
padding: 5px;
cursor:pointer;
}

.email_labels a:hover
{
background: #ccc;
}

</style>




<form action="index.php" method="post" name="adminForm"  id="adminForm">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general_op" data-toggle="tab">General Options</a></li>
				<li><a href="#email_op" data-toggle="tab">Email Options</a></li>
				<li><a href="#actions_op" data-toggle="tab">Actions after Submission</a></li>		
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="general_op">
					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Published' ); ?>: </label>
								</div>
								<div class="controls">
									<fieldset class="radio btn-group btn-group-yesno">
										<input type="radio" id="publishedyes" name="published" value="1" <?php if($row->published==1 ) echo "checked='checked'" ?> />
										<label for="publishedyes">Yes</label>
										<input type="radio" id="publishedno" name="published" value="0" <?php if($row->published==0 ) echo "checked='checked'" ?> />
										<label for="publishedno">No</label>
									</fieldset>	
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Save data(to database)' ); ?>: </label>
								</div>
								<div class="controls">
									<fieldset class="radio btn-group btn-group-yesno">
										<input type="radio" id="savedbyes" name="savedb" value="1" <?php if($row->savedb==1 ) echo "checked='checked'" ?> />
										<label for="savedbyes">Yes</label>
										<input type="radio" id="savedbno" name="savedb" value="0" <?php if($row->savedb==0 ) echo "checked='checked'" ?> />
										<label for="savedbno">No</label>
									</fieldset>	
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Theme' ); ?>: </label>
								</div>
								<div class="controls">
									<select id="theme" name="theme" onChange="set_preview()" >
									<?php 
									foreach($themes as $theme) 
									{
										if($theme->id==$row->theme)
										{
											echo '<option value="'.$theme->id.'" selected>'.$theme->title.'</option>';
										}
										else
											echo '<option value="'.$theme->id.'">'.$theme->title.'</option>';
									}
									?>
									</select>
									<a class="modal" id="modalbutton" href="<?php echo JURI::root(true) ?>/index.php?option=com_contactformmaker&amp;id=<?php echo $row->id ?>&tmpl=component&amp;test_theme=<?php echo $row->theme ?>" rel="{handler: 'iframe', size: {x:1000, y: 520}}">
										<div class="btn">
												<span class="icon-search" title="Preview" >
												</span>
											Preview
										</div>
									</a>
									<a class="modal" id="add_theme" href="index.php?option=com_contactformmaker&amp;task=edit_css&amp;tmpl=component&amp;theme=<?php echo $row->theme ?>&amp;form_id=<?php echo $row->id ?>&amp;new=0" rel="{handler: 'iframe', size: {x:800, y: 450}}">
										<div class="btn">
												<span class="icon-edit" title="Edit Css" >
												</span>
											Edit CSS
										</div>
									</a>	
								</div>
							</div>
							<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Required fields mark' ); ?>: </label>
								</div>
								<div class="controls">
									<input type="text" id="requiredmark" name="requiredmark" value="<?php echo $row->requiredmark ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane" id="email_op">
					<div class="control-group">
						<div class="control-label">
							<label> <?php echo JText::_( 'Send E-mail' ); ?>: </label>
						</div>
						<div class="controls">
								<fieldset class="radio btn-group btn-group-yesno">
									<input type="radio" id="sendemailyes" name="sendemail" value="1" <?php if($row->sendemail==1 ) echo "checked='checked'" ?> />
									<label for="sendemailyes">Yes</label>
									<input type="radio" id="sendemailno" name="sendemail" value="0" <?php if($row->sendemail==0 ) echo "checked='checked'" ?> />
									<label for="sendemailno">No</label>
								</fieldset>	
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6" style="">
							<fieldset class="form-horizontal">
								<legend>Email to Administrator</legend>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Email to Send Submissions to' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail" name="mail" value="<?php echo $row->mail ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Email From' ); ?>: </label>
									</div>
									<div class="controls">
											<?php 
												$fields =$row->form_fields;
												$fields=explode('*:*id*:*type_submitter_mail*:*type*:*', $fields);
												$n=count($fields);
												$is_other=true;

												for($i=0; $i<$n-1; $i++)
												{
													echo '<div style="height: 20px;"><input type="radio" name="mail_from" id="mail_from'.$i.'" value="'.substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])).'"  '.(substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])) == $row->mail_from ? 'checked="checked"' : '' ).' style="margin:0px 5px 0px 0px" onclick="wdhide(\'mail_from_other\')"/><label for="mail_from'.$i.'" style="cursor:pointer">'.substr($fields[$i+1], 0, strpos($fields[$i+1], '*:*w_field_label*:*')).'</label></div>';
													
													if(substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])) == $row->mail_from)
														$is_other=false;
												}
												

											?>
											<div style="height: 20px; <?php if($n==1) echo 'display:none;' ?>">
											<input type="radio" id="other" name="mail_from" value="other" <?php if($is_other) echo 'checked="checked"' ;?> style="margin:0px 5px 0px 0px" onclick="wdshow('mail_from_other')" />
												<label for="other" style="cursor:pointer">Other</label>
											</div>
											<input type="text" style="<?php if($n==1) echo 'width:250px'; else  echo 'width:230px' ?>; margin:0px; <?php if($n!=1) echo 'margin-left:20px' ?>;<?php if($is_other) echo 'display:block'; else  echo 'display:none';?>" id="mail_from_other" name="mail_from_other" value="<?php if($is_other)  echo $row->mail_from ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'From Name' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_from_name" name="mail_from_name" value="<?php echo $row->mail_from_name ?>" style="width:250px;" />
									<img src="components/com_formmaker/images/add.png" onclick="document.getElementById('mail_from_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none;">
									<?php 
									$choise = 'document.getElementById(\'mail_from_name\')';
									echo '<div style="position:relative; top:-3px;"><div id="mail_from_labels" class="email_labels" style="display:none;">';							
									for($i=0; $i<count($label_label); $i++)			
									{ 			
										if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))			
										continue;		
										
										$param = htmlspecialchars(addslashes($label_label[$i]));			
										
										$fld_label = $param;
										if(strlen($fld_label)>30)
										{
											$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
											$fld_label = explode("\n", $fld_label);
											$fld_label = $fld_label[0] . ' ...';	
										}
									
										echo "<a onClick=\"insertAtCursorform(".$choise.",'".$param."'); document.getElementById('mail_from_labels').style.display='none';\" style=\"display:block; text-decoration:none;\">".$fld_label."</a>";	

									}
									echo '</div></div>';								
									?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label  for="reply_to" class="hasTip" > <?php echo JText::_( 'Reply to' ); ?>:<br>(if different from "Email From") </label>
									</div>
									<div class="controls">
											<?php 
												$fields =$row->form_fields;
												$fields=explode('*:*id*:*type_submitter_mail*:*type*:*', $fields);
												$n=count($fields);
												$is_other=true;

												for($i=0; $i<$n-1; $i++)
												{
													echo '
													<div style="height: 20px;">
														<input type="radio" name="reply_to" id="reply_to'.$i.'" value="'.substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])).'"  '.(substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])) == $row->reply_to ? 'checked="checked"' : '' ).' style="margin:0px 5px 0px 0px" onclick="wdhide(\'reply_to_other\')"/>
														<label for="reply_to'.$i.'" style="cursor:pointer">'.substr($fields[$i+1], 0, strpos($fields[$i+1], '*:*w_field_label*:*')).'</label>
													</div>';
													
													if(substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])) == $row->reply_to)
														$is_other=false;
												}
												

											?>
											<div style="height: 20px; <?php if($n==1) echo 'display:none;' ?>"><input type="radio" id="other1" name="reply_to" value="other" <?php if($is_other) echo 'checked="checked"' ;?> style="margin:0px 5px 0px 0px" onclick="wdshow('reply_to_other')" /><label for="other1" style="cursor:pointer">Other</label></div>
											<input type="text" style="<?php if($n==1) echo 'width:250px'; else  echo 'width:230px' ?>; margin:0px; <?php if($n!=1) echo 'margin-left:20px' ?>; <?php if($is_other) echo 'display:block'; else  echo 'display:none';?>" id="reply_to_other" name="reply_to_other" value="<?php if($is_other)  echo $row->reply_to; ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'CC' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_cc" name="mail_cc" value="<?php echo $row->mail_cc ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'BCC' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_bcc" name="mail_bcc" value="<?php echo $row->mail_bcc ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Subject' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_subject" name="mail_subject" value="<?php echo $row->mail_subject ?>" style="width:250px;" />
										<img src="components/com_formmaker/images/add.png" onclick="document.getElementById('mail_subject_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none;">
									<?php 
									$choise = 'document.getElementById(\'mail_subject\')';
									echo '<div style="position:relative; top:-3px;"><div id="mail_subject_labels" class="email_labels" style="display:none;">';							
									for($i=0; $i<count($label_label); $i++)			
									{ 			
										if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))		
										continue;		
										
										$param = htmlspecialchars(addslashes($label_label[$i]));			
										
										$fld_label = $param;
										if(strlen($fld_label)>30)
										{
											$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
											$fld_label = explode("\n", $fld_label);
											$fld_label = $fld_label[0] . ' ...';	
										}
									
										echo "<a onClick=\"insertAtCursorform(".$choise.",'".$param."'); document.getElementById('mail_subject_labels').style.display='none';\" style=\"display:block; text-decoration:none;\">".$fld_label."</a>";	

									}
									echo '</div></div>';								
									?>
									</div>
								</div>
								<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Mode' ); ?>: </label>
								</div>
								<div class="controls">
									<fieldset class="radio btn-group btn-group-yesno">
										<input type="radio" id="htmlmode" name="mail_mode" value="1" <?php if($row->mail_mode==1 ) echo "checked='checked'" ?> />
										<label for="htmlmode">HTML</label>
										<input type="radio" id="textmode" name="mail_mode" value="0" <?php if($row->mail_mode==0 ) echo "checked='checked'" ?> />
										<label for="textmode">Text</label>
									</fieldset>	
								</div>
								</div>
								
								
								<div class="control-group span10" style="border-top:1px solid #999; margin:0px;">
									<div style="margin:10px 0px">
										<label > <?php echo JText::_( 'Custom Text in Email For Administrator' ); ?>: </label>
									</div>
									<div>
										<?php 
										$choise = 'document.getElementById(\'script_mail\')';
					
										for($i=0; $i<count($label_label); $i++)			
										{ 			
											if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_mark_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha"|| $label_type[$i]=="type_button" || $label_type[$i]=="type_file_upload" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))			
											continue;		
											
											$param = "'".htmlspecialchars(addslashes($label_label[$i]))."'";
											$fld_label = $param;
											if(strlen($fld_label)>30)
											{
												$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
												$fld_label = explode("\n", $fld_label);
												$fld_label = $fld_label[0] . ' ...';	
											}	
											echo '<input type="button" class="btn" value="'.$fld_label.'" onClick="insertAtCursorform('.$choise.','.$param.')" /> ';	
										}	

											echo '<input type="button" value="\'Ip\'" onClick="insertAtCursorform('.$choise.',\'ip\')" /> ';
												
											echo '<br/><input style="margin:3px 0; font-weight:bold;" type="button" class="btn" value="All fields list" onClick="insertAtCursorform('.$choise.',\'all\')" /> ';			
										?>
									


							<?php if($is_editor) echo $editor->display('script_mail',$row->script_mail,'50%','350','40','6');
							else
							{
							?>
							<textarea name="script_mail" id="script_mail" cols="40" rows="6" style="width: 450px; height: 350px; " class="mce_editable" aria-hidden="true"><?php echo $row->script_mail ?></textarea>
							<?php

							}
							 ?>		   		   

									</div>	

								</div>
							</fieldset>
						</div>
						<div class="span6" style="">
							<fieldset class="form-horizontal">
								<legend>Email to User</legend>
								<div class="control-group">
									<div class="control-label">
										<label class="hasTip"> <?php echo JText::_( 'Send to' ); ?>: </label>
									</div>
									<div class="controls">
										<?php 
											$fields =$row->form_fields;
											$fields=explode('*:*id*:*type_submitter_mail*:*type*:*', $fields);
											$n=count($fields);
											if($n==1)
												echo 'There is no email field';
											else
											for($i=0; $i<$n-1; $i++)
											{
												echo '
												<div style="height: 20px;">
													<input type="checkbox" name="send_to'.$i.'" id="send_to'.$i.'" value="'.substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])).'"  '.(is_numeric(strpos($row->send_to, '*'.substr($fields[$i], strrpos($fields[$i], '*:*new_field*:*')+15, strlen($fields[$i])).'*')) ? 'checked="checked"' : '' ).' style="margin:0px 5px 0px 0px"/>
													<label for="send_to'.$i.'" style="cursor:pointer">'.substr($fields[$i+1], 0, strpos($fields[$i+1], '*:*w_field_label*:*')).'</label>
												</div>';
											}
										?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Email From' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_from_user" name="mail_from_user" value="<?php echo $row->mail_from_user ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'From Name' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_from_name_user" name="mail_from_name_user" value="<?php echo $row->mail_from_name_user ?>" style="width:250px;" />
										<img src="components/com_formmaker/images/add.png" onclick="document.getElementById('mail_from_name_user_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none;">
										<?php 
										$choise = 'document.getElementById(\'mail_from_name_user\')';
										echo '<div style="position:relative; top:-3px;"><div id="mail_from_name_user_labels" class="email_labels" style="display:none;">';							
										for($i=0; $i<count($label_label); $i++)			
										{ 			
											if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))		
											continue;		
											
											$param = htmlspecialchars(addslashes($label_label[$i]));			
											
											$fld_label = $param;
											if(strlen($fld_label)>30)
											{
												$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
												$fld_label = explode("\n", $fld_label);
												$fld_label = $fld_label[0] . ' ...';	
											}
										
											echo "<a onClick=\"insertAtCursorform(".$choise.",'".$param."'); document.getElementById('mail_from_name_user_labels').style.display='none';\" style=\"display:block; text-decoration:none;\">".$fld_label."</a>";	

										}
										echo '</div></div>';								
										?>
									</div>
								</div>
								
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Reply to' ); ?>:<br>(if different from "Email Form") </label>
									</div>
									<div class="controls">
										<input type="text" id="reply_to_user" name="reply_to_user" value="<?php echo $row->reply_to_user ?>" style="width:250px;" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'CC' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_cc_user" name="mail_cc_user" value="<?php echo $row->mail_cc_user ?>" style="width:250px;" />
									</div>
								</div>
								
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'BCC' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_bcc_user" name="mail_bcc_user" value="<?php echo $row->mail_bcc_user ?>" style="width:250px;" />
									</div>
								</div>
								
								<div class="control-group">
									<div class="control-label">
										<label> <?php echo JText::_( 'Subject' ); ?>: </label>
									</div>
									<div class="controls">
										<input type="text" id="mail_subject_user" name="mail_subject_user" value="<?php echo $row->mail_subject_user ?>" style="width:250px;" />
										<img src="components/com_formmaker/images/add.png" onclick="document.getElementById('mail_subject_user_labels').style.display='block';" style="vertical-align: middle; display:inline-block; margin:0px; float:none;">
										<?php 
										$choise = 'document.getElementById(\'mail_subject_user\')';
										echo '<div style="position:relative; top:-3px;"><div id="mail_subject_user_labels" class="email_labels" style="display:none;">';							
										for($i=0; $i<count($label_label); $i++)			
										{ 			
											if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))	
											continue;		
											
											$param = htmlspecialchars(addslashes($label_label[$i]));			
											
											$fld_label = $param;
											if(strlen($fld_label)>30)
											{
												$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
												$fld_label = explode("\n", $fld_label);
												$fld_label = $fld_label[0] . ' ...';	
											}
										
											echo "<a onClick=\"insertAtCursorform(".$choise.",'".$param."'); document.getElementById('mail_subject_user_labels').style.display='none';\" style=\"display:block; text-decoration:none;\">".$fld_label."</a>";	

										}
										echo '</div></div>';								
										?>
									</div>
								</div>
								<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Mode' ); ?>: </label>
								</div>
								<div class="controls">
									<fieldset class="radio btn-group btn-group-yesno">
										<input type="radio" id="htmlmode_user" name="mail_mode_user" value="1" <?php if($row->mail_mode_user==1 ) echo "checked='checked'" ?> />
										<label for="htmlmode_user">HTML</label>
										<input type="radio" id="textmode_user" name="mail_mode_user" value="0" <?php if($row->mail_mode_user==0 ) echo "checked='checked'" ?> />
										<label for="textmode_user">Text</label>
									</fieldset>	
								</div>
								</div>
								
								<div class="control-group span10" style="border-top:1px solid #999; margin:0px;">
									<div style="margin:10px 0px">
										<label > <?php echo JText::_( 'Custom Text in Email For User' ); ?>: </label>
									</div>
									<div>
										<?php 
										$choise = 'document.getElementById(\'script_mail_user\')';	
							
										for($i=0; $i<count($label_label); $i++)			
										{ 			
											if($label_type[$i]=="type_submit_reset" || $label_type[$i]=="type_editor" || $label_type[$i]=="type_map" || $label_type[$i]=="type_mark_map" || $label_type[$i]=="type_captcha"|| $label_type[$i]=="type_recaptcha"|| $label_type[$i]=="type_button" || $label_type[$i]=="type_file_upload" || $label_type[$i]=="type_send_copy" || in_array($label_id[$i], $disabled_fields))		
												continue;		
											
											$param = "'".htmlspecialchars(addslashes($label_label[$i]))."'";
											$fld_label = $param;
											if(strlen($fld_label)>30)
											{
												$fld_label = wordwrap(htmlspecialchars(addslashes($label_label[$i])), 30);
												$fld_label = explode("\n", $fld_label);
												$fld_label = $fld_label[0] . ' ...';	
											}	
											echo '<input class="btn" type="button" value="'.$fld_label.'" onClick="insertAtCursorform('.$choise.','.$param.')" /> ';	
										}
										
										echo '<input type="button" value="\'Ip\'" onClick="insertAtCursorform('.$choise.',\'ip\')" /> ';		
										echo '<br/><input style="margin:3px 0; font-weight:bold;" type="button" class="btn" value="All fields list" onClick="insertAtCursorform('.$choise.',\'all\')" /> ';					
										
										if($is_editor) echo $editor->display('script_mail_user',$row->script_mail_user,'50%','350','40','6');
										else
										{
										?>
										<textarea name="script_mail_user" id="script_mail_user" cols="40" rows="6" style="width: 450px; height: 350px; " class="mce_editable" aria-hidden="true"><?php echo $row->script_mail_user ?></textarea>
										<?php
										}
										?>		   		   

									</div>
								</div>
							</fieldset>					
						</div>
					</div>
				</div>
				
				<div class="tab-pane" id="actions_op">
					<div class="row-fluid">
						<div class="span12">
							<div class="control-group">
								<div class="control-label">
									<label> <?php echo JText::_( 'Action type' ); ?>: </label>
								</div>
								<div class="controls">
									<select id="submit_text_type" name="submit_text_type" onchange="set_type(this.value)">
										<option value="1"  <?php if($row->submit_text_type==1 ) echo "selected='selected'" ?>>Stay on Form</option>
										<option value="2"  <?php if($row->submit_text_type==2 ) echo "selected='selected'" ?>>Article</option>
										<option value="3"  <?php if($row->submit_text_type==3 ) echo "selected='selected'" ?>>Custom Text</option>
										<option value="4"  <?php if($row->submit_text_type==4 ) echo "selected='selected'" ?>>URL</option>
									</select>
								</div>
							</div>
							<div class="control-group"  id="none" <?php if($row->submit_text_type!=1) echo 'style="display:none"' ?> >
								<div class="control-label">
									<label> <?php echo JText::_( 'Stay on Form' ); ?>: </label>
								</div>
								<div class="controls">
									<i class="icon-ok"></i>
								</div>
							</div>
							<div class="control-group"  id="article" <?php if($row->submit_text_type!=2) echo 'style="display:none"' ?>   >
								<div class="control-label">
									<label> <?php echo JText::_( 'Article' ); ?>: </label>
								</div>
								<div class="controls">
									<?php 

									$name="id";
									$value=$row->article_id;
									$control_name="urlparams";

									$db		= JFactory::getDBO();
									$doc 		= JFactory::getDocument();
									$fieldName	= $control_name.'['.$name.']';
									$article = JTable::getInstance('content');
									if ($value) {
										$article->load($value);
									} else {
										$article->title = JText::_('Select an Article');
									}

									$js = "	function jSelectArticle_".$name."(id, title, object) {
										document.getElementById('article_id').value = id;
										document.getElementById('".$name."_name').value = title;
										SqueezeBox.close();
									}";
									$doc->addScriptDeclaration($js);

									$link = 'index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticle_'.$name;

									JHTML::_('behavior.modal', 'a.modal');
									$html = "\n".'<div><a class="modal" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 750, y: 500}}"><input style="background:none; border:none; font-size:11px" type="text" id="'.$name.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'"  readonly="readonly" /></a></div>';
									$html .= "\n".'<input type="hidden" id="article_id" name="article_id" value="'.(int)$value.'" />';

									echo $html;

									?>
									<span onclick="remove_article()" style="color:#000000; cursor:pointer; padding-left:5px;"><i>Remove article</i></span>			
								</div>
							</div>
							<div class="control-group" <?php if($row->submit_text_type!=3 ) echo 'style="display:none"' ?>  id="custom1">
								<div class="control-label">
									<label for="submissioni text"> <?php echo JText::_( 'Text' ); ?>: </label>
								</div>
								<div class="controls">
									<?php 
									if($is_editor) 
										echo $editor->display('submit_text',$row->submit_text,'50%','350','40','6');
									else
									{
										?>
										<textarea name="submit_text" id="submit_text" cols="40" rows="6" style="width: 450px; height: 350px; " class="mce_editable" aria-hidden="true"><?php echo $row->submit_text ?></textarea>
										<?php
									}
									?>		   		   
								</div>
							</div>
							<div class="control-group" <?php if($row->submit_text_type!=4 ) echo 'style="display:none"' ?>  id="url">
								<div class="control-label">
									<label for="submissioni text"> <?php echo JText::_( 'URL' ); ?>: </label>
								</div>
								<div class="controls">
									<input type="text" id="url" name="url" value="<?php echo $row->url ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>

				</div>
		</div>
	</div>

    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="id" value="<?php echo $row->id?>" />
    <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="task" value="" />
</form>
	<script language="javascript" type="text/javascript">
	function hide_email_labels(event)
	{

		var e = event.toElement || event.relatedTarget;
        if (e.parentNode == this || e == this) {
           return;
		}
		
		this.style.display="none";
	}
	
	if(document.getElementById('mail_from_labels'))
	document.getElementById('mail_from_labels').addEventListener('mouseout',hide_email_labels,true);
	if(document.getElementById('mail_subject_labels'))
	document.getElementById('mail_subject_labels').addEventListener('mouseout',hide_email_labels,true);
	if(document.getElementById('mail_from_name_user_labels'))
	document.getElementById('mail_from_name_user_labels').addEventListener('mouseout',hide_email_labels,true);
	if(document.getElementById('mail_subject_user_labels'))
	document.getElementById('mail_subject_user_labels').addEventListener('mouseout',hide_email_labels,true);

	set_preview();
	</script>

<?php		

       }
	   

public static function add_blocked_ips($rows){

	JRequest::setVar( 'hidemainmenu', 1 );
		
		?>
        
<script>
function check_isnum_point(e)
{
   	var chCode1 = e.which || e.keyCode;
	
	if (chCode1 ==46)
		return true;
	
	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

function submitbutton(pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel_themes') 
	{
		submitform( pressbutton );
		return;
	}


	submitform( pressbutton );
}


</script>        
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="admintable">

 
				<tr>
					<td class="key">
						<label for="title">
							IP:
						</label>
					</td>
					<td >
                                    <input type="text" name="ip" id="ip" onkeypress="return check_isnum_point(event);" size="60"/>
					</td>
				</tr>
			
</table>           
    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="task" value="" />
</form>

	   <?php	
	

}


public static function show_submits(&$rows, &$forms, &$lists, &$pageNav, &$labels, $label_titles, $rows_ord, $filter_order_Dir,$form_id, $labels_id, $sorted_labels_type, $total_entries, $total_views, $where, $where3)
{
	$label_titles_copy=$label_titles;
	JHTML::_('behavior.tooltip');
	JHTML::_('behavior.calendar');
	JHTML::_('behavior.modal');
	
	$document = JFactory::getDocument();
	$document->addScript(JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/contactform.js');
	
	$mainframe = JFactory::getApplication();
JSubMenuHelper::addEntry(JText::_('Forms'), 'index.php?option=com_contactformmaker&amp;task=forms' );
JSubMenuHelper::addEntry(JText::_('Submissions'), 'index.php?option=com_contactformmaker&amp;task=submits',true );
JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_contactformmaker&amp;task=themes' );
JSubMenuHelper::addEntry(JText::_('Blocked IPs'), 'index.php?option=com_contactformmaker&amp;task=blocked_ips' );
JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options' );
	$language = JFactory::getLanguage();
	$language->load('com_contactformmaker', JPATH_SITE, null, true);	

	$n=count($rows);
	$m=count($labels);
	$group_id_s= array();
	
	$rows_ord_none=array();
	
	if(count($rows_ord)>0 and $m)
	for($i=0; $i <count($rows_ord) ; $i++)
	{
	
		$row = &$rows_ord[$i];
	
		if(!in_array($row->group_id, $group_id_s))
		{
		
			array_push($group_id_s, $row->group_id);
			
		}
	}
	?>

<script type="text/javascript">
function generate_csv_xml(type)
{
var checked_ids ='';
$('.checked_cbs input').each(function() {
	if($(this)[0].checked==true)
	checked_ids += $(this)[0].value+',';
	
	});

window.location='index.php?option=com_contactformmaker&task='+type+'&format=row&id=<?php echo $form_id; ?>&checked_ids='+checked_ids;
}



Joomla.tableOrdering=  function( order, dir, task ) 
{
    var form = document.adminForm;
    form.filter_order2.value     = order;
    form.filter_order_Dir2.value = dir;
    submitform( task );
}

function renderColumns()
{
	allTags=document.getElementsByTagName('*');
	
	for(curTag in allTags)
	{
		if(typeof(allTags[curTag].className)!="undefined")
		if(allTags[curTag].className.indexOf('_fc')>0)
		{
			curLabel=allTags[curTag].className.replace('_fc','');
			if(document.forms.adminForm.hide_label_list.value.indexOf('@'+curLabel+'@')>=0)
				allTags[curTag].style.display = 'none';
			else
				allTags[curTag].style.display = '';
		}
	}
}

function clickLabChB(label, ChB)
{
	document.forms.adminForm.hide_label_list.value=document.forms.adminForm.hide_label_list.value.replace('@'+label+'@','');
	if(document.forms.adminForm.hide_label_list.value=='') document.getElementById('ChBAll').checked=true;
	
	if(!(ChB.checked)) 
	{
		document.forms.adminForm.hide_label_list.value+='@'+label+'@';
		document.getElementById('ChBAll').checked=false;
	}
	renderColumns();
}

function toggleChBDiv(b)
{
	if(b)
	{
		sizes=window.getSize();
		document.getElementById("sbox-overlay").style.width=sizes.x+"px";
		document.getElementById("sbox-overlay").style.height=sizes.y+"px";
		document.getElementById("ChBDiv").style.left=Math.floor((sizes.x-350)/2)+"px";
		
		document.getElementById("ChBDiv").style.display="block";
		document.getElementById("sbox-overlay").style.display="block";
	}
	else
	{
		document.getElementById("ChBDiv").style.display="none";
		document.getElementById("sbox-overlay").style.display="none";
	}
}

function clickLabChBAll(ChBAll)
{
	<?php
	if(isset($labels))
	{
		$templabels=array_merge(array('submitid','submitdate','submitterip'),$labels_id);
		$label_titles=array_merge(array('ID','Submit date', 'Submitter\'s IP Address'),$label_titles);
	}
	?>

	if(ChBAll.checked)
	{ 
		document.forms.adminForm.hide_label_list.value='';

		for(i=0; i<=ChBAll.form.length; i++)
			if(typeof(ChBAll.form[i])!="undefined")
				if(ChBAll.form[i].type=="checkbox")
					ChBAll.form[i].checked=true;
	}
	else
	{
		document.forms.adminForm.hide_label_list.value='@<?php echo implode($templabels,'@@') ?>@';

		for(i=0; i<=ChBAll.form.length; i++)
			if(typeof(ChBAll.form[i])!="undefined")
				if(ChBAll.form[i].type=="checkbox")
					ChBAll.form[i].checked=false;
	}

	renderColumns();
}

function remove_all()
{
	document.getElementById('startdate').value='';
	document.getElementById('enddate').value='';
	document.getElementById('ip_search').value='';
	<?php
		$n=count($rows);
	
	for($i=0; $i < count($labels) ; $i++)
	{
	echo "
	if(document.getElementById('".$form_id.'_'.$labels_id[$i]."_search'))
		document.getElementById('".$form_id.'_'.$labels_id[$i]."_search').value='';
	";
	}
	?>
}

function show_hide_filter()
{	
	if(document.getElementById('fields_filter').style.display=="none")
	{
		document.getElementById('fields_filter').style.display='';
		document.getElementById('filter_img').src='components/com_contactformmaker/images/filter_hide.png';
	}
	else
	{
		document.getElementById('fields_filter').style.display="none";
		document.getElementById('filter_img').src='components/com_contactformmaker/images/filter_show.png';
	}
}
</script>

<style>
.reports
{
	border:1px solid #DEDEDE;
	border-radius:11px;
	background-color:#F0F0F0;
	text-align:center;
	width:100px;
}

.bordered
{
	border-radius:8px
}

pre
{
background:none;
border:0px;
}

#fields_filter th
{
vertical-align:middle !important;
}

input[type="radio"], input[type="checkbox"] {
margin: 0px 4px 5px 5px;
}

select{
margin: 0px !important;
}

</style>
<form action="index.php?option=com_contactformmaker&task=submits" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="option" value="com_contactformmaker">
    <input type="hidden" name="task" value="submits">
<br />
    <table width="100%" style="border-collapse: separate; border-spacing: 2px;">

        <tr >
            <td align="left" width="300"> Select a form:             
                <select name="form_id" id="form_id" onchange="if(document.getElementById('startdate'))remove_all();document.adminForm.submit();">
                    <option value="0" selected="selected"> Select a Form </option>
                    <?php 
            $option='com_contactformmaker';
            
            if( $forms)
            for($i=0, $n=count($forms); $i < $n ; $i++)
        
            {
                $form = &$forms[$i];
        
        
                if($form_id==$form->id)
                {
                    echo "<option value='".$form->id."' selected='selected'>".$form->title."</option>";
                    $form_title=$form->title;
                }
                else
                    echo "<option value='".$form->id."' >".$form->title."</option>";
            }
        ?>
                    </select>
            </td>
			<?php if(isset($form_id) and $form_id>0):  ?>
			<td class="reports" ><strong>Entries</strong><br /><?php echo $total_entries; ?></td>
			<td class="reports"><strong>Views</strong><br /><?php echo $total_views ?></td>
            <td class="reports"><strong>Conversion Rate</strong><br /><?php  if($total_views) echo round((($total_entries/$total_views)*100),2).'%'; else echo '0%' ?></td>
			<td style="font-size:24px;text-align:center;">
				<?php echo $form_title ?>
			</td>
			<td style="text-align:right;" width="300">
                Export to
 				<input type="button" value="CSV" onclick="window.onload=generate_csv_xml('generate_csv')" />&nbsp;
				<input type="button" value="XML" onclick="window.onload=generate_csv_xml('generate_xml')" />  </td>
			
        </tr>
        
        <tr>

            <td colspan=5>
                <br />
                <input type="hidden" name="hide_label_list" value="<?php  echo $lists['hide_label_list']; ?>" /> 
                <img id="filter_img" src="components/com_contactformmaker/images/filter_show.png" width="40" style="vertical-align:middle; cursor:pointer" onclick="show_hide_filter()"  title="Search by fields" />
				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="remove_all();this.form.submit();" data-original-title="Clear">
				<i class="icon-remove"></i></button>
            </td>
			<td align="right">
                <br /><br />
                <?php if(isset($labels)) echo '<input type="button" class="btn" onclick="toggleChBDiv(true)" value="Add/Remove Columns" style="vertical-align: top;" />'; ?>
				<?php echo $pageNav->getLimitBox(); ?>

			</td>
        </tr>

		<?php else: echo '<td><br /><br /><br /></td></tr>'; endif; ?>
    </table>
    <table class="table table-striped" width="100%">
        <thead>
            <tr>

                <th width="3%"><?php echo '#'; ?></th>

				<th width="4%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				
				 <?php
				 echo '<th width="4%" class="submitid_fc"';
				 if(!(strpos($lists['hide_label_list'],'@submitid@')===false)) 
				 echo 'style="display:none;"';
				 echo '>';
				 echo JHTML::_('grid.sort', 'Id', 'group_id', @$lists['order_Dir'], @$lists['order'] );
				 echo '</th>';
				 
				 echo '<th width="150" class="submitdate_fc"';
				 if(!(strpos($lists['hide_label_list'],'@submitdate@')===false)) 
				 echo 'style="display:none;"';
				 echo '>';
				 echo JHTML::_('grid.sort', 'Submit date', 'date', @$lists['order_Dir'], @$lists['order'] );
				 echo '</th>';

				 echo '<th width="100" class="submitterip_fc"';
				 if(!(strpos($lists['hide_label_list'],'@submitterip@')===false)) 
				 echo 'style="display:none;"';
				 echo '>';
				 echo JHTML::_('grid.sort', 'Submitter\'s IP Address', 'ip', @$lists['order_Dir'], @$lists['order'] );
				 echo '</th>';
				 
				 
	$n=count($rows);

	
	for($i=0; $i < count($labels) ; $i++)
	{
		if(strpos($lists['hide_label_list'],'@'.$labels_id[$i].'@')===false)  $styleStr='';
		else $styleStr='style="display:none;"';
		
		
			$field_title=$label_titles_copy[$i];

			echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>'.JHTML::_('grid.sort', $field_title, $labels_id[$i]."_field", @$lists['order_Dir'], @$lists['order'] ).'</th>';
	}
?>

            </tr>
            <tr id="fields_filter" style="display:none; background:#F1F1F1">
                <th width="3%"></th>
                <th width="3%"></th>
				<th width="4%" class="submitid_fc" <?php if(!(strpos($lists['hide_label_list'],'@submitid@')===false)) echo 'style="display:none;"';?> ></th>
				
				
				<th width="150" class="submitdate_fc" style="text-align:left; <?php if(!(strpos($lists['hide_label_list'],'@submitdate@')===false)) echo 'display:none;';?>" align="center"> 
				<table class="simple_table">
					<tr class="simple_table">
						<td class="simple_table">From:</td>
						<td class="simple_table"><input class="inputbox" type="text" name="startdate" id="startdate" style="width:70px" maxlength="10" value="<?php echo $lists['startdate'];?>" /> </td>
						<td class="simple_table">
						<button class="btn" id="startdate_but"><i class="icon-calendar"></i></button>
						</td>
					</tr>
					<tr class="simple_table">
						<td class="simple_table">To:</td>
						<td class="simple_table"><input class="inputbox" type="text" name="enddate" id="enddate" style="width:70px" maxlength="10" value="<?php echo $lists['enddate'];?>" /> </td>
						<td class="simple_table">
						<button class="btn" id="enddate_but"><i class="icon-calendar"></i></button>
						</td>
					</tr>
				</table>
				
				</th>
				
				
				
				
				<th width="100"class="submitterip_fc"  <?php if(!(strpos($lists['hide_label_list'],'@submitterip@')===false)) echo 'style="display:none;"';?>>
                 <input type="text" name="ip_search" id="ip_search" value="<?php echo $lists['ip_search'] ?>" onChange="this.form.submit();" style="width:150px"/>
				</th>
				<?php				 
                    $n=count($rows);
					$ka_fielderov_search=false;
					
					if($lists['ip_search'] || $lists['startdate'] || $lists['enddate']){
						$ka_fielderov_search=true;
					}
					
                    for($i=0; $i < count($labels) ; $i++)
                    {
                        if(strpos($lists['hide_label_list'],'@'.$labels_id[$i].'@')===false)  
							$styleStr='';
                        else 
							$styleStr='style="display:none;"';
						
						if(!$ka_fielderov_search)
							if($lists[$form_id.'_'.$labels_id[$i].'_search'])
							{
								$ka_fielderov_search=true;
							} 



						switch($sorted_labels_type[$i])
						{
							case 'type_mark_map': echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>'.'</th>'; break;
							default : 			  echo '<th class="'.$labels_id[$i].'_fc" '.$styleStr.'>'.'<input name="'.$form_id.'_'.$labels_id[$i].'_search" id="'.$form_id.'_'.$labels_id[$i].'_search" type="text" value="'.$lists[$form_id.'_'.$labels_id[$i].'_search'].'"  onChange="this.form.submit();" >'.'</th>'; break;			
						
						}
						
						
                 }
                ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="100"> <?php echo $pageNav->getListFooter(); ?>

				</td>
            </tr>
        </tfoot>

        <?php
    $k = 0;
	$m=count($labels);
	$group_id_s= array();
	$l=0;
	if(count($rows_ord)>0 and $m)
	for($i=0; $i <count($rows_ord) ; $i++)
	{
	
		$row = &$rows_ord[$i];
	
		if(!in_array($row->group_id, $group_id_s))
		{
		
			array_push($group_id_s, $row->group_id);
			
		}
	}
	

	for($www=0, $qqq=count($group_id_s); $www < $qqq ; $www++)
	{	
	$i=$group_id_s[$www];
	
		$temp= array();
		for($j=0; $j < $n ; $j++)
		{
			$row = &$rows[$j];
			if($row->group_id==$i)
			{
				array_push($temp, $row);
			}
		}
		$f=$temp[0];
		$date=$f->date;
		$ip = $f->ip;

		$checked 	= JHTML::_('grid.id', $www, $group_id_s[$www]);
		$link="index.php?option=com_contactformmaker&task=submit_info&group_id=".$f->group_id."&tmpl=component";
		?>

        <tr class="<?php echo "row$k"; ?>">

              <td align="center"><?php echo $www+1;?></td>

          <td align="center" class="checked_cbs"><?php echo $checked?></td>
		  
<?php

if(strpos($lists['hide_label_list'],'@submitid@')===false)
echo '<td align="center" class="submitid_fc"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$f->group_id.'</a></td>';
else 
echo '<td align="center" class="submitid_fc" style="display:none;"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$f->group_id.'</a></td>';

if(strpos($lists['hide_label_list'],'@submitdate@')===false)
echo '<td align="center" class="submitdate_fc"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$date.'</a></td>';
else 
echo '<td align="center" class="submitdate_fc" style="display:none;"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$date.'</a></td>'; 



if(strpos($lists['hide_label_list'],'@submitterip@')===false)
echo '<td align="center" class="submitterip_fc"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$ip.'</a></td>';
else 
echo '<td align="center" class="submitterip_fc" style="display:none;"><a class="modal" href="'.$link.'" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.$ip.'</a></td>';


		$ttt=count($temp);
		for($h=0; $h < $m ; $h++)
		{		
			$not_label=true;
			for($g=0; $g < $ttt ; $g++)
			{			
				$t = $temp[$g];
				if(strpos($lists['hide_label_list'],'@'.$labels_id[$h].'@')===false)  $styleStr='';
				else $styleStr='style="display:none;"';
				if($t->element_label==$labels_id[$h])
				{
					if(strpos($t->element_value,"***map***"))
					{
						$map_params=explode('***map***',$t->element_value);
						
						$longit	=$map_params[0];
						$latit	=$map_params[1];
						
						echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><a class="modal"  href="index.php?option=com_contactformmaker&task=show_map&long='.$longit.'&lat='.$latit.'&tmpl=component" rel="{handler: \'iframe\', size: {x:630, y: 570}}">'.'Show on Map'."</a></td>";
					}
					else

						if(strpos($t->element_value,"*@@url@@*"))
						{
							echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'>'; 
							$new_files=explode("*@@url@@*", $t->element_value);

							foreach($new_files as $new_file)
							if($new_file)
							{
								$new_filename=explode('/', $new_file);
								$new_filename=$new_filename[count($new_filename)-1];
								if(strpos(strtolower($new_filename), 'jpg')!== false or strpos(strtolower($new_filename), 'png')!== false or strpos(strtolower($new_filename), 'gif')!== false or strpos(strtolower($new_filename), 'jpeg')!== false)
									echo  '<a href="'.$new_file.'" class="modal">'.$new_filename."</a></br>";
								else
									echo  '<a target="_blank" href="'.$new_file.'">'.$new_filename."</a></br>";
							}
							echo "</td>";
						}			
						else
						{
		
							echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'><pre style="font-family:inherit; white-space: pre;">'.str_replace("***br***",'<br>', $t->element_value).'</pre></td>';
						}
					$not_label=false;
				}
			}
			if($not_label)
					echo  '<td align="center" class="'.$labels_id[$h].'_fc" '.$styleStr.'></td>';
		}


?>
        </tr>

        <?php


		$k = 1 - $k;

	}

	?>

    </table>

	
	
        <?php
		    $db = JFactory::getDBO();

foreach($sorted_labels_type as $key => $label_type)
{
	if($label_type=="type_checkbox" || $label_type=="type_radio" || $label_type=="type_own_select")
	{	
		?>
<br />
<br />

        <strong><?php echo $label_titles_copy[$key]?></strong>
<br />
<br />

    <?php

		$query = "SELECT element_value FROM #__contactformmaker_submits ".$where3." AND element_label='".$labels_id[$key]."'";
		$db->setQuery( $query);
		$choices = $db->loadObjectList();
	
		if($db->getErrorNum()){
			echo $db->stderr();
			return false;}
			
	$colors=array('#2CBADE','#FE6400');
	$choices_labels=array();
	$choices_count=array();
	$all=count($choices);
	$unanswered=0;	
	foreach($choices as $key => $choice)
	{
		if($choice->element_value=='')
		{
			$unanswered++;
		}
		else
		{
			if(!in_array( $choice->element_value,$choices_labels))
			{
				array_push($choices_labels, $choice->element_value);
				array_push($choices_count, 0);
			}

			$choices_count[array_search($choice->element_value, $choices_labels)]++;
		}
	}
	array_multisort($choices_count,SORT_DESC,$choices_labels);
	?>
	<table width="50%" class="adminlist">
		<thead>
			<tr>
				<th width="20%">Choices</th>
				<th>Percentage</th>
				<th width="10%">Count</th>
			</tr>
		</thead>
    <?php 
	foreach($choices_labels as $key => $choices_label)
	{
	?>
		<tr>
			<td><?php echo str_replace("***br***",'<br>', $choices_label)?></td>
			<td><div class="bordered" style="width:<?php echo ($choices_count[$key]/($all-$unanswered))*100; ?>%; height:18px; background-color:<?php echo $colors[$key % 2]; ?>"></td>
			<td><?php echo $choices_count[$key]?></td>
		</tr>
    <?php 
	}
	
	if($unanswered){
	?>
    <tr>
    <td colspan="2" align="right">Unanswered</th>
    <td><strong><?php echo $unanswered;?></strong></th>
	</tr>

	<?php	
	}
	?>
    <tr>
    <td colspan="2" align="right"><strong>Total</strong></th>
    <td><strong><?php echo $all;?></strong></th>
	</tr>

	</table>
	<?php
	}
}
	?>

	
	
    <input type="hidden" name="boxchecked" value="0">

    <input type="hidden" name="filter_order2" value="<?php echo $lists['order']; ?>" />

    <input type="hidden" name="filter_order_Dir2" value="<?php echo $lists['order_Dir']; ?>" />

</form>
<?php 
if(isset($labels))
{
?>
<div id="sbox-overlay" style="z-index: 65555; position: fixed; top: 0px; left: 0px; visibility: visible; zoom: 1; background-color:#000000; opacity: 0.7; filter: alpha(opacity=70); display:none;" onclick="toggleChBDiv(false)"></div>
<div style="background-color:#FFFFFF; width: 350px; height: 350px; overflow-y: scroll; padding: 20px; position: fixed; top: 100px;display:none; border:2px solid #AAAAAA;  z-index:65556" id="ChBDiv">

<form action="#">
    <p style="font-weight:bold; font-size:18px;margin-top: 0px;">
    Select Columns
    </p>

    <input type="checkbox" <?php if($lists['hide_label_list']==='') echo 'checked="checked"' ?> onclick="clickLabChBAll(this)" id="ChBAll" />All</br>

	<?php 
    
        foreach($templabels as $key => $curlabel)
        {
            if(strpos($lists['hide_label_list'],'@'.$curlabel.'@')===false)
            echo '<input type="checkbox" checked="checked" onclick="clickLabChB(\''.$curlabel.'\', this)" />'.$label_titles[$key].'<br />';
            else
            echo '<input type="checkbox" onclick="clickLabChB(\''.$curlabel.'\', this)" />'.$label_titles[$key].'<br />';
        }
    
  
   
    ?>
    <br />
    <div style="text-align:center;">
        <input type="button" onclick="toggleChBDiv(false);" value="Done"  class="btn" /> 
    </div>
</form>
</div>

<?php } ?>


<script>
<?php if($ka_fielderov_search){?> 
document.getElementById('fields_filter').style.display='';
document.getElementById('filter_img').src='components/com_contactformmaker/images/filter_hide.png';
	<?php
 }?>
 
				Calendar.setup({
						inputField: "startdate",
						ifFormat: "%Y-%m-%d",
						button: "startdate_but",
						align: "Tl",
						singleClick: true,
						firstDay: 0
						});
						
				Calendar.setup({
						inputField: "enddate",
						ifFormat: "%Y-%m-%d",
						button: "enddate_but",
						align: "Tl",
						singleClick: true,
						firstDay: 0
						});


</script>

<?php


}
public static function submit_info($rows, $labels_id ,$labels_name,$labels_type){

		?>

<style>
table.submit_table {
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-color: #999999;
	border-collapse: collapse;
}

table.submit_table td {
	padding: 6px;
	border: 1px solid #fff;
	font-size:13px;
}

.field_label
{
background:#E4E4E4;
font-weight:bold;
}

.field_value
{
background:#f0f0ee;
}

</style>		
<table class="submit_table">
				<tr>
					<td class="field_label">	
							<?php echo JText::_( 'ID' ); ?>:
					</td>
					<td class="field_value">
                       <?php echo $rows[0]->group_id;?>
					</td>
				</tr>
                
                <tr>
					<td class="field_label">
							<?php echo JText::_( 'Date' ); ?>:
					</td>
					<td class="field_value">
                       <?php echo $rows[0]->date;?>
					</td>
				</tr>
                <tr>
					<td class="field_label">
							<?php echo JText::_( 'IP' ); ?>:
					</td>
					<td class="field_value">
                       <?php echo $rows[0]->ip;?>
					</td>
                </tr>
                
<?php 
foreach($labels_id as $key => $label_id)
{
	if($labels_type[$key]!='' and $labels_type[$key]!='type_editor' and $labels_type[$key]!='type_submit_reset' and $labels_type[$key]!='type_map' and $labels_type[$key]!='type_captcha' )
	{
		$element_value='';
		foreach($rows as $row)
		{
			if($row->element_label==$label_id)
			{		
				$element_value=	$row->element_value;
				break;
			}
			else
			{	
				$element_value=	'element_valueelement_valueelement_value';
				
			}
			
		}
		
		if($element_value=="element_valueelement_valueelement_value")
			continue;
			
			echo	'<tr>
					<td class="field_label">'.$labels_name[$key].'</td>
					<td class="field_value">'.str_replace("***br***",'<br>', $element_value).'</td>
					</tr>';

	}
}

?>
 </table>        
 
        <?php		
       

}

public static function show_blocked_ips(&$rows, &$pageNav, &$lists){

JSubMenuHelper::addEntry(JText::_('Forms'), 'index.php?option=com_contactformmaker&amp;task=forms');
JSubMenuHelper::addEntry(JText::_('Submissions'), 'index.php?option=com_contactformmaker&amp;task=submits' );
JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_contactformmaker&amp;task=themes' );
JSubMenuHelper::addEntry(JText::_('Blocked IPs'), 'index.php?option=com_contactformmaker&amp;task=blocked_ips', true  );
JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options' );

	JHTML::_('behavior.tooltip');	
	JHtml::_('formbehavior.chosen', 'select');

	?>
<script>
Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order_ips.value     = order;
    form.filter_order_Dir_ips.value = dir;
    submitform( task );
}

function SelectAll(obj) { obj.focus(); obj.select(); } </script>
<form action="index.php?option=com_contactformmaker" method="post" name="adminForm" id="adminForm">

    <table width="100%">

        <tr>

            <tr>

            <td align="left" width="100%">
				<input type="text" name="search_ip" id="search_ip" value="<?php echo $lists['search_ip'];?>" class="text_area"  placeholder="Search ip" style="margin:0px" />
				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('search_ip').value='';this.form.submit();" data-original-title="Clear">
				<i class="icon-remove"></i></button>
			
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $pageNav->getLimitBox(); ?>
				</div>


            </td>

        </tr>

			
			
        </tr>

    </table>

	
	   <table class="table table-striped" width="100%" >

        <thead>

            <tr>

                <th width="4%"><?php echo '#'; ?></th>
                <th width="6%"><?php echo  JHTML::_('grid.sort', 'Id', 'Id', @$lists['order_Dir'], @$lists['order'] ); ?></th>

				<th width="4%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

               <th width="70%"><?php echo JHTML::_('grid.sort', 'Ip', 'ip', @$lists['order_Dir'], @$lists['order'] ); ?></th>


            </tr>

        </thead>

        <tfoot>

            <tr>

                <td colspan="6"> <?php echo $pageNav->getListFooter(); ?> </td>

            </tr>

        </tfoot>

        <?php

	

    $k = 0;

	for($i=0, $n=count($rows); $i < $n ; $i++)

	{

		$row = &$rows[$i];

		$checked 	= JHTML::_('grid.id', $i, $row->id);

		// prepare link for id column

			$link 		= JRoute::_( 'index.php?option=com_contactformmaker&task=edit_blocked_ips&cid[]='. $row->id );

		?>

        <tr class="<?php echo "row$k"; ?>">

                      <td align="center"><?php echo $i+1?></td>
                      <td align="center"><?php echo $row->id?></td>

          <td align="center"><?php echo $checked?></td>

         <td align="center"><a href="<?php echo $link; ?>"><?php echo $row->ip; ?></a></td>

           
        </tr>

        <?php

		$k = 1 - $k;

	}

	?>

    </table>

    <input type="hidden" name="option" value="com_contactformmaker">
    <input type="hidden" name="task" value="blocked_ips">
    <input type="hidden" name="boxchecked" value="0"  >
    <input type="hidden" name="filter_order_ips" value="<?php echo $lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir_ips" value="<?php echo $lists['order_Dir']; ?>" />    
	
</form>

<?php
}


public static function show(&$rows, &$pageNav, &$lists){

JSubMenuHelper::addEntry(JText::_('Forms'), 'index.php?option=com_contactformmaker&amp;task=forms', true );
JSubMenuHelper::addEntry(JText::_('Submissions'), 'index.php?option=com_contactformmaker&amp;task=submits' );
JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_contactformmaker&amp;task=themes' );
JSubMenuHelper::addEntry(JText::_('Blocked IPs'), 'index.php?option=com_contactformmaker&amp;task=blocked_ips' );
JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options' );

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.modal');

	?>
<script>

Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order.value     = order;
    form.filter_order_Dir.value = dir;
    submitform( task );
}


 function SelectAll(obj) { obj.focus(); obj.select(); } 
 </script>
<form action="index.php?option=com_contactformmaker" method="post" name="adminForm" id="adminForm">

    <table width="100%">

        <tr>

            <td align="left" width="100%">
                <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" placeholder="Search title" style="margin:0px" />

				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('search').value='';this.form.submit();" data-original-title="Clear">
				<i class="icon-remove"></i></button>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $pageNav->getLimitBox(); ?>
				</div>


            </td>

        </tr>

    </table>

    <table class="table table-striped" width="100%" >

        <thead>

            <tr>

                <th width="4%"><?php echo '#'; ?></th>
                <th width="6%"><?php echo  JHTML::_('grid.sort', 'Id', 'Id', @$lists['order_Dir'], @$lists['order'] ); ?></th>

				<th width="4%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

                <th width="34%"><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>

                <th width="35%"><?php echo JHTML::_('grid.sort', 'Email to Send Submissions to', 'mail', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="15%"><?php echo 'Preview'; ?></th>
                <th width="15%"><?php echo 'Plugin Code<br/>(Copy to article)'; ?></th>

            </tr>

        </thead>

        <tfoot>

            <tr>

                <td colspan="7"> <?php echo $pageNav->getListFooter(); ?> </td>

            </tr>

        </tfoot>

        <?php

	

    $k = 0;

	for($i=0, $n=count($rows); $i < $n ; $i++)

	{

		$row = &$rows[$i];

		$checked 	= JHTML::_('grid.id', $i, $row->id);

		$published 	= JHTML::_('grid.published', $row, $i); 


		// prepare link for id column

		$link 		= JRoute::_( 'index.php?option=com_contactformmaker&task=edit&cid[]='. $row->id );

		?>

        <tr class="<?php echo "row$k"; ?>">

                      <td align="center"><?php echo $i+1?></td>
                      <td align="center"><?php echo $row->id?></td>

          <td align="center"><?php echo $checked?></td>

            <td align="center"><a href="<?php echo $link; ?>"><?php echo $row->title?></a></td>

            <td align="center"><?php echo $row->mail?></td>
			<td align="center"><a class="modal" id="modalbutton" href="<?php echo JURI::root(true) ?>/index.php?option=com_contactformmaker&amp;id=<?php echo $row->id ?>&tmpl=component&amp;test_theme=<?php echo $row->theme ?>" rel="{handler: 'iframe', size: {x:800, y: 420}}">Preview</a></td>
            <td align="center"><input type="text" readonly="readonly" value="{loadcontactform <?php echo $row->id?>}" onclick="SelectAll(this)" width="130"></td>

        </tr>

        <?php

		$k = 1 - $k;

	}

	?>

    </table>

    <input type="hidden" name="option" value="com_contactformmaker">
    <input type="hidden" name="task" value="forms">
    <input type="hidden" name="boxchecked" value="0"  >
    <input type="text" name="filter_order"  id="filter_order" value="<?php echo $lists['order']; ?>"  class="text_area" style="display:none"/>
    <input type="text" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" class="text_area" style="display:none" />


</form>

<?php
}

public static function edit(&$row, &$labels){
		$optionsRow = JTable::getInstance('contactformmaker_options', 'Table');
		$optionsRow->load(1);
		$key = isset($optionsRow->map_key) && $optionsRow->map_key ? '&key='.$optionsRow->map_key : '';
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$document = JFactory::getDocument();

		$cmpnt_js_path = JURI::root(true).'/administrator/components/com_contactformmaker/js';
		
	
		$document->addScript($cmpnt_js_path.'/if_gmap.js');
		
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
			$document->addScript('https://maps.google.com/maps/api/js?sensor=false'.$key);
		else	
			$document->addScript('http://maps.google.com/maps/api/js?sensor=false'.$key);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_contactformmaker/css/style.css');

		$is_editor=false;
		
		$plugin = JPluginHelper::getPlugin('editors', 'tinymce');
		if (isset($plugin->type))
		{ 
			$editor	= JFactory::getEditor('tinymce');
			$is_editor=true;
		}
		

		JHTML::_('behavior.tooltip');	
		JHTML::_('behavior.calendar');
		JHTML::_('behavior.modal');
	?>

<script type="text/javascript">
if($)
if(typeof $.noConflict === 'function'){
   $.noConflict();
}
var already_submitted=false;
Joomla.submitbutton= function (pressbutton) 
{

	if(!document.getElementById('araqel'))
	{
		alert('Please wait while page loading');
		return;
	}
	else
		if(document.getElementById('araqel').value=='0')
		{
			alert('Please wait while page loading');
			return;
		}
		
	var form = document.adminForm;

	if (already_submitted) 
	{
		submitform( pressbutton );
		return;
	}
	
	if (pressbutton == 'cancel') 

	{

		submitform( pressbutton );

		return;

	}
	
	if (form.title.value == "")

	{
				alert( "The form must have a title." );	
				return;
	}		

		
	document.getElementById('take').style.display="none";
	document.getElementById('saving').style.display="block";
	remove_whitespace(document.getElementById('take'));
	tox='';
	disabled_ids = '';
	form_fields='';

	l_id_array=[<?php echo $labels['id']?>];
	l_label_array=[<?php echo $labels['label']?>];
	l_type_array=[<?php echo $labels['type']?>];
	l_id_removed=[];

	for(x=0; x< l_id_array.length; x++)
	{
		l_id_removed[l_id_array[x]]=true;
	}
		

		if(document.getElementById('form_id_tempform_view1'))
		{
			wdform_page=document.getElementById('form_id_tempform_view1');
			remove_whitespace(wdform_page);
			n=wdform_page.childNodes.length-2;	
			for(q=0;q<=n;q++)
			{
				if(!wdform_page.childNodes[q].getAttribute("wdid"))
				{
					wdform_section=wdform_page.childNodes[q];
					for (x=0; x < wdform_section.childNodes.length; x++)
					{
						wdform_column=wdform_section.childNodes[x];
						if(wdform_column.firstChild)
						for (y=0; y < wdform_column.childNodes.length; y++)
						{
							is_in_old=false;
							wdform_row=wdform_column.childNodes[y];
							if(wdform_row.nodeType==3)
								continue;
							wdid=wdform_row.getAttribute("wdid");
							if(!wdid)
								continue;
							l_id=wdid;
							l_label = document.getElementById( wdid+'_element_labelform_id_temp').innerHTML;
							l_label = l_label.replace(/(\r\n|\n|\r)/gm," ");
							wdtype=wdform_row.firstChild.getAttribute('type');

							if(wdform_row.getAttribute("disabled"))
							{
								if(wdtype!="type_address")
									disabled_ids += wdid + ',';
								else
									disabled_ids += wdid + ',' + (parseInt(wdid)+1) + ','+(parseInt(wdid)+2)+ ',' +(parseInt(wdid)+3)+ ',' +(parseInt(wdid)+4)+ ','+(parseInt(wdid)+5) + ',';
							}
							
							for(z=0; z< l_id_array.length; z++)
							{
								if(l_id_array[z]==wdid)
								{
									if(l_type_array[z]=="type_address")
									{
										if(document.getElementById(l_id+"_mini_label_street1"))		
											l_id_removed[l_id_array[z]]=false;
							
											
										if(document.getElementById(l_id+"_mini_label_street2"))		
										l_id_removed[parseInt(l_id_array[z])+1]=false;	
									
										
										if(document.getElementById(l_id+"_mini_label_city"))
										l_id_removed[parseInt(l_id_array[z])+2]=false;	
																			
										if(document.getElementById(l_id+"_mini_label_state"))
										l_id_removed[parseInt(l_id_array[z])+3]=false;	
										
										
										if(document.getElementById(l_id+"_mini_label_postal"))
										l_id_removed[parseInt(l_id_array[z])+4]=false;	
										
										
										if(document.getElementById(l_id+"_mini_label_country"))
										l_id_removed[parseInt(l_id_array[z])+5]=false;	
										
										z=z+5;
									}
									else
										l_id_removed[l_id]=false;

								}
							}
							
							if(wdtype=="type_address")
							{
								addr_id=parseInt(wdid);
								id_for_country= addr_id;
								
								if(document.getElementById(id_for_country+"_mini_label_street1"))
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_street1").innerHTML+'#**label**#type_address#****#';addr_id++; 
								if(document.getElementById(id_for_country+"_mini_label_street2"))	
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_street2").innerHTML+'#**label**#type_address#****#';addr_id++; 	
								if(document.getElementById(id_for_country+"_mini_label_city"))	
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_city").innerHTML+'#**label**#type_address#****#';	addr_id++;
								if(document.getElementById(id_for_country+"_mini_label_state"))	
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_state").innerHTML+'#**label**#type_address#****#';	addr_id++;		
								if(document.getElementById(id_for_country+"_mini_label_postal"))
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_postal").innerHTML+'#**label**#type_address#****#';	addr_id++; 
								if(document.getElementById(id_for_country+"_mini_label_country"))
								tox=tox+addr_id+'#**id**#'+document.getElementById(id_for_country+"_mini_label_country").innerHTML+'#**label**#type_address#****#'; 
							}
							else
								tox=tox+wdid+'#**id**#'+l_label+'#**label**#'+wdtype+'#****#';
								
							
							id=wdid;
							form_fields+=wdid+"*:*id*:*";
							form_fields+=wdtype+"*:*type*:*";
							
							
							w_choices=new Array();	
							w_choices_checked=new Array();
							w_choices_disabled=new Array();
							w_allow_other_num=0;
							w_property=new Array();	
							w_property_type=new Array();	
							w_property_values=new Array();
							w_choices_price=new Array();
							
							if(document.getElementById(id+'_element_labelform_id_temp').innerHTML)
								w_field_label=document.getElementById(id+'_element_labelform_id_temp').innerHTML.replace(/(\r\n|\n|\r)/gm," ");
								
							if(document.getElementById(id+'_label_sectionform_id_temp'))
							if(document.getElementById(id+'_label_sectionform_id_temp').style.display=="block")
								w_field_label_pos="top";
							else
								w_field_label_pos="left";
														
							if(document.getElementById(id+"_elementform_id_temp"))
							{
								s=document.getElementById(id+"_elementform_id_temp").style.width;
								 w_size=s.substring(0,s.length-2);
							}
							
							if(document.getElementById(id+"_label_sectionform_id_temp"))
							{
								s=document.getElementById(id+"_label_sectionform_id_temp").style.width;
								w_field_label_size=s.substring(0,s.length-2);
							}
							
							if(document.getElementById(id+"_requiredform_id_temp"))
								w_required=document.getElementById(id+"_requiredform_id_temp").value;
								
							if(document.getElementById(id+"_uniqueform_id_temp"))
								w_unique=document.getElementById(id+"_uniqueform_id_temp").value;
								
							if(document.getElementById(id+'_label_sectionform_id_temp'))
							{
								w_class=document.getElementById(id+'_label_sectionform_id_temp').getAttribute("class");
								if(!w_class)
									w_class="";
							}
								
							gen_form_fields();
							wdform_row.innerHTML="%"+id+" - "+l_label+"%";
							
						}
					}
				}
			
				else
				{
						id=wdform_page.childNodes[q].getAttribute("wdid");
						if(wdform_page.childNodes[q].getAttribute("disabled"))
							disabled_ids += id + ',';
						w_editor=document.getElementById(id+"_element_sectionform_id_temp").innerHTML;
						
						form_fields+=id+"*:*id*:*";
						form_fields+="type_section_break"+"*:*type*:*";
												
						form_fields+="custom_"+id+"*:*w_field_label*:*";
						form_fields+=w_editor+"*:*w_editor*:*";
						form_fields+="*:*new_field*:*";
						wdform_page.childNodes[q].innerHTML="%"+id+" - "+"custom_"+id+"%";
						
					

				}

			}
		}	
	
	document.getElementById('disabled_fields').value=disabled_ids;
	document.getElementById('label_order_current').value=tox;
	
	for(x=0; x< l_id_array.length; x++)
	{
		if(l_id_removed[l_id_array[x]])
			tox=tox+l_id_array[x]+'#**id**#'+l_label_array[x]+'#**label**#'+l_type_array[x]+'#****#';
	}
	
	
	document.getElementById('label_order').value=tox;
	document.getElementById('form_fields').value=form_fields;
	
	
	refresh_()
	
		already_submitted=true;
		submitform( pressbutton );
}

function remove_whitespace(node)
{
var ttt;
	for (ttt=0; ttt < node.childNodes.length; ttt++)
	{
        if( node.childNodes[ttt] && node.childNodes[ttt].nodeType == '3' && !/\S/.test(  node.childNodes[ttt].nodeValue ))
		{
			
			node.removeChild(node.childNodes[ttt]);
			 ttt--;
		}
		else
		{
			if(node.childNodes[ttt].childNodes.length)
				remove_whitespace(node.childNodes[ttt]);
		}
	}
	return
}

function refresh_()
{
				
	document.getElementById('counter').value=gen;
	

		if(document.getElementById('form_id_tempform_view1'))
		{
			document.getElementById('form_id_tempform_view1').removeAttribute('style');
		}
		
	document.getElementById('form_front').value=document.getElementById('take').innerHTML;
}




	gen=<?php echo $row->counter; ?>;//add main form  id
    function enable()
	{

		if(document.getElementById('formMakerDiv').style.display=='block'){jQuery('#formMakerDiv').slideToggle(200);}else{jQuery('#formMakerDiv').slideToggle(400);}
		
		if(document.getElementById('formMakerDiv').offsetWidth)
			document.getElementById('formMakerDiv1').style.width	=(document.getElementById('formMakerDiv').offsetWidth - 60)+'px';
		if(document.getElementById('formMakerDiv1').style.display=='block'){jQuery('#formMakerDiv1').slideToggle(200);}else{jQuery('#formMakerDiv1').slideToggle(400);}
		
	}

    function enable2()
	{


		if(document.getElementById('formMakerDiv').style.display=='block'){jQuery('#formMakerDiv').slideToggle(200);}else{jQuery('#formMakerDiv').slideToggle(400);}
		
		if(document.getElementById('formMakerDiv').offsetWidth)
			document.getElementById('formMakerDiv1').style.width	=(document.getElementById('formMakerDiv').offsetWidth - 60)+'px';
	
    if(document.getElementById('formMakerDiv1').style.display=='block'){jQuery('#formMakerDiv1').slideToggle(200);}else{jQuery('#formMakerDiv1').slideToggle(400);}
	
		
	}
	
    </script>

<style>
#take_temp .element_toolbar, #take_temp .page_toolbar, #take_temp .captcha_img, #take_temp .wdform_stars
{
display:none;
}

#when_edit
{
position:absolute;
background-color:#666;
z-index:101;
display:none;
width:100%;
height:100%;
opacity: 0.7;
filter: alpha(opacity = 70);
}

#formMakerDiv
{
position:fixed;
background-color:#666;
z-index:100;
display:none;
left:0;
top:0;
width:100%;
height:100%;
opacity: 0.7;
filter: alpha(opacity = 70);
}
#formMakerDiv1
{
position:fixed;
z-index:100;
background-color:transparent;
top:0;
left:0;
display:none;
margin-left:30px;
margin-top:35px;
}

input[type="radio"], input[type="checkbox"] {
margin: 0px 4px 5px 5px;
}

.pull-left
{
float:none !important;
}

.modal-body
{
max-height:100%;
}

img
{
max-width:none;
}


.formmaker_table input
{
border-radius: 3px;
padding: 2px;
}

.formmaker_table
{
border-radius:8px;
border:6px #00aeef solid;
background-color:#00aeef;
height:120px;
}

.formMakerDiv1_table
{
border:6px #00aeef solid;
background-color:#FFF;
border-radius:8px;
}

label
{
display:inline;
}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div  class="formmaker_table" width="100%" >
<div style="float:left; text-align:center">
 	</br>
   <img src="components/com_contactformmaker/images/ContactFormMaker.png" />
	</br>
	</br>
	   <img src="components/com_contactformmaker/images/logo.png" />


</div>
</br>
<div style="float:right">

    <span style="font-size:16.76pt; font-family:tahoma; color:#FFFFFF; vertical-align:middle;">Form title:&nbsp;&nbsp;</span>

    <input id="title" name="title" style="width:151px; height:19px; border:none; font-size:11px; " value="<?php echo $row->title; ?>" />
 <br/>
 </br>
	<a href="#" onclick="Joomla.submitbutton('form_options_temp')" style="cursor:pointer;margin:10px; float:right; color:#fff; font-size:20px"><img src="components/com_contactformmaker/images/formoptions.png" /></a>    
   <br/>

</div>
	
  

</div>

<div id="formMakerDiv" onclick="close_window()"></div>  
<div id="formMakerDiv1"  align="center">
    
    
<table border="0" width="100%" cellpadding="0" cellspacing="0" height="100%" class="formMakerDiv1_table">
  <tr>
    <td style="padding:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr valign="top">
         
		<td width="40%" height="100%" align="left"><div id="edit_table" style="padding:0px; overflow-y:scroll; height:535px" ></div></td>

		 <td align="center" valign="top" style="background:url(components/com_contactformmaker/images/border2.png) repeat-y;">&nbsp;</td>
         <td style="padding:15px">
         <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
         
            <tr>
                <td align="right">
				
                  <img alt="ADD" title="add" style="cursor:pointer; vertical-align:middle; margin:5px" src="components/com_contactformmaker/images/save.png" onClick="add(0)"/>
                  <img alt="CANCEL" title="cancel"  style=" cursor:pointer; vertical-align:middle; margin:5px" src="components/com_contactformmaker/images/cancel_but.png" onClick="close_window()"/>
				
                	<hr style=" margin-bottom:10px" />
                  </td>
              </tr>
              
              <tr height="100%" valign="top">
                <td  id="show_table"></td>
              </tr>
              
            </table>
         </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<input type="hidden" id="old" />
<input type="hidden" id="old_selected" />
<input type="hidden" id="element_type" />
<input type="hidden" id="editing_id" />
<div id="main_editor" style="position:absolute; display:none; z-index:140;"><?php if($is_editor) echo $editor->display('editor','','440px','350px','40','6');
else
{
?>
<textarea name="editor" id="editor" cols="40" rows="6" style="width: 440px; height: 350px; " class="mce_editable" aria-hidden="true"></textarea>
<?php

}
 ?></div>
 </div>
 
 <?php if(!$is_editor)
?>
<iframe id="tinymce" style="display:none"></iframe>

<?php
?>


 
 
<br />
<br />

    <fieldset>

    <legend>

    <h2 style="color:#00aeef">Form</h2>

    </legend>

        <?php
		
    echo '<style>'.self::first_css.'</style>';

?>

<div id="saving" style="display:none">
	<div id="saving_text">Saving</div>
	<div id="fadingBarsG">
	<div id="fadingBarsG_1" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_2" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_3" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_4" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_5" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_6" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_7" class="fadingBarsG">
	</div>
	<div id="fadingBarsG_8" class="fadingBarsG">
	</div>
	</div>
</div>


    <div id="take"><?php
	
	    echo $row->form_front;
		
	 ?></div>

    </fieldset>

    <input type="hidden" name="form_front" id="form_front">
	<input type="hidden" name="form_fields" id="form_fields"/>
	  
    <input type="hidden" name="pagination" id="pagination" />
    <input type="hidden" name="show_title" id="show_title" />
    <input type="hidden" name="show_numbers" id="show_numbers" />
	
    <input type="hidden" name="public_key" id="public_key" />
    <input type="hidden" name="private_key" id="private_key" />
    <input type="hidden" name="recaptcha_theme" id="recaptcha_theme" />

	<input type="hidden" id="label_order_current" name="label_order_current" value="<?php echo $row->label_order_current;?>" />
    <input type="hidden" id="label_order" name="label_order" value="<?php echo $row->label_order;?>" />
    <input type="hidden" name="counter" id="counter" value="<?php echo $row->counter;?>">
	<input type="hidden" name="disabled_fields" id="disabled_fields" value="<?php echo $row->disabled_fields;?>">
<script type="text/javascript">

function formOnload()
{
for(t=0; t<<?php echo $row->counter;?>; t++)
	if(document.getElementById( "wdform_field"+t))
		if(document.getElementById( "wdform_field"+t).parentNode.getAttribute("disabled"))
		{
			if(document.getElementById( "wdform_field"+t).getAttribute("type")!='type_section_break')
				document.getElementById( "wdform_field"+t).style.cssText = 'display:table-cell;';

				document.getElementById( "disable_field"+t).checked = false;
				document.getElementById( "disable_field"+t).setAttribute("title", "Enable the field"); 
				document.getElementById( "wdform_field"+t).parentNode.style.cssText = 'opacity:0.4;';
			
		}
		else
		{
			document.getElementById( "disable_field"+t).checked = true;
		}


for(t=0; t<<?php echo $row->counter;?>; t++)
	if(document.getElementById(t+"_typeform_id_temp"))
	{
		if(document.getElementById(t+"_typeform_id_temp").value=="type_map")
		{
			if_gmap_init(t);
			for(q=0; q<20; q++)
				if(document.getElementById(t+"_elementform_id_temp").getAttribute("long"+q))
				{
				
					w_long=parseFloat(document.getElementById(t+"_elementform_id_temp").getAttribute("long"+q));
					w_lat=parseFloat(document.getElementById(t+"_elementform_id_temp").getAttribute("lat"+q));
					w_info=parseFloat(document.getElementById(t+"_elementform_id_temp").getAttribute("info"+q));
					add_marker_on_map(t,q, w_long, w_lat, w_info, false);
				}
		}
		
		else
		if(document.getElementById(t+"_typeform_id_temp").value=="type_name")
		{
				var myu = t;
				jQuery(document).ready(function() {	

				jQuery("#"+myu+"_mini_label_first").click(function() {		
			
				if (jQuery(this).children('input').length == 0) {	

					var first = "<input type='text' id='first' class='first' style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";	
						jQuery(this).html(first);							
						jQuery("input.first").focus();			
						jQuery("input.first").blur(function() {	
					
					var id_for_blur = document.getElementById('first').parentNode.id.split('_');
					var value = jQuery(this).val();			
				jQuery("#"+id_for_blur[0]+"_mini_label_first").text(value);		
				});	
			}	
			});	    
				
				jQuery("label#"+myu+"_mini_label_last").click(function() {	
			if (jQuery(this).children('input').length == 0) {	
			
				var last = "<input type='text' id='last' class='last'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";	
					jQuery(this).html(last);			
					jQuery("input.last").focus();					
					jQuery("input.last").blur(function() {	
					var id_for_blur = document.getElementById('last').parentNode.id.split('_');			
					var value = jQuery(this).val();			
					
					jQuery("#"+id_for_blur[0]+"_mini_label_last").text(value);	
				});	
				 
			}	
			});
			
				jQuery("label#"+myu+"_mini_label_title").click(function() {	
			if (jQuery(this).children('input').length == 0) {		
				var title_ = "<input type='text' id='title_' class='title_'  style='outline:none; border:none; background:none; width:50px;' value=\""+jQuery(this).text()+"\">";	
					jQuery(this).html(title_);			
					jQuery("input.title_").focus();					
					jQuery("input.title_").blur(function() {	
					var id_for_blur = document.getElementById('title_').parentNode.id.split('_');			
					var value = jQuery(this).val();			
					
					jQuery("#"+id_for_blur[0]+"_mini_label_title").text(value);	
				});	
			}	
			});


			jQuery("label#"+myu+"_mini_label_middle").click(function() {	
			if (jQuery(this).children('input').length == 0) {		
				var middle = "<input type='text' id='middle' class='middle'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";	
					jQuery(this).html(middle);			
					jQuery("input.middle").focus();					
					jQuery("input.middle").blur(function() {	
					var id_for_blur = document.getElementById('middle').parentNode.id.split('_');			
					var value = jQuery(this).val();			
					
					jQuery("#"+id_for_blur[0]+"_mini_label_middle").text(value);	
				});	
			}	
			});
			
			});		
		}						
		else
		if(document.getElementById(t+"_typeform_id_temp").value=="type_phone")
		{
				var myu = t;
			  
			jQuery(document).ready(function() {	
			jQuery("label#"+myu+"_mini_label_area_code").click(function() {		
			if (jQuery(this).children('input').length == 0) {		

				var area_code = "<input type='text' id='area_code' class='area_code' style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";		

				jQuery(this).html(area_code);		
				jQuery("input.area_code").focus();		
				jQuery("input.area_code").blur(function() {	
				var id_for_blur = document.getElementById('area_code').parentNode.id.split('_');
				var value = jQuery(this).val();			
				jQuery("#"+id_for_blur[0]+"_mini_label_area_code").text(value);		
				});		
			}	
			});	

			
			jQuery("label#"+myu+"_mini_label_phone_number").click(function() {		

			if (jQuery(this).children('input').length == 0) {			
				var phone_number = "<input type='text' id='phone_number' class='phone_number'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";						

				jQuery(this).html(phone_number);					

				jQuery("input.phone_number").focus();			
				jQuery("input.phone_number").blur(function() {		
				var id_for_blur = document.getElementById('phone_number').parentNode.id.split('_');
				var value = jQuery(this).val();			
				jQuery("#"+id_for_blur[0]+"_mini_label_phone_number").text(value);		
				});	
			}	
			});
			
			});	
		}						
		else
		if(document.getElementById(t+"_typeform_id_temp").value=="type_address")
		{
			var myu = t;
       
			jQuery(document).ready(function() {		
			jQuery("label#"+myu+"_mini_label_street1").click(function() {			

				if (jQuery(this).children('input').length == 0) {				
				var street1 = "<input type='text' id='street1' class='street1' style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";
				jQuery(this).html(street1);					
				jQuery("input.street1").focus();		
				jQuery("input.street1").blur(function() {	
				var id_for_blur = document.getElementById('street1').parentNode.id.split('_');
				var value = jQuery(this).val();			
				jQuery("#"+id_for_blur[0]+"_mini_label_street1").text(value);		
				});		
				}	
				});		
			
			jQuery("label#"+myu+"_mini_label_street2").click(function() {		
			if (jQuery(this).children('input').length == 0) {		
			var street2 = "<input type='text' id='street2' class='street2'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";
			jQuery(this).html(street2);					
			jQuery("input.street2").focus();		
			jQuery("input.street2").blur(function() {	
			var id_for_blur = document.getElementById('street2').parentNode.id.split('_');
			var value = jQuery(this).val();			
			jQuery("#"+id_for_blur[0]+"_mini_label_street2").text(value);		
			});		
			}	
			});	
			
			
			jQuery("label#"+myu+"_mini_label_city").click(function() {	
				if (jQuery(this).children('input').length == 0) {	
				var city = "<input type='text' id='city' class='city'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";
				jQuery(this).html(city);			
				jQuery("input.city").focus();				
				jQuery("input.city").blur(function() {	
				var id_for_blur = document.getElementById('city').parentNode.id.split('_');		
				var value = jQuery(this).val();		
				jQuery("#"+id_for_blur[0]+"_mini_label_city").text(value);		
			});		
			}	
			});	
			
			jQuery("label#"+myu+"_mini_label_state").click(function() {		
				if (jQuery(this).children('input').length == 0) {	
				var state = "<input type='text' id='state' class='state'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";	
					jQuery(this).html(state);		
					jQuery("input.state").focus();		
					jQuery("input.state").blur(function() {	
				var id_for_blur = document.getElementById('state').parentNode.id.split('_');					
				var value = jQuery(this).val();			
			jQuery("#"+id_for_blur[0]+"_mini_label_state").text(value);	
			});	
			}
			});		

			jQuery("label#"+myu+"_mini_label_postal").click(function() {		
			if (jQuery(this).children('input').length == 0) {			
			var postal = "<input type='text' id='postal' class='postal'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";
			jQuery(this).html(postal);			
			jQuery("input.postal").focus();			
			jQuery("input.postal").blur(function() {
			var id_for_blur = document.getElementById('postal').parentNode.id.split('_');	
			var value = jQuery(this).val();		
			jQuery("#"+id_for_blur[0]+"_mini_label_postal").text(value);		
			});	
			}
			});	
			
			
			jQuery("label#"+myu+"_mini_label_country").click(function() {		
				if (jQuery(this).children('input').length == 0) {		
					var country = "<input type='country' id='country' class='country'  style='outline:none; border:none; background:none; width:130px;' value=\""+jQuery(this).text()+"\">";
					jQuery(this).html(country);		
					jQuery("input.country").focus();	
					jQuery("input.country").blur(function() {	
					var id_for_blur = document.getElementById('country').parentNode.id.split('_');				
					var value = jQuery(this).val();			
					jQuery("#"+id_for_blur[0]+"_mini_label_country").text(value);			
					});	
				}	
			});
			});	

		}						
		
	}
	
remove_whitespace(document.getElementById('take'));
	
	form_view=1;
	form_view_count=0;
	
	document.getElementById('araqel').value=1;

}

function formAddToOnload()
{ 
	if(formOldFunctionOnLoad){ formOldFunctionOnLoad(); }
	formOnload();
}

function formLoadBody()
{
	formOldFunctionOnLoad = window.onload;
	window.onload = formAddToOnload;
}

var formOldFunctionOnLoad = null;

formLoadBody();


</script>


	<script src="<?php echo  $cmpnt_js_path ?>/contactformmaker.js" type="text/javascript" style=""></script>
	<script src="<?php echo  JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/contactform.js'; ?>" type="text/javascript"></script>
	<script src="<?php echo  JURI::root(true).'/components/com_contactformmaker/views/contactformmaker/tmpl/jquery-ui.js'; ?>" type="text/javascript"></script>

    <input type="hidden" name="option" value="com_contactformmaker" />

    <input type="hidden" name="id" value="<?php echo $row->id?>" />

    <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />

    <input type="hidden" name="task" value="" />
    <input type="hidden" id="araqel" value="0" />

</form>

<?php		

       }	


public static function show_themes(&$rows, &$pageNav, &$lists){

	JSubMenuHelper::addEntry(JText::_('Forms'), 'index.php?option=com_contactformmaker&amp;task=forms' );
	JSubMenuHelper::addEntry(JText::_('Submissions'), 'index.php?option=com_contactformmaker&amp;task=submits' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_contactformmaker&amp;task=themes', true );
	JSubMenuHelper::addEntry(JText::_('Blocked IPs'), 'index.php?option=com_contactformmaker&amp;task=blocked_ips' );
	JSubMenuHelper::addEntry(JText::_('Global Options'), 'index.php?option=com_contactformmaker&amp;task=global_options' );

	JHTML::_('behavior.tooltip');	
	JHtml::_('formbehavior.chosen', 'select');

	?>
<script type="text/javascript">
Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order_themes.value     = order;
    form.filter_order_Dir_themes.value = dir;
    submitform( task );
}


function SelectAll(obj) { obj.focus(); obj.select(); } 
</script>
	
   
	<form action="index.php?option=com_contactformmaker" method="post" name="adminForm" id="adminForm">
    
		<table width="100%">
		<tr>
			<td align="left" width="100%">
				<input type="text" name="search_theme" id="search_theme" value="<?php echo $lists['search_theme'];?>" class="text_area"  placeholder="Search theme" style="margin:0px" />
				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('search_theme').value='';this.form.submit();" data-original-title="Clear">
				<i class="icon-remove"></i></button>
				
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $pageNav->getLimitBox(); ?>
				</div>

			</td>
		</tr>
		</table>    
    
        
    <table class="table table-striped"  width="100%">
    <thead>
    	<tr>            
            <th width="30" class="title"><?php echo "#" ?></td>
			<th width="20"><input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)"></th>
            <th width="30" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></td>
            <th><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
            <th><?php echo JText::_('Default'); ?></th>
        </tr>
    </thead>
	<tfoot>
		<tr>
			<td colspan="11">
			 <?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
                
    <?php
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
		$row = &$rows[$i];
		$checked 	= JHTML::_('grid.id', $i, $row->id);
		$link 		= JRoute::_( 'index.php?option=com_contactformmaker&task=edit_themes&cid[]='. $row->id );
?>
        <tr class="<?php echo "row$k"; ?>">
        	<td align="center"><?php echo $i+1?></td>
        	<td><?php echo $checked?></td>
        	<td align="center"><?php echo $row->id?></td>
        	<td><a href="<?php echo $link;?>"><?php echo $row->title?></a></td>            
			<td align="center">
				<?php if ( $row->default == 1 ) : ?>
				<i class="icon-star"></i>
				<?php else : ?>
				&nbsp;
				<?php endif; ?>
			</td>
       </tr>
        <?php
		$k = 1 - $k;
	}
	?>
    </table>
	
    <input type="hidden" name="option" value="com_contactformmaker">
    <input type="hidden" name="task" value="themes">    
    <input type="hidden" name="boxchecked" value="0"> 
    <input type="hidden" name="filter_order_themes" value="<?php echo $lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir_themes" value="<?php echo $lists['order_Dir']; ?>" />       
    </form>

<?php
}

public static function add_themes($def_theme){

		JRequest::setVar( 'hidemainmenu', 1 );
		
		?>
        
<script>

Joomla.submitbutton= function (pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel_themes') 
	{
		submitform( pressbutton );
		return;
	}
	if(form.title.value=="")
	{
		alert('Set Theme title');
		return;
	}

	submitform( pressbutton );
}


</script>        
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<table class="admintable" >

 
				<tr>
					<td class="key">
						<label for="title">
							Title of theme:
						</label>
					</td>
					<td >
                                    <input type="text" name="title" id="title" size="80"/>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<label for="title">
							Css:
						</label>
					</td>
					<td >
                                    <textarea name="css" id="css" rows=30 style="width:500px"><?php echo $def_theme->css ?></textarea>
					</td>
				</tr>
</table>           
    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="task" value="" />
</form>

	   <?php	
	
}

public static function edit_blocked_ips(&$row){
JRequest::setVar( 'hidemainmenu', 1 );

		
		?>
        
<script>
function check_isnum_point(e)
{
   	var chCode1 = e.which || e.keyCode;
	
	if (chCode1 ==46)
		return true;
	
	if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57))
        return false;
	return true;
}

function submitbutton(pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel_blocked_ips') 
	{
		submitform( pressbutton );
		return;
	}


	submitform( pressbutton );
}


</script>        
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<table class="admintable">

 
				<tr>
					<td class="key">
						<label for="title">
							IP:
						</label>
					</td>
					<td >
                                    <input type="text" name="ip" id="ip" value="<?php echo htmlspecialchars($row->ip) ?>" onkeypress="return check_isnum_point(event);" size="60"/>
					</td>
				</tr>
				
</table>           
    <input type="hidden" name="option" value="com_contactformmaker" />
	<input type="hidden" name="id" value="<?php echo $row->id?>" />        
	<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
	<input type="hidden" name="task" value="" />        
</form>

	   <?php	


}

public static function edit_themes(&$row){

		JRequest::setVar( 'hidemainmenu', 1 );
		
		?>
        
<script>

Joomla.submitbutton= function (pressbutton) {
	
	var form = document.adminForm;
	
	if (pressbutton == 'cancel_themes') 
	{
		submitform( pressbutton );
		return;
	}
	if(form.title.value=="")
	{
		alert('Set Theme title');
		return;
	}

	submitform( pressbutton );
}


</script>        
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<table class="admintable" >

 
				<tr>
					<td class="key">
						<label for="title">
							Title of theme:
						</label>
					</td>
					<td >
                        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($row->title) ?>" size="80"/>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<label for="title">
							Css:
						</label>
					</td>
					<td >
                        <textarea name="css" id="css" rows=30 style="width:500px"><?php echo htmlspecialchars($row->css) ?></textarea>
					</td>
				</tr>
</table>           
    <input type="hidden" name="option" value="com_contactformmaker" />
	<input type="hidden" name="id" value="<?php echo $row->id?>" />        
	<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
	<input type="hidden" name="task" value="" />        
</form>

	   <?php	
	
}

/////////////////////////////////////////////////////////////////////// THEME /////////////////////////////////


public static function forchrome($id){
?>
<script type="text/javascript">


window.onload=val; 

function val()
{
var form = document.adminForm;
	submitform();
}

</script>
<form action="index.php" method="post" name="adminForm"  id="adminForm">

    <input type="hidden" name="option" value="com_contactformmaker" />

    <input type="hidden" name="id" value="<?php echo $id;?>" />

    <input type="hidden" name="cid[]" value="<?php echo $id; ?>" />

    <input type="hidden" name="task" value="gotoedit" />
</form>
<?php
}

public static function editCss(&$theme, &$form){
JRequest::setVar( 'hidemainmenu', 1 );
	

$new = JRequest::getVar('new',0);
	
		?>
  

<script>
if(<?php echo $new ?> == 1)
{
window.parent.location.reload();

}

</script>  

<style>
label {display:inline-block;}
</style>

<?php
if($new == 1 )
return;  ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <table class="adminform">
        
		<tr>
            <td >
			<label for="message"> <?php echo JText::_( 'Theme Title' ); ?> </label>  
			<input type="text" name="title" id="title" value="<?php echo $theme->title; ?>" size="40"/>
			</td >
		</tr>
        <tr>
            <td >
                <textarea style="margin: 0px; width:100%" cols="100" rows="18" name="css" id="css" ><?php echo $theme->css;?></textarea>
            </td>
        </tr>
		
		<tr>
            <td>
			<input type="submit" value="Save " onclick="document.getElementById('task').value = 'save_for_edit';  this.form.submit(); ">
			 <input type="submit" value="Apply" onclick="document.getElementById('task').value = 'apply_for_edit';  document.getElementById('id').value = ''; this.form.submit(); ">
             <input type="submit" value="Save as new" onclick="document.getElementById('task').value = 'save_new_theme';  document.getElementById('id').value = ''; this.form.submit(); ">
                <button onclick="document.getElementById('css').value=document.getElementById('main_theme').innerHTML; return false;" style="margin-left:15px;">Reset</button>
            </td>
        </tr>
    </table>
	<div style="display:none;" id="main_theme"><?php echo str_replace('"','\"',$theme->css); ?></div>
    <input type="hidden" name="option" value="com_contactformmaker" />
    <input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" id="id" value="<?php echo $theme->id; ?>" />
	<input type="hidden" name="form_id" id="form_id" value="<?php echo $form->id; ?>" />
	
</form>


<?php		

       }



public static function select_article(&$rows, &$pageNav, &$lists)
{



		JHTML::_('behavior.tooltip');	

	?>

<form action="index.php?option=com_contactformmaker" method="post" name="adminForm" id="adminForm">

    <table width="100%">

        <tr>

            <td align="left" width="100%"> <?php echo JText::_( 'Filter' ); ?>:

                <input type="text" name="search" id="search" value="<?php echo $lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />

                <button onclick="this.form.submit();"> <?php echo JText::_( 'Go' ); ?></button>

                <button onclick="document.getElementById('search').value='';this.form.submit();"> <?php echo JText::_( 'Reset' ); ?></button>

            </td>

        </tr>

    </table>

    <table class="adminlist" width="100%">

        <thead>

            <tr>

                <th width="4%"><?php echo '#'; ?></th>

                <th width="8%">

                    <input type="checkbox" name="toggle"

 value="" onclick="checkAll(<?php echo count($rows)?>)">

                </th>

                <th width="50%"><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>

                <th width="38%"><?php echo JHTML::_('grid.sort', 'Email to Send Submissions to', 'mail', @$lists['order_Dir'], @$lists['order'] ); ?></th>

            </tr>

        </thead>

        <tfoot>

            <tr>

                <td colspan="50"> <?php echo $pageNav->getListFooter(); ?> </td>

            </tr>

        </tfoot>

        <?php

	

    $k = 0;

	for($i=0, $n=count($rows); $i < $n ; $i++)

	{

		$row = &$rows[$i];

		$checked 	= JHTML::_('grid.id', $i, $row->id);

		$published 	= JHTML::_('grid.published', $row, $i); 

		// prepare link for id column

		$link 		= JRoute::_( 'index.php?option=com_contactformmaker&task=edit&cid[]='. $row->id );

		?>

        <tr class="<?php echo "row$k"; ?>">

              <td align="center"><?php echo $row->id?></td>

          <td align="center"><?php echo $checked?></td>

            <td align="center"><a href="<?php echo $link; ?>"><?php echo $row->title?></a></td>

            <td align="center"><?php echo $row->mail?></td>

        </tr>

        <?php

		$k = 1 - $k;

	}

	?>

    </table>

    <input type="hidden" name="option" value="com_contactformmaker">
    <input type="hidden" name="task" value="forms">
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="" />

</form>

<?php

}







//////////////////////////////glxavor 
}
?>