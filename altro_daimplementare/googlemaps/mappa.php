<?php 
session_start();
?>
<html>
    <head>
        <title>Distributors | Transfer Oil Test</title>
            <!-- INIZIO HEADER -->
            <?php include_once("./template_pagina/header.php"); ?>
            <!-- FINE HEADER -->
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoco8k7gDNrJ6Qs3KdZyNaWMi2c3j0St8"></script>
			<script>
				//<![CDATA[
				
				var customIcons = {
					notdefined: {
					  icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
					}
				};
				var lat = 44.9648;
				var lng = 10.3792;
				var zoom = 2;
				var dati = "none";
				var drawcircle = false;
				var rag = 0;
				
				function detectMobileBrowser() {
					var useragent = navigator.userAgent;
					var mapdiv = document.getElementById("map");
				  
					if (useragent.indexOf('iPhone') != -1 || useragent.indexOf('Android') != -1 )
					{
					  mapdiv.style.width = '600px';
					  mapdiv.style.height = '800px';
					}
				}
				
				function initialize(lat, lng, zoom, drawcircle, raggio)
				{
					var mapCanvas = document.getElementById('map');
					var mapOptions = {
						center: new google.maps.LatLng(lat, lng),
						zoom: zoom,
						mapTypeId: 'roadmap'
					}
					var map = new google.maps.Map(mapCanvas, mapOptions);
					if (drawcircle)
					{
						var marker = new google.maps.Marker({
							map: map,
							position: new google.maps.LatLng(lat, lng),
							title: 'La tua posizione'
						});
						//raggio = parseInt(raggio) * Math.sqrt(2);
						var raggioreale = raggio;
						raggiocerchio = parseInt(raggioreale) * 1.21421;
						alert(raggiocerchio);
						var cityCircle = new google.maps.Circle({
						strokeColor: '#FF6600',
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: '#FF6600',
						fillOpacity: 0.35,
						map: map,
						center: new google.maps.LatLng(lat, lng),
						radius: raggiocerchio
						});
						dati = lat+"_"+lng+"_"+raggioreale;
						alert(dati);
                    }
					var infoWindow = new google.maps.InfoWindow;
					function downloadUrl(url,callback) {
						var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;
				   
							request.onreadystatechange = function()
							{
							if (request.readyState == 4)
							{
								request.onreadystatechange = doNothing;
								callback(request, request.status);
							}
						};
						request.open('GET', url, true);
						request.send(null);
					}
					function bindInfoWindow(marker, map, infoWindow, html) {
						google.maps.event.addListener(marker, 'click', function() {
							infoWindow.setContent(html);
							infoWindow.open(map, marker);
						});
					}
					downloadUrl("generaxml.php?dati="+dati, function(data)
					{
						var xml = data.responseXML;
						var markers = xml.documentElement.getElementsByTagName("marker");
						for (var i = 0; i < markers.length; i++)
						{
							var name = markers[i].getAttribute("name");
							var indirizzo = markers[i].getAttribute("address");
							var type = markers[i].getAttribute("type");
							var website = markers[i].getAttribute("website");
							var fax = markers[i].getAttribute("fax");
							var tel = markers[i].getAttribute("tel");
							var email = markers[i].getAttribute("email");
							var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
							var html = "<b>" + name + "</b> <br/> <b>Address</b>: " + indirizzo + "<br/> <b>Website:</b> " + website + "<br/> <b>Fax: </b>" + fax + "<br/> <b>Tel: </b>" + tel + "<br/> <b>E-Mail:</b> " + email;
							var icon = customIcons[type] || {};
							var marker = new google.maps.Marker({
								map: map,
								position: point,
								icon: icon.icon
							});
							bindInfoWindow(marker, map, infoWindow, html);
						}
					});
					function doNothing() {}
					detectMobileBrowser();
				}
				google.maps.event.addDomListener(window, 'load', function(){initialize(lat, lng, zoom, drawcircle, rag);});
			    
				//]]>
				$("document").ready(function()
				{
					function setPosition()
					{
						rag = $("#distanza").val();
						if (rag == "0")
						{
                            var geocoder = new google.maps.Geocoder();
							var address = $("#puntocentrale").val();
							geocoder.geocode( { 'address': address}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK) {
									lat = results[0].geometry.location.lat();
									lng = results[0].geometry.location.lng();
									initialize(lat, lng, 15);
								} 
							}); 
                        }
						else
						{
							var geocoder = new google.maps.Geocoder();
							var address = $("#puntocentrale").val();
							geocoder.geocode( { 'address': address}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK) {
									lat = results[0].geometry.location.lat();
									lng = results[0].geometry.location.lng();
									drawcircle = true;
									initialize(lat, lng, 8, drawcircle, rag);
								} 
							}); 
						}
                    }
					$("#puntocentrale").keydown(function(e){
							if(e.keyCode==13) setPosition();
					});
					$("#applicaBTN").click(function()
					{
						setPosition();
					});
					
				});
				
				
			</script>

            <!-- INIZIO CONTENUTO PAGINA -->
			<div id="filtri">
				<?php echo $localizzazione["google_map_presentation_string"][$_SESSION['lang']]; ?>
				<div class="divisore"></div>
				<?php echo $localizzazione["google_map_central_point"][$_SESSION['lang']]; ?>: <input type="text" id="puntocentrale" />
				<select id="distanza">
					<option value="0"><?php echo $localizzazione["google_map_select_distance"][$_SESSION['lang']]; ?></option>
					<option value="50000">50 KM</option>
					<option value="55860">55.86 KM</option>
					<option value="100000">100 KM</option>
					<option value="150000">150 KM</option>
					<option value="200000">200 KM</option>
					<option value="250000">250 KM</option>
					<option value="300000">300 KM</option>
				</select>
				<input type="button" id="applicaBTN" value="<?php echo $localizzazione["applyBTN"][$_SESSION['lang']]; ?>" />
				<div class="divisore"></div>
				
			</div>
            <div id="map"></div>
            <!-- FINE CONTENUTO PAGINA -->
            
            <!-- INIZIO FOOTER -->
            <?php include_once("./template_pagina/footer.php"); ?>
            <!-- FINE FOOTER -->