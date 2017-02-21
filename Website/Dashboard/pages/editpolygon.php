<?php
include('config.php');
$comid = $_GET['q'];
$i = 0;

	$ftime = true;
	include ('config.php');
	$sql = "SELECT * FROM PolygonShape WHERE ComID ='$comid'";
	$xmlString = '<s><my name="Yo Bro">';	
        $result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    		$lat1[$i] = $row['lat'];
	    		$lon1[$i] = $row['lon'];
	    		$i++;
			$string = '<coord lat="' . $row['lat'] . '" lng="' . $row['lon'] . '"/>';
			if($ftime)
			{
				$lat = $row['lat'];
				$lon = $row['lon'];
				$ftime = false;
			}
			$xmlString = $xmlString . $string;
		}
	}
	
	$finalPart = '<coord lat="' . $lat . '" lng="' . $lon . '"/>';
	$xmlString = $xmlString . $finalPart;
	$xmlString = $xmlString . '</my></s>';
	
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
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>	</head>
	<body> <div class="row">
             <div class="col-lg-12">
                            <?php
 			    include('config.php');
 			    
                     	    $sql = "SELECT * FROM FarmInfo WHERE FarmID = $comid";
			    $result = $conn->query($sql);
			
			    if ($result->num_rows > 0) {
				    // output data of each row
				   while($row = $result->fetch_assoc()) {
				    	$name = $row['FName'];
				    	$plant = $row['PlantID'];   
				    }}
                            $l = $_GET['l'];
                            
                            if($l == 1)
                            	$result1 = '<div class="alert alert-success">The Communication Hub has been successfully edited!</div>';
                     	    echo $result1;
                            
                            ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Basic Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            
                                <div class="col-lg-6">
                                    <form method="GET" action="editCom.php">
                                        <div class="form-group">
                                            <input type="hidden" name="l" value="1">	
                                        
                                            <label>Communication Hub ID Number: </label>
                                            <input class="form-control" name="comid" value="<?php echo $comid;?>">
                                            <p class="help-block">Ex. 111-000-111</p>
                                            
                                            <label>Communication Hub Name: </label>
                                            <input class="form-control" name="name" value="<?php echo $name;?>"><br>
                                        </div>
                              </div>
                              <div class="col-lg-6">
                                        <div class="form-group">
                                        
                                            <label>Date: </label>
                                            <input type="date" class="form-control" name="date">
                                            <p class="help-block">Today</p>
                                            
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
	
                               	<div>
                            </div></div></div></div>
                            <div class="panel panel-default">
                        <div class="panel-heading">
                            Map Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&libraries=geometry,drawing"></script>
	<div id="map"></div></br>
	<div class="col-lg-6">
	<label>Latitude</label>
	<textarea id="lat" name="lat" class="form-control"></textarea>
	</div>
	<div class="col-lg-6">
	<label>Longitude</label>
	<textarea id="lon" name="lon" class="form-control"></textarea>
	</div>
	<script>
	</script>
                            <script>
      var latP = <?php echo json_encode($lat1);?>;
      var lonP = <?php echo json_encode($lon1);?>;
      var latR = latP.map(Number);
      var lonR = lonP.map(Number);
	document.getElementById('lat').innerHTML = JSON.stringify(latR);
	document.getElementById('lon').innerHTML = JSON.stringify(lonR);
		  var map;
      var lat = new Array();
      var lon = new Array();
	function initMap() {
	    map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 13,
            mapTypeId: 'hybrid',
            disableDefaultUI: true,
	    center: {lat: latR[0], lng: latR[0]}
	  });
	  
	  var arr = new Array();
	var polygons = [];
	var bounds = new google.maps.LatLngBounds();
	var xmlString = <?php echo json_encode($xmlString);?>;
	var xml = xmlParse(xmlString);
	var subdivision = xml.getElementsByTagName("my");
	for (var i = 0; i < subdivision.length; i++) {
		            arr = [];
		            var coordinates = xml.documentElement.getElementsByTagName("my")[i].getElementsByTagName("coord");
		            for (var j=0; j < coordinates.length; j++) {
		              arr.push( new google.maps.LatLng(
		                    parseFloat(coordinates[j].getAttribute("lat")),
		                    parseFloat(coordinates[j].getAttribute("lng"))
		              ));
		                
		              bounds.extend(arr[arr.length-1])
		            }
		            
		            var myPolygon = new google.maps.Polygon({
		                paths: arr,
		                editable: true,
		                draggable: true,
		                strokeColor: '#FF0000',
		                strokeOpacity: 0.8,
		                strokeWeight: 2,
		                fillColor: '#FF0000',
		                fillOpacity: 0.35
		            })
		            
		            polygons.push(myPolygon);
		            polygons[polygons.length-1].setMap(map);
		        }
		  map.fitBounds(bounds);
		
		/**
		 * Parses the given XML string and returns the parsed document in a
		 * DOM data structure. This function will return an empty DOM node if
		 * XML parsing is not supported in this browser.
		 * @param {string} str XML string.
		 * @return {Element|Document} DOM.
		 */
		function xmlParse(str) {
		  if (typeof ActiveXObject != 'undefined' && typeof GetObject != 'undefined') {
		    var doc = new ActiveXObject('Microsoft.XMLDOM');
		    doc.loadXML(str);
		    return doc;
		  }
		
		  if (typeof DOMParser != 'undefined') {
		    return (new DOMParser()).parseFromString(str, 'text/xml');
		  }
		
		  return createElement('div', null);
		}
		
			
			  myPolygon.setMap(map);
			  //google.maps.event.addListener(myPolygon, "dragend", getPolygonCoords);
			  google.maps.event.addListener(myPolygon.getPath(), "insert_at", getPolygonCoords);
			  //google.maps.event.addListener(myPolygon.getPath(), "remove_at", getPolygonCoords);
			  google.maps.event.addListener(myPolygon.getPath(), "set_at", getPolygonCoords);
			
			//Display Coordinates below map
			function getPolygonCoords() {
			  var len = myPolygon.getPath().getLength();
			  var htmlStr = "";
			  for (var i = 0; i < len; i++) {
			    var stringPoints = myPolygon.getPath().getAt(i).toUrlValue(5);
			    var points = stringPoints.split(",");
			    lat[i] = points[0];
			    lon[i] = points[1];
			    }
			  document.getElementById('lat').innerHTML = JSON.stringify(lat);
			  document.getElementById('lon').innerHTML = JSON.stringify(lon);
			}
			}
	</script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&callback=initMap">
    </script><style>
      #map {
        height: 50%;
      }
      #tabs
      {
      	width: 100%;
      }      
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
                               
	
                               	<div>
                            </div>
                       </div>
                   </div>
              </div>
                  
             
            <!-- /.row -->
           
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Edit Communication Hub!" name="submit"><br>
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