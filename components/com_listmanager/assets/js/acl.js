var LmAcl  = function(){
	//Implements: [Options, Events],
	this.add=false;
	this.del=false;
	this.edit=false;
	this.detail=false;
	this.detailpdf=false;
	this.detailrtf=false;
	this.bulk=false;
	this.headers=new Array();	
	/*options:{},
    initialize: function(options){
    	this.setOptions(options);
    },*/
	this.setAcl=function(aclstring){
    	var arrAcl=aclstring.split('#');
    	for(var i=0;i<arrAcl.length;i++){
    		switch(arrAcl[i]){
    			case 'detail': this.detail=true; break;
    			case 'detailpdf': this.detailpdf=true; break;
    			case 'detailrtf': this.detailrtf=true; break;
    			case 'add': this.add=true; break;
    			case 'delete': this.del=true; break;
    			case 'edit': this.edit=true; break;
    			case 'bulk': this.bulk=true; break;
    			default: this.addViewHeader(arrAcl[i]);
    		}
    	}
    };    
    this.addViewHeader=function(id){
    	if (!isNaN(id)&&id.length>0) this.headers.include(id);
    	return;
    };
    this.isDelete= function(){ return this.del; };
    this.isAdd= function(){ return this.add; };
    this.isDetail=function(){ return this.detail; };
    this.isDetailpdf=function(){ return this.detailpdf; };
    this.isDetailrtf=function(){ return this.detailrtf; };
    this.isBulk=function(){ return this.bulk; };
    this.isEdit= function(id){
    	if(this.edit) return true;
    	if(this.headers.indexOf(id)!=-1) return true;
    	return false;
    };
    this.isGlobalEdit= function(){
    	if(this.edit) return true;
    	if(this.headers.length>0) return true;
    	return false;
    };
};
