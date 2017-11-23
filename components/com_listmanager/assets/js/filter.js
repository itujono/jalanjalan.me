var LmFilterManager = function(){
	//Implements: [Options, Events],
	this.filters=[];	
	this.filtersloaded=[];
	/*options:{},
    initialize: function(options){
    	this.setOptions(options);
    },*/
	this.setFilter=function(headerId, value){
    	this.delFilter(headerId);    	
    	if (value!==null && value!=''){
    		var lmfilter = new LmFilter({
	    		headerId:headerId,
	    		value:value
	    	});
	    	this.filters.push(lmfilter);
    	}    	
    };
    this.setFilterLoaded=function(headerId, value){
    	this.delFilterLoaded(headerId);    
    	if (value!=null && value.length>0){
    		var lmfilter = new LmFilter({
	    		headerId:headerId,
	    		value:value
	    	});
	    	this.filtersloaded.push(lmfilter);
    	}
    };
    this.getFilter=function(){
    	return this.filters;
    };
    this.delFilter=function(headerId){
    	for(var i=0;i<this.filters.length;i++){    		
    		if (this.filters[i].getHeaderId()==headerId)
    			this.filters.splice(i,1);
    	}    		
    };
    this.delFilterLoaded=function(headerId){
    	for(var i=0;i<this.filtersloaded.length;i++){    		
    		if (this.filtersloaded[i].getHeaderId()==headerId)
    			this.filtersloaded.splice(i,1);
    	}    		
    };
    this.getFilterByHeaderid=function(headerId){
    	for(var i=0;i<this.filters.length;i++){    		
    		if (this.filters[i].getHeaderId()==headerId)
    			return this.filters[i].getValue();
    	} 
    	for(var i=0;i<this.filtersloaded.length;i++){    		
    		if (this.filtersloaded[i].getHeaderId()==headerId)
    			return this.filtersloaded[i].getValue();
    	} 
    	return null;
    };  
    this.getFilterByHeaderidRaw=function(headerId){
    	for(var i=0;i<this.filters.length;i++){    		
    		if (this.filters[i].getHeaderId()==headerId)
    			return this.filters[i].getRawValue();
    	} 
    	for(var i=0;i<this.filtersloaded.length;i++){    		
    		if (this.filtersloaded[i].getHeaderId()==headerId)
    			return this.filtersloaded[i].getRawValue();
    	} 
    	return null;
    };
    this.clearAllFilters=function(){
    	this.filters.length=0;
    };
    this.getJSONFilter=function(){
    	var opts=new Array();
    	for(var i=0;i<this.filters.length;i++){ 
    		var arrInt=new Array();
    		arrInt[0]=this.filters[i].options.headerId;
    		arrInt[1]=this.filters[i].options.value;
    		opts[i]=arrInt;
    	}
    	for(var i=0;i<this.filtersloaded.length;i++){ 
    		var arrInt=new Array();
    		arrInt[0]=this.filtersloaded[i].options.headerId;
    		arrInt[1]=this.filtersloaded[i].options.value;
    		opts[i]=arrInt;
    	}
    	return JSON.stringify(opts);
    };
    
};
var LmFilter = function(options){
	//Implements: [Options, Events],
	this.filters=[];	
	this.options={
		headerId:9999,
		value:''
	};
	this.options = jQuery.extend(this.options, options || {});
    /*initialize: function(options){
    	this.setOptions(options);
    },*/
	this.getHeaderId=function(){ return this.options.headerId;};
	//this.getValue=function(){ return (this.options.value+"").toLowerCase();};
	this.getValue=function(){ return (this.options.value+"");};
	this.getRawValue=function(){return this.options.value};
	this.isGlobal=function(){ return this.options.headerId==9999;};
};
