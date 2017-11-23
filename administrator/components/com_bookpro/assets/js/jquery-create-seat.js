(function($) { // Hide scope, no $ conflict
    /* creteseat manager. */
    function creteseat() {
         this._defaults = {
             row:10,
             column:10
             
         }
    }
    $.extend(creteseat.prototype, {
       dataName: 'creteseat',
       markerClass: 'hasCreteseat',
       _attachCreteseat: function(target, settings) {
            target = $(target);
            if (target.hasClass(this.markerClass)) {
                return;
            }
            target.addClass(this.markerClass);
            var inst = {target: target};
            this._update(target[0]);
            target.bind('keydown.' + this.dataName, this._keyDown).
                bind('keypress.' + this.dataName, this._keyPress).
                bind('keyup.' + this.dataName, this._keyUp);
                
       },
       _update: function(target, hidden) {
          target = $(target.target || target);
          var inst = $.data(target[0], $.creteseat.dataName);
           alert(inst);
          if (inst) {
              if (inst.inline || $.creteseat.curInst == inst) {
                    
              }
              if (inst.inline) {
                target.html(this._generateContent(target[0]));
                target.focus();
              }
          }
         
       },
       _generateContent: function(target) {
            return "sdfsdfds";
       },
       _keyDown: function(event) {
           alert(1);
       }, 
       _keyPress: function(event) {
       },
       _keyUp: function(event) {
           
       }   
    });
    $.fn.creteseat = function(options) {
        
        var otherArgs = Array.prototype.slice.call(arguments, 1);
        if ($.inArray(options, ['getDate', 'isDisabled', 'isSelectable', 'options', 'retrieveDate']) > -1) {
            return $.creteseat[options].apply($.creteseat, [this[0]].concat(otherArgs));
        }
        return this.each(function() {
            if (typeof options == 'string') {
                $.datepick[options].apply($.creteseat, [this].concat(otherArgs));
            }
            else {
                $.creteseat._attachCreteseat(this, options || {});
            }
        });
    };
    $.creteseat = new creteseat(); // singleton instance
    $(function() {
        $(document).mousedown($.creteseat._checkExternalClick).
            resize(function() { $.creteseat.hide($.creteseat.curInst); });
    });
})(jQuery);