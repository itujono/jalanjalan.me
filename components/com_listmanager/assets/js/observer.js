var Observable = function() {
    this.subscribers = [];
}
 
Observable.prototype = {
    subscribe: function(type,callback) {
		if (this.subscribers[type]===undefined){
			this.subscribers[type]=[];
		}
        this.subscribers[type].push(callback);
    },
    unsubscribe: function(type,callback) {
    	if (this.subscribers[type]===undefined) return;
        var i = 0,
            len = this.subscribers[type].length;       
        for (; i < len; i++) {
            if (this.subscribers[type][i] === callback) {
                this.subscribers[type].splice(i, 1);
                return;
            }
        }
    },
    publish: function(type,data) {
    	if (this.subscribers[type]===undefined){
			this.subscribers[type]=[];
		}
        var i = 0,
            len = this.subscribers[type].length;
        for (; i < len; i++) {
            this.subscribers[type][i](data);
        }        
    }
};
LMJQ(document).ready(function() {
	observable = new Observable();  
});
