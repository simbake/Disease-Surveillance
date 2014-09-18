<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
$(function() {

//load();
$("#filter_graph").click(function(){
	//get the selected parameters
	var disease = $("#disease").attr("value");
	var type = $("#type").attr("value");
	var start_week = $("#from").attr("value");
	var end_week = $("#to").attr("value");
	var reporting_year = $("#year").attr("value");
	var district = $("#district").attr("value");
	//Reset the marker parameters
	markers = [];
	//call the map function with the selected parameters
	load(disease,type,start_week,end_week,reporting_year,district);
	
});
});
	var map;
    var markers = [];
    var infoWindow;
    var locationSelect;
    var markerCluster;
  //<![CDATA[
    function load(disease,type,start_week,end_week,reporting_year,district) {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(0.35156,37.913818),
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
      var opt = { minZoom: 7 };
      map.setOptions(opt);
      infoWindow = new google.maps.InfoWindow();
      //generate the url for getting the data
      var url = "map_display/get_data/"+disease+"/"+type+"/"+start_week+"/"+end_week+"/"+reporting_year+"/"+district;
	downloadUrl(url, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("contact");
         var facility_id = markerNodes[i].getAttribute("facility_id");
         var totals = markerNodes[i].getAttribute("totals");
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));
         createMarker(latlng, name, address,facility_id,totals);
         bounds.extend(latlng);  	    
       }
       map.fitBounds(bounds);
       markerCluster = new MarkerClusterer(map, markers,{ zoomOnClick: false });
       google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
           var covered_markers = cluster.getMarkers();
           
			var uniqueness = [];
           for (marker in covered_markers)
           {
           if(uniqueness.indexOf(covered_markers[marker].facility_id) == -1){
               uniqueness.push(covered_markers[marker].facility_id);
           }
           }
           if(uniqueness.length == 1){
           google.maps.event.trigger(covered_markers[0], 'click'); 
           }
   	});
          
      });	
   }
    
    

   function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
   }
    function createMarker(latlng, name, address, facility_id, totals) {
      var html = "<div style='min-width:100px; min-height:100px;'><b>" + name + "</b> <br><br><b>Telephone: </b>" + address+"<br><br><b>Number Reported: </b>"+totals+"</div>";
      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        facility_id: facility_id,
        statistics: totals
      });       
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }


    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>
</script>
<style>
select{
	margin:5px 0;
}
	.top_graphs_container{
		width:980px;
		margin: 0 auto;
		overflow: hidden;
	}
	.graph{
		width:970px;  
	}
	.graph_title{
		letter-spacing: 1px;
		font-size: 10px;
		font-weight: bold;
		margin: 0 auto;
		width:300px;
	}
	#notifications_panel{
		width:100%;
	}
	.message{
		width:300px;
	}
	.notification_link{
		text-decoration: none;
		float:left;
		margin: 5px;
	}
	h2{
		margin-left:100px; 
	}
	.disease_container{  
		float:left;
		position: relative;
		padding: .5em 0;
	}
	ul.diseases {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	.disease-text {
		margin-right: 0; 
		line-height: 1.3;
		margin-right: 1.75em;
		display: inline-block;
		}
</style>
<div class="top_graphs_container">
<div class="graph">
<div id="daily_rg_filter">
	
	<fieldset style="width:750px; display: inline;"><legend>Select Parameters to Map</legend>
		<b>Disease: </b>
		<select id="disease">
		<?php 
		foreach($diseases as $disease){?>
			<option value="<?php echo $disease['id']?>"><?php echo $disease['Name']?></option>
		<?php }
		?>
	</select>
	<b>District: </b>
	<select id="district">
		<?php 
		foreach($districts as $district){?>
			<option value="<?php echo $district['id']?>"><?php echo $district['Name']?></option>
		<?php }
		?>
	</select>
	<b>Statistic Type: </b>
	<select id="type">
		<option value="1">Cases Only</option>
		<option value="2">Deaths Only</option>
	</select>

	
	<b>Epiweek From: </b>	<select id="from">
		<?php 
			for($x=1; $x<=52; $x++){ ?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
		<?php }
		?>
	</select> <b>Epiweek To: </b>	<select id="to">
		<?php 
			for($x=1; $x<=52; $x++){ ?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>
		<?php }
		?>
	</select>
	<b>Year: </b><select id="year">
	<?php  
		$year = date('Y');
		$counter = 0;
		for($x=0;$x<=10;$x++){ ?>
			<option <?php if($counter == 0){echo "selected";}?> value="<?php echo $year;?>"><?php echo $year;?></option>
			
		<?php 
		$counter++;
		$year--;
		}
	?>
	</select>
	<input type="button" id="filter_graph" value="Show Map" class="button"/>
	</fieldset>
	
</div>
<div id = "map" style="width: 850px; height: 450px;" title="Daily Activity Graph" ></div>
</div>

</div>