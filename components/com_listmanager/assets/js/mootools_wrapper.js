/*
 * <1.2 VERSION 1
 * >=1.2 VERSION 2
 */
var MOOTOOLS_11=1;
var MOOTOOLS_12=2;
var MOOTOOLS_13=3;
function getVersion(){
	vers=parseInt(replaceAll(MooTools.version,'.','').substring(0,2));
	if(vers<12) return 1;
	else if(vers<13) return 2;
	else return 3;
}

function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}

if (getVersion()==MOOTOOLS_11){
	// Funciones compartidas
	Element.extend({
		setHTMLContent:function(content){
			this.setHTML(content);
		}
		,getTagContent:function(){
			return this.getTag();
		}
		,getHTMLContent:function(){
			return this.innerHTML;
		}
		,hideContent:function(){		
			this.setStyle('display', 'none');
		}	
	});
	
	// Funciones de Mootools >=1.2
	Element.extend({
		dispose: function(){
			this.remove();
		}
		,destroy: function(){
			this.remove();
		}
		,getSelected: function(){
			ret=null;
			this.getElements('option').each(function(elem){
				if (elem.getProperty('selected')){
	            	ret=elem;
	            }
			});
			return ret;
		}		
		,erase: function(item){
			this.setProperty('value','');
		}
		,dissolve:function(){
			new Fx.Style($(this),'opacity', {duration: 10}).start('1','0');		    
		}
		,reveal:function(){
			new Fx.Style($(this),'opacity', {duration: 10}).start('0','1');		    
		}
		,fade:function(opt){
			if (opt=='hide'){
				new Fx.Style($(this),'opacity', {duration: 10}).start('1','0');
			} else if (opt=='in') {
				new Fx.Style($(this),'opacity', {duration: 10}).start('0','1');
			} 
		}
		,hide:function(){		
			this.setStyle('display', 'none');
		}
	});	
} else if (getVersion()==MOOTOOLS_12){
	// Funciones compartidas
	Element.implement({
		setHTMLContent:function(content){
			this.setHTML(content);
		}
		,getTagContent:function(){
			return this.getTag();
		}
		,getHTMLContent:function(){
			return this.innerHTML;
		}
		,hideContent:function(){		
			this.setStyle('display', 'none');
		}	
	});
	
	// Funciones de Mootools >=1.2
	Element.implement({
		dispose: function(){
			this.remove();
		}
		,destroy: function(){
			this.remove();
		}
		,getSelected: function(){
			ret=null;
			this.getElements('option').each(function(elem){
				if (elem.getProperty('selected')){
	            	ret=elem;
	            }
			});
			return ret;
		}		
		,erase: function(item){
			this.setProperty('value','');
		}
		,dissolve:function(){
			new Fx.Style($(this),'opacity', {duration: 10}).start('1','0');		    
		}
		,reveal:function(){
			new Fx.Style($(this),'opacity', {duration: 10}).start('0','1');		    
		}
		,fade:function(opt){
			if (opt=='hide'){
				new Fx.Style($(this),'opacity', {duration: 10}).start('1','0');
			} else if (opt=='in') {
				new Fx.Style($(this),'opacity', {duration: 10}).start('0','1');
			} 
		}
		,hide:function(){		
			this.setStyle('display', 'none');
		}
	});	
} else if (getVersion()==MOOTOOLS_13){

	Element.implement({
		setHTMLContent:function(content){
			this.set('html',content);
		}
		,getTagContent:function(){
			return this.get('tag');
		}
		,getHTMLContent:function(){
			return this.get('html');
		}
		,hideContent:function(){		
			this.hide();
		}	
	});
}



