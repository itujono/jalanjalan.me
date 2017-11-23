var LmSorterManager = function(){
	//Implements: [Options, Events],	
	this.headerOrders=null;
	/*options:{},
    initialize: function(options){
    	this.setOptions(options);
    },*/
	this.setOrder=function(headerId,order){
    	this.deleteOrderInHeaders(headerId);
    	var sorter=new LmSorterElem();
    	sorter.setSort(headerId,order);
    	if(this.headerOrders==null) this.headerOrders=new Array();
    	this.headerOrders.splice(0,0,sorter);
    };
    this.getJSONSorter=function(){
    	if (this.headerOrders==null) return null;
    	return JSON.stringify(this.headerOrders);
    };
    this.deleteOrderInHeaders=function(headerId){
    	// Only one order
    	this.headerOrders=null;
    	// More than one order
    	/*
    	if (this.headerOrders==null) return;
    	var index=null;
    	LMJQ(this.headerOrders).each(function(i,elem){
    		if(elem.headerId==headerId){ index=i; }
    	});
    	if (index!=null) this.headerOrders.splice(index,1);
    	return;
    	*/
    };
};
var LmSorterElem = function(){
	//Implements: [Options, Events],
	this.headerId=null;
	this.order='asc';
	/*initialize: function(options){
		this.setOptions(options);
	},*/
	this.setSort=function(headerId,order){
		this.headerId=headerId;
		this.order=order;
	};
	this.getSort=function(){
		var arrInt=new Array();
    	arrInt[0]=this.headerId;
    	arrInt[1]=this.order;
    	return arrInt;
	};
};
/*
var LmSorterManager = new Class({
	Implements: [Options, Events],	
	headerOrders:null,
	options:{},
    initialize: function(options){
    	this.setOptions(options);
    },
    setOrder:function(headerId,order){
    	this.deleteOrderInHeaders(headerId);
    	var sorter=new LmSorterElem();
    	sorter.setSort(headerId,order);
    	if(this.headerOrders==null) this.headerOrders=new Array();
    	this.headerOrders.splice(0,0,sorter);
    },
    getJSONSorter:function(){
    	if (this.headerOrders==null) return null;
    	return JSON.stringify(this.headerOrders);
    },
    deleteOrderInHeaders:function(headerId){
    	if (this.headerOrders==null) return;
    	var index=null;
    	this.headerOrders.each(function(elem,i){
    		if(elem.headerId==headerId){ index=i; }
    	});
    	if (index!=null) this.headerOrders.splice(index,1);
    	return;
    }
});
var LmSorterElem = new Class({
	Implements: [Options, Events],
	headerId:null,
	order:'asc',
	initialize: function(options){
		this.setOptions(options);
	},
	setSort:function(headerId,order){
		this.headerId=headerId;
		this.order=order;
	},
	getSort:function(){
		var arrInt=new Array();
    	arrInt[0]=this.headerId;
    	arrInt[1]=this.order;
    	return arrInt;
	}
});
*/