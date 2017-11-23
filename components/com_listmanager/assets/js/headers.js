var LmHeader = function(){	
	this.order=false,
	this.initialize=function(){};
	this.setJsonData=function(data){
    	this.id = data['id'];
    	this.idlisting = data['idlisting'];
    	this.idfield = data['idfield'];
    	this.value = data['value'];    	
    	this.idrecord = data['idrecord'];
    	this.type = data['type'];
    	this.decimal = data['decimal'];
    	this.order = data['order'];
    	this.size = data['size'];
    	this.name = data['name'];
    	this.innername = data['innername'];
    	this.visible = data['visible'];
    	this.total = data['total'];
    	this.limit0 = data['limit0'];
    	this.limit1 = data['limit1'];
    	this.multivalue = data['multivalue'];
    	this.mandatory = data['mandatory'];
    	this.autofilter = data['autofilter'];
    	this.showorder = data['showorder'];
    	this.defvalue = data['defaulttext'];
    	this.styleclass = data['css'];
    	this.readmore = data['readmore'];
    	this.readmore_word_count = data['readmore_word_count'];
    	this.link_id = data['link_id'];
    	this.link_url = data['link_url'];
    	this.link_type = data['link_type'];
    	this.link_width = data['link_width'];
    	this.link_height = data['link_height'];
    	this.link_detail = data['link_detail'];
    	this.bulk = data['bulk'];
    	this.cardview = data['cardview'];
    	this._struser=data['_struser'];
    	this.filterplaceholder=data['filterplaceholder'];
    	this.filterplaceholderfrom=data['filterplaceholderfrom'];
    	this.filterplaceholderto=data['filterplaceholderto'];
    };
    this.isVisible=function(){
    	if (this.visible=='1') return true;
    	else return false;
    };
    this.isAutofilter=function(){
    	if (this.autofilter>-1) return true;
    	else return false;
    };
    this.getIntervalo=function(){
    	return this.limit1-this.limit0;
    };
    this.isShowOrder=function(){
    	if (this.showorder=="1") return true;
    	else return false;
    };
    this.isCardViewLarge=function(){
    	if (this.cardview=='1') return true;
    	else return false;
    };
    this.getDefaultValue=function(){
    	return this.defvalue;
    };    
}

/*var LmHeader = new Class({	
	order:false,
	initialize: function(){},
	setJsonData: function(data){
    	this.id = data['id'];
    	this.idlisting = data['idlisting'];
    	this.idfield = data['idfield'];
    	this.value = data['value'];    	
    	this.idrecord = data['idrecord'];
    	this.type = data['type'];
    	this.decimal = data['decimal'];
    	this.order = data['order'];
    	this.size = data['size'];
    	this.name = data['name'];
    	this.innername = data['innername'];
    	this.visible = data['visible'];
    	this.total = data['total'];
    	this.limit0 = data['limit0'];
    	this.limit1 = data['limit1'];
    	this.multivalue = data['multivalue'];
    	this.mandatory = data['mandatory'];
    	this.autofilter = data['autofilter'];
    	this.showorder = data['showorder'];
    	this.defvalue = data['defaulttext'];
    	this.styleclass = data['css'];
    	this.readmore = data['readmore'];
    	this.readmore_word_count = data['readmore_word_count'];
    	this.link_id = data['link_id'];
    	this.link_url = data['link_url'];
    	this.link_type = data['link_type'];
    	this.link_width = data['link_width'];
    	this.link_height = data['link_height'];
    	this._struser=data['_struser'];    	    	
    },
    isVisible: function(){
    	if (this.visible=='1') return true;
    	else return false;
    },
    isAutofilter: function(){
    	if (this.autofilter>-1) return true;
    	else return false;
    },
    getIntervalo: function(){
    	return this.limit1-this.limit0;
    },
    isShowOrder:function(){
    	if (this.showorder=="1") return true;
    	else return false;
    },
    getDefaultValue:function(){
    	return this.defvalue;
    }
    
});*/