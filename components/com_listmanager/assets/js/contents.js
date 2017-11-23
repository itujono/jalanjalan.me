var LmRow = function(){
	this.initialize=function(options){};
	this.idrecord=0;
	this.values=[];
	this.setData=function(data){
		this.values.push(data);
	};
	this.setRecordId=function(idrecord){
		this.idrecord=idrecord;
	};
	this.getData=function(id){
    	var ret=null;
    	LMJQ(this.values).each(function(index,elem){
    		if (elem.getId()==id) ret=elem;
    	});
    	return ret;    
	};
	this.checkFilter=function(filters,headers){		
		if (filters==undefined || filters.getFilter().length<=0) return true;
		var ret=true;		
		for(var i=0;i<filters.getFilter().length;i++){
			var filter=filters.getFilter()[i];			
			if (filter.isGlobal()){
				ret_global=false;
				for(var v=0;v<this.values.length;v++){
					var vals=this.values[v];
					var tempVal=vals.getValue().toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
					if (tempVal.indexOf(filter.getValue())>-1){
						ret_global=true;
						break;
					}
				}
				ret=ret&&ret_global;
			} else {		
				if (LMJQ.type(this.getData(filter.getHeaderId()))==='null') {
					ret=false;
					continue;
				}
				var value=this.getData(filter.getHeaderId()).getValue().toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
				var filter_value=filter.getRawValue();
				if (LMJQ.type(filter_value)==='array'){
					ret_array=false;					
					for(var j=0;j<filter_value.length;j++){
						var tmp_arr_val=filter_value[j].toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
						if (value.indexOf(tmp_arr_val)>-1) {
							ret_array=true;
							break;
						}
					}
					ret=ret&&ret_array;
				} else {
					var tempVal=value.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
					var type_header_id=filter.getHeaderId();
					var header=getHeaderById(headers, type_header_id);
					var tmp_filter_value=filter_value.toLowerCase().replace(/<\/?[^>]+(>|$)/g, "");
					// Filtro por tipos. Los numericos son distintos.
					switch(parseInt(header.type)){
						case 0:
						case 4:	
						case 7:
						case 9:
						case 10:
							if (tempVal.indexOf(tmp_filter_value)<=-1) ret=false;
							break;
						default:
							if (tmp_filter_value.length>0 && tempVal!=tmp_filter_value) ret=false;
							break;
					}					
				}
			}
		}
		return ret;
	}
}; 

var LmData = function(options){
	//this.Implements: [Options, Events],
	this.options={
		id:0,
		value:'',
		multivalue:''
	};
	this.options = jQuery.extend(this.options, options || {});
	/*this.initialize: function(options){
		this.setOptions(options);
	},*/
	this.getValue=function(){
		return this.options.value;
	};
	this.getMultiValue=function(){
		return this.options.multivalue;
	};
	this.getId=function(){
		return this.options.id;
	};
	this.isEmpty=function(){
		return this.options.value==undefined || this.options.value=='';
	};
};

function getHeaderById(headers, id){
	for(var i=0;i<headers.length;i++){
		if (headers[i].id==id) return headers[i]; 
	}
	return null;
}

