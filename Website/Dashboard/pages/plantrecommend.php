<?php
	$tanxwrbMajor = $_GET['soil'];
	$avgTempAPI = $_GET['avg'];
	$minTemperatureAPI = $_GET['low'];
	$maxTemperatureAPI = $_GET['high'];
	
	include('config.php');
	if($tanxwrbMajor != '')
	{
		$sql = "SELECT * FROM SoilCategoryID WHERE soil = '$tanxwrbMajor'";
		
	        $result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		 	$CatID = $row['CatID'];    
		    }
		}
		
		
		$sql = "SELECT * FROM Categories WHERE ID = '$CatID'";
				
	        $result = $conn->query($sql);
	
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		 	$CAT = $row['CAT']; 	   
		 	$des = $row['des'];
		    }
		}
		
		
		$sql = "SELECT * FROM SoilPlants WHERE CatID = '$CatID'";
				
	        $result = $conn->query($sql);
		$i = 0;
		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		 	$plantName[$i] = $row['CommonName']; 
		 	$selectedPlants[$i] = $row['CommonName'];
		 	$i++;
		    }
		}
		
		for($i = 0; $i < count($plantName); $i++)
		{
			$sql = "SELECT * FROM PlantID WHERE CommonName = '$plantName[$i]'";
					
		        $result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
			 	$PlantID[$i] = $row['PlantID'];
			 	$LatinName[$i] = $row['LatinName'];
			 	$des[$i] = $row['des']; 
			    }
			}
		}
		
		for($i = 0; $i < count($plantName); $i++)
		{
			$sql = "SELECT * FROM PlantInformation WHERE PlantID = '$PlantID[$i]'";
					
		        $result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
			 	$SoilpHLevel[$i] = $row['SoilpHLevel'];
			 	$Temperature[$i] = $row['Temperature'];
			 	$minTemperature[$i] = $row['minTemperature'];
			 	$maxTemperature[$i] = $row['maxTemperature'];
			    }
			}
		}
		
		for($i = 0; $i < count($plantName); $i++)
		{
			if($maxTemperatureAPI >= $maxTemperature[$i])
				$selectedPlants[$i] = "null";
			else if($minTemperatureAPI <= $minTemperature[$i])
				$selectedPlants[$i] = "null";	
		
		}
		
	
		for($i = 0; $i < count($plantName); $i++)
		{
			if($selectedPlants[$i] != 'null')
			{
				$result1 = true;
			}
		}
	}

