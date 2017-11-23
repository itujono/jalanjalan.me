<?php 

$points = $displayData;

$config=JBFactory::getConfig();

$start=implode(',',array_shift($points));
$end=implode(',', array_pop($points));

$str=array();
if(count($points)>0){
	
	for ($i = 0; $i < count($points); $i++) {
		$str[]="{ location:'".implode(',', $points[$i]) ."',stopover: true }";
		
	}
	
	$waypoint='['. implode(',', $str).']';
	
}


//echo $waypoint;die;

?>
<style>
#map {
       	width: 100%;
        height: 500px;
      }

 #directions-panel {
        margin-top: 10px;
        background-color: #FFEE77;
        padding: 10px;
      }
    </style>
    
  <div id="map"></div>



<script>
function initMap() {
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 6
	});
		directionsDisplay.setMap(map);
		calculateAndDisplayRoute(directionsService, directionsDisplay);

}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {
	var waypts = [
	{
		location: '20.5835196,105.92299000000003',
		stopover: false,
	},{
		location: '20.2129969,105.92299000000003',
		stopover: true
		}];

	waypts=<?php echo $waypoint?$waypoint:'null'; ?>;

	directionsService.route({
		origin:'<?php echo $start ?>',
		destination:'<?php echo $end ?>',
		waypoints: waypts,
		optimizeWaypoints: true,
		travelMode: 'DRIVING'
		avoidHighways: true,
  		avoidTolls: true,

	}, function(response, status) {
		if (status === 'OK') {
			directionsDisplay.setDirections(response);
			var route = response.routes[0];
			var summaryPanel = document.getElementById('directions-panel');
			summaryPanel.innerHTML = '';
			// For each route, display summary information.
			for (var i = 0; i < route.legs.length; i++) {
				var routeSegment = i + 1;
				summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
				'</b><br>';
				summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
				summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
				summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
			}
		} else {
			window.alert('Directions request failed due to ' + status);
		}
	});
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $config->get('gmap_api') ?>&callback=initMap"> </script>