


function addLeadingZero(num) {
    if (num < 10) {
      return "0" + num;
    } else {
      return "" + num;
    }
   }
jQuery(document).ready(function($) {
if(jQuery('#filter_from').val() && jQuery('#filter_to')){
	//alert('Init date picker');
	jQuery.ajax({
		type:"GET",
		url: "index.php?option=com_bookpro&task=getdepart_date&format=raw",
		data:"to="+jQuery('#filter_to').val()+'&from='+jQuery('#filter_from').val(),
		success:function(result){
				
				var array = JSON.parse(result);
				jQuery('.start_date').datepicker({
					
					startDate: "",
				    endDate: "+10m",
				    autoclose: true,
				    language: local,
					format: date_format,
					beforeShowDay: function(date) {            
		                dmy = addLeadingZero(date.getDate()) + "-" + addLeadingZero((date.getMonth()+1)) + "-" + date.getFullYear();
			            var check=jQuery.inArray(dmy, array);
			            //console.log(check);
				           if(check==-1){
			               	 return {
		                         enabled : false
		                      };
				           }
		               }
		          		
					});
				//	jQuery('.start_date').datepicker('show');
			}
		});
	
	jQuery.ajax({
		type:"GET",
		url: "index.php?option=com_bookpro&task=getdepart_date&format=raw",
		data:"to="+jQuery('#filter_from').val()+'&from='+jQuery('#filter_to').val(),
		success:function(result){
				console.log(result);
				var array = JSON.parse(result);
				jQuery('.end_date').datepicker({
					
					startDate: "",
				    endDate: "+10m",
				    autoclose: true,
				    language: local,
					format: date_format,
					beforeShowDay: function(date) {            
		                dmy = addLeadingZero(date.getDate()) + "-" + addLeadingZero((date.getMonth()+1)) + "-" + date.getFullYear();
			            var check=jQuery.inArray(dmy, array);
			           //console.log(check);
				           if(check==-1){
			               	 return {
		                         enabled : false
		                      };
				           }
		               }
						
		          		
					});
				//jQuery('.end_date').datepicker('startDate',d);
			}
		});
	
}
});

function jbdatepicker(format){
	
jQuery(document).on('change','#filter_to',function(){
		jQuery('.start_date').datepicker('remove');
		jQuery.ajax({
			type:"GET",
			url: "index.php?option=com_bookpro&task=getdepart_date&format=raw",
			data:"to="+jQuery(this).val()+'&from='+jQuery('#filter_from').val(),
			success:function(result){
					
					var array = JSON.parse(result);
					jQuery('.start_date').datepicker({
						
						startDate: "",
					    endDate: "+10m",
					    autoclose: true,
					    language: local,
						format: date_format,
						beforeShowDay: function(date) {            
			                dmy = addLeadingZero(date.getDate()) + "-" + addLeadingZero((date.getMonth()+1)) + "-" + date.getFullYear();
				            var check=jQuery.inArray(dmy, array);
				            //console.log(check);
					           if(check==-1){
				               	 return {
			                         enabled : false
			                      };
					           }
			               }
			          		
						});
					//	jQuery('.start_date').datepicker('show');
				}
			});
	});

jQuery(document).on('change','#filter_to',function(){
	jQuery('.end_date').datepicker('remove');
	jQuery.ajax({
		type:"GET",
		url: "index.php?option=com_bookpro&task=getdepart_date&format=raw",
		data:"to="+jQuery('#filter_from').val()+'&from='+jQuery(this).val(),
		success:function(result){
				
				var array = JSON.parse(result);
				jQuery('.end_date').datepicker({
					
					startDate: "",
				    endDate: "+10m",
				    autoclose: true,
				    language: local,
					format: date_format,
					beforeShowDay: function(date) {            
		                dmy = addLeadingZero(date.getDate()) + "-" + addLeadingZero((date.getMonth()+1)) + "-" + date.getFullYear();
			            var check=jQuery.inArray(dmy, array);
			            console.log(check);
				           if(check==-1){
			               	 return {
		                         enabled : false
		                      };
				           }
		               }
						
		          		
					});
				//jQuery('.end_date').datepicker('show');
			}
		});
});
}