?>
<html>
	<head>
	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<style>
			#map { height: 50%;}
		</style>
		
    <!-- Bootstrap Core CSS -->

    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]--><script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		<style>
			#map { height: 50%;}
		</style>
	</head>
	<body> <div class="row">
            
		<script>
			// In the following example, markers appear when the user clicks on the map.
			// The markers are stored in an array.
			// The user can then click an option to hide, show or delete the markers.
			var map;
			var markers = [];
			
			function initMap() {
			  var haightAshbury = {lat: 42.96510461350928, lng: -81.27687453757972};
			
			  map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 12,
			    center: haightAshbury,
			    mapTypeId: 'terrain'
			  });
			
			  // This event listener will call addMarker() when the map is clicked.
			google.maps.event.addListener(map, 'click', function( event ){
  				addMarker(event.latLng.lat(), event.latLng.lng());
			});
			
			// Adds a marker to the map and push to the array.
			function addMarker(lat, lng) {
			  var location = {lat: lat, lng: lng};
			  var marker = new google.maps.Marker({
			    position: location,
			    map: map
			  });
			  markers.push(marker);
			  document.getElementById('lat').value = lat;
			  document.getElementById('lon').value = lng;
			  changeSoil(lat, lng);
			  changeTemperature(lat, lng);
			}
			
			function changeSoil(lat, lng)
			{
				var soil = new XMLHttpRequest();
				soil.open("GET", "https://rest.soilgrids.org/query?lon=" + lng + "&lat=" + lat, false);
				soil.send(null);
				var r = JSON.parse(soil.response);
				var majorreal = r.properties.TAXGOUSDAMajor;
				document.getElementById('soil').value = majorreal;	
			}
			
			function changeTemperature(lat, lng)
			{
				var avgHigh = 0, avg = 0, avgLow = 0;
				var xhttp = new XMLHttpRequest();
				xhttp.open("GET", "http://api.wunderground.com/api/b4cd263c8d359c83/planner_05010530/q/" + lat + "," + lng +".json", false);
				xhttp.send(null);
				response = JSON.parse(xhttp.responseText);
				console.log(response); 
				avgHigh = avgHigh + parseInt(response.trip.temp_high.max.C);
				avgLow = avgLow + parseInt(response.trip.temp_high.min.C);
				avg = avg + parseInt(response.trip.temp_high.avg.C);
				for(i = 6; i < 12; i++)
				  {  
				    var xhttp = new XMLHttpRequest();
				    if(!(i > 9))
				    {
				      xhttp.open("GET", "http://api.wunderground.com/api/b4cd263c8d359c83/planner_0" + i + "010" + i + "30/q/" + lat + "," + lng +".json", false);     
				    }
				      
				    else
				    {
				      xhttp.open("GET", "http://api.wunderground.com/api/b4cd263c8d359c83/planner_" + i + "01" + i + "30/q/" + lat + "," + lng +".json", false)
				    }
				      
				    xhttp.send(null);
				    response = JSON.parse(xhttp.responseText);
				    console.log(response); 
				    avgHigh = avgHigh + parseInt(response.trip.temp_high.max.C);
				    avgLow = avgLow + parseInt(response.trip.temp_high.min.C);
				    avg = avg + parseInt(response.trip.temp_high.avg.C);
				  }
				  document.getElementById('high').value = avgHigh/7;
				  document.getElementById('low').value = avgLow/7;
				  document.getElementById('avg').value = avg/7;	
			}
			
			}
			
			// Sets the map on all markers in the array.
			
		</script>
		  <div class="col-lg-12">
                                    <form method="GET" action="plantrecommend.php">
                                        
                  
             
            <!-- /.row -->
            <div class="row">
             <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Location Picker
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
		<div><div id="map"></div></div>
		<!-- Replace the value of the key parameter with your own API key. -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&callback=initMap">
    </script>
     				<div class="col-lg-4"><br>
                                   <label>Latitude: </label>
                                   <input class="form-control" name="lat" id="lat"><br>
                                   <label>Longitude: </label>
                                   <input class="form-control" name="lon" id="lon">
                                </div>
                                
                                
     				<div class="col-lg-4"><br>
                                   <label>Soil Type: </label>
                                   <input class="form-control" name="soil" id="soil"><br>
                                   <label>Average Temperature: </label>
                                   <input class="form-control" name="avg" id="avg">
                                </div>
                                
                                
     				<div class="col-lg-4"><br>
                                   <label>High Temperature: </label>
                                   <input class="form-control" name="high" id="high"><br>
                                   <label>Low Temperature: </label>
                                   <input class="form-control" name="low" id="low">
                                </div>
                               
                                 </div>
                                       
                               	<div>
                            </div>
                       </div>
                   </div>
               </form>                       
            </div> <input type="submit" class="btn btn-primary btn-lg btn-block" value="Find the right Plant!" name="submit"><br>
             
	            <h4>Results...</h4>
	            
	            <?php
		error_reporting(0);
		$zeroResults = true;
		      if ($result1) {
		     for($i = 0; $i < count($plantName); $i++)
		     {
			$query = "SELECT * FROM PlantID WHERE CommonName = '$selectedPlants[$i]'";
	 	
			        $result = $conn->query($query);
			if ($result->num_rows > 0) {
			        while($row = $result->fetch_assoc()) {
			         echo '<div class="panel-group" id="accordion">';
	                         echo '<div class="panel panel-default">';
	                         echo '<div class="panel-heading">';
	                         echo '<h4 class="panel-title">';
	                         echo '<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">' .$row['CommonName']. ' - ' .$row['LatinName']. '</a>';
	                         echo '</h4>';
	                         echo '</div>';
	                         echo '<div id="collapseOne" class="panel-collapse collapse in">';
	                         echo '<div class="panel-body"><p>' .$row['des']. '</p><a href="plantinfo.php?q=' . $row['PlantID'] . '">Learn More <i class="fa fa-arrow-circle-right"></i></a>';
	                         echo '</div>';
	                         echo '</div>';
	                         echo '</div>';
			        }
			        $zeroResults = false;
		        }
		        }}
		        if($zeroResults)
		        {
		        	echo '<div class="alert alert-info">Your Search - <b>'. $search .'</b> - returned 0 results. Try making sure all words are spelled correctly, try different keywords, or try more general keywords. </div>';
		        }
		     
		    ?>
            <!-- /.row -->
              </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->


    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="../bower_components/datatables-responsive/js/dataTables.responsive.js"></script>
    
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    </script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

		
	</body>
</html>