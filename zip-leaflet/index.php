<!DOCTYPE html>
<html lang="">
<head>
  <meta charset="utf-8">
  
	    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	  	<link rel="stylesheet" href="http://code.leafletjs.com/leaflet-0.3.1/leaflet.css" />  
	  	<!--[if lte IE 8]>
	    	<link rel="stylesheet" href="http://code.leafletjs.com/leaflet-0.3.1/leaflet.ie.css" />
	  	<![endif]-->
	  	<link rel="stylesheet" href="includes/css/main.css" />
		<script src="http://code.leafletjs.com/leaflet-0.3.1/leaflet.js"></script>
		<script src="includes/js/animate.js"></script>

		<title></title>
		<meta name="description" content="" />
	  	<meta name="keywords" content="" />
		<meta name="robots" content="" />
</head>

<body>

<div id="container" STYLE="HEIGHT: 100%; WIDTH: 100%; ">
	<div id="header">
		<h1>instagram + zipcar</h1>
		<div id="nav">View Hashtag <img src="includes/img/orangearrow.png"></div>
	</div><! -- Header -->
	
	<div id="map" style="height: 100%; width: 100%;"></div><!-- Map gets inserted here -->
	
	<div id="carousel"></div><!-- Photo Carousel -->
	
	<div id="footer">
	
	</div><!-- Footer -->
	
	<div id="zip-logo"><img src="includes/img/zip-logo.png" /></div>
</div>

  <script type="text/javascript">
  
$(document).ready(function() {
 
	var map = new L.Map('map');
	
	var alldata = null;

	var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/cfc96afd35bc4c12b3f06893fff79e8c/60666/256/{z}/{x}/{y}.png',
		cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade',
		cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 12, attribution: cloudmadeAttribution});
			

	map.setView(new L.LatLng(46, -52), 3).addLayer(cloudmade);
		
				
	$.getJSON("photos.json", function(data) { 


	var geojsonLayer = new L.GeoJSON(data, {
	    pointToLayer: function (latlng){
	        return new L.CircleMarker(latlng, {
	            radius: 8,
		           fillColor: "#fecb00",
		           color: "#fecb00",
		           weight: 1,
		           opacity: 1,
		           fillOpacity: 0.9,
		           //bindPopup: Whazza
		       });
		       
		   }

	});
	


	map.addLayer(geojsonLayer); //Add layer to map 


	var alldata = data; // Passes json data to new variable
    //console.log(alldata);
    
    for (var i = 0; i < 7; i++ )
			{			
        		$("#carousel").append("<div class='zoom'><a href='#'><img src='" + alldata.features[i].properties.image + "' width='70' height='70' /></a></div>" );

        	}
    

	}); 
	
	
	//This is the as-of-yet-disfunctional zoom script
	
	        var cont_left = $("#carousel").position().left;
        $("a img").hover(function() {
            // hover in
            console.log(this);
            $(this).parent().parent().css("z-index", 1);
            $(this).animate({
               height: "120",
               width: "120",
               left: "-=50",
               top: "-=50"
            }, "fast");
        }, function() {
            // hover out
            $(this).parent().parent().css("z-index", 0);
            $(this).animate({
                height: "70",
                width: "70",
                left: "+=50",
               top: "+=50"
            }, "fast");
        });
        
        $(".zoom").each(function(index) {
            var left = (index * 80) + cont_left;
            $(this).css("left", left + "px");
        });
        
        
});



  </script>

</body>
</html>