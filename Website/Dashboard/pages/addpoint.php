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
             <div class="col-lg-12">
                    	    <?php
                    	    $id = $_GET['q'];
                            $l = $_GET['l'];
                            
                            if($l == 1)
                            	$result1 = '<div class="alert alert-success">The Earth Station has been successfully addded!</div>';
                     	    echo $result1;
                     	    ?> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Basic Information and Sensors
                        </div>
                        <div class="panel-body">
                            <div class="row"><script>
			// In the following example, markers appear when the user clicks on the map.
			// The markers are stored in an array.
			// The user can then click an option to hide, show or delete the markers.
			var map;
			var markers = [];
			var lat = 42.962623;
			var lon = -81.364819;
			//var lat = latR.map(Number);
			//var lon = lonR.map(Number);
			
			function initMap() {
			  var haightAshbury = {lat: lat, lng: lon};
			
			  map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 12,
			    center: haightAshbury,
			    mapTypeId: 'terrain'
			  });
			addMarker(lat, lon);
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
			}
			}
			
			// Sets the map on all markers in the array.
			
		</script>
		 <div class="col-lg-6">
                                    <form method="GET" action="addES.php">
                                        <div class="form-group">
                                            <input type="hidden" name="l" value="1">
                                            
                                            <label>Earth Station ID Number: </label>
                                            <input class="form-control" name="esid">
                                            <p class="help-block">Ex. 111-000-111</p>
                                            
                                            <label>Earth Station Name: </label>
                                            <input class="form-control" name="name"><br>
                                            
                                            <label>Communication Hub: </label>
                                            <select class="form-control" name="comid">
                                               <?php
                                                include('config.php');
                                                $sql = "SELECT * FROM FarmInfo WHERE UserID =$id";
					
						$result = $conn->query($sql);
						
						if ($result->num_rows > 0) {
						    // output data of each row
						    while($row = $result->fetch_assoc()) {
						     		echo '<option value="'. $row['FarmID'] .'">'. $row['FName'] .'</option>';
						      }}
						?>
                                            </select>
                                            <br>
                                            <label>Sub Farm: </label>
                                            <select class="form-control">
                                               <?php
                                               include ('config.php');
						$sql = "SELECT * FROM FarmInfo WHERE UserID =$id";
					
						$result = $conn->query($sql);
						
						if ($result->num_rows > 0) {
						    // output data of each row
						    while($row = $result->fetch_assoc()) {
						     		echo '<option value="'. $row['FarmID'] .'">'. $row['FName'] .'</option>';
						      }}
						?>
                                            </select>
                                        </div>
                              </div>
                              <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Sensor One: </label>
                                            <select class="form-control" name="sensOne" required>
                                            	<option>Select One...</option>
                                                <option value="1">Moisture</option>
                                                <option value="2">Temperature</option>
                                                <option value="3">Nitrogen</option>
                                                <option value="4">Light</option>
                                                <option value="5">Rain</option>
                                            </select>
                                            <p class="help-block">Choose one of the following</p>
                                           <label>Sensor Two: </label>
                                            <select class="form-control" name="sensTwo" required>
                                            	<option>Select One...</option>
                                                <option value="1">Moisture</option>
                                                <option value="2">Temperature</option>
                                                <option value="3">Nitrogen</option>
                                                <option value="4">Light</option>
                                                <option value="5">Rain</option>
                                            </select>
                                            <br>
                                            <label>Sensor Three: </label>
                                            <select class="form-control" name="sensThree" required>
                                            	<option>Select One...</option>
                                                <option value="1">Moisture</option>
                                                <option value="2">Temperature</option>
                                                <option value="3">Nitrogen</option>
                                                <option value="4">Light</option>
                                                <option value="5">Rain</option>
                                            </select>
                                            
                                            <br>
                                            <label>Plant: </label>
                                            <select class="form-control" name="plantid">
                                                <?php
                                               include ('config.php');
						$sql = "SELECT * FROM PlantID";
					
						$result = $conn->query($sql);
						
						if ($result->num_rows > 0) {
						    // output data of each row
						    while($row = $result->fetch_assoc()) {
						     	echo '<option value="'. $row['PlantID'] .'">'. $row['CommonName'] .'</option>';
						      }}
						?>
                                            </select>
                                        </div>
                                        </div>
                           </div>
                       </div>
                                       
                               	<div>
                            </div>
                       </div>
                   </div>
              </div>
                  
             
            <!-- /.row -->
            <div class="row">
             <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Earth Station Location
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
		<div><div id="map"></div></div>
		<!-- Replace the value of the key parameter with your own API key. -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&callback=initMap">
    </script>
     <div class="col-lg-6">
                                   <label>Latitude: </label>
                                   <input class="form-control" name="lat" id="lat" onClick="getSoilData(this);" >
                                </div>
                                
                                <div class="col-lg-6">
                                   <label>Longitude: </label>
                                   <input class="form-control" name="lon" id="lon" onClick="getSoilData(this);" >
                                </div>
                               
                                 </div>
                                       
                               	<div>
                            </div>
                       </div>
                   </div>
              </div>
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Add Earth Station!" name="submit"><br>
               </form>                       
            </div>
             
            <!-- /.row -->
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