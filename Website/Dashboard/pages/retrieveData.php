<!DOCTYPE HTML>  
<?php
include('config.php');
$comid = $_GET['n'];
if($comid==0)
{
	$popup = '<div class="alert alert-info">Please select from the dropdown below to continue!</div>';
}
else
{
	$sql = "SELECT * FROM FarmInfo WHERE FarmID= $comid";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$FName = $row['FName'];
	}
	$popup = '<div class="alert alert-info">Searching for Communication Hub:<bold> '. $FName .' </bold></div>';
}
$id = $_GET['q'];
$sql = sprintf('SELECT * FROM ESInfo WHERE UserID= %d', $id);
if($comid!=0)
	$sql = "SELECT * FROM ESInfo WHERE UserID =$id AND ComID=$comid";
	
$result = $conn->query($sql);
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);					
	while($row = $result->fetch_assoc()) {
	    $ESID = $row['ESID'];
	
	    $plusLat = $row['lon'] + 0.0005;
	    $minusLat = $row['lon'] - 0.0005;
	    $plusLon = $row['lat'] + 0.0005;
	    $minusLon = $row['lat'] - 0.0005;
	    
	    $sqlHis = sprintf('SELECT * FROM ESHis WHERE ESID = %d', $ESID);
	    $resultHis = $conn->query($sqlHis);
	    $dataValues = array();
	    $timestampValues = array();
	    $dataAverage = 0;
	
	    while($rowHis = $resultHis->fetch_assoc()){
	        //You don't need the index
	        $dataValues[] = $rowHis['value'];
	    	$timestampValues[] = $rowHis['TimeStamp'];
	    	$dataAverage = $dataAverage + $rowHis['value'];
	    }
	    $dataAverage = $dataAverage/count($dataValues);
	    $max = 0;
	    for($i = 0; $i < count($dataValues); $i++)
	    {
	    	if($timestampValues[$i] > $timestampValues[$max])
	    	{
	    		$max = $i;
	    	}
	    }
	    $colorCode = '#00' . dechex(round($dataValues[$max]/4.01568627451));
	    $colorCode = $colorCode . '00';
	  /*  $colorAverage = round($dataAverage/100);
	    
	    if($colorAverage <= 3)
	    	$colorCode = '#FF0000';
	    
	    else if($colorAverage > 3 && $colorAverage <= 6)
	    	$colorCode = '#FFF000';
	    
	    else if($colorAverage > 6 && $colorAverage <= 8)
	    	$colorCode = '#78FF00';
	    	
	    else
	    	$colorCode = '#001FFF';*/
	    	
	    $geojson['features'][] = array(
	        'type' => 'Feature',
	        'geometry' => array(
	            'type' => 'Polygon',
	            'coordinates' => array(array(
	                array($plusLat, $plusLon),
	                array($minusLat, $plusLon),
	                array($minusLat, $minusLon),
	                array($plusLat, $minusLon),
	                array($plusLat, $plusLon)
	                )
	            )),
	        'properties' => array(
	            'id' => $ESID,
	            'color' => $colorCode,
	            'average' => $dataAverage,
	            'data' => array($dataValues),
	            'timestamp' => array($timestampValues)
	        ),
	    );
	}
	file_put_contents('esdata.json', json_encode($geojson, JSON_NUMERIC_CHECK));
?>

<?php
	$sql = "SELECT * FROM ESInfo WHERE ComID ='$comid'";
        $result = $conn->query($sql);
	$i = 0;
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		$sensors[$row['ESID']][0] = $row['Type_SENS1'];
		$sensors[$row['ESID']][1] = $row['Type_SENS2'];
		$sensors[$row['ESID']][2] = $row['Type_SENS3'];
		$values[$row['ESID']][0] = $row['Data_SENS1'];
		$values[$row['ESID']][1] = $row['Data_SENS2'];
		$values[$row['ESID']][2] = $row['Data_SENS3'];
		$esinfo[$i] = $row['ESID'];
		$esname[$i] = $row['name'];
		$i++;	
	}}
	


?>

<?php
	$ftime = true;
	include ('config.php');
	$sql = "SELECT * FROM PolygonShape WHERE ComID ='$comid'";
	$xmlString = '<s><my name="Yo Bro">';	
        $result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script><style>
      #map {
        height: 100%;
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

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
     <meta name="author" content="">
   <title>DRIP - Your Farming Tool</title>

    <!-- Bootstrap Core CSS -->

    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

</head>

<body>      
    <script>
	var sensorsR = <?php echo json_encode($sensors);?>;
	var earthstationsR = <?php echo json_encode($esinfo);?>;
	var valuesR = <?php echo json_encode($values);?>;
	var nameR = <?php echo json_encode($esname);?>;
	var sensors = sensorsR.map(Number);
	var earthstations = earthstationsR.map(Number);
	var values = valuesR.map(Number);
        var map;
	function initMap() {
	    map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 15,
            mapTypeId: 'hybrid',
            disableDefaultUI: true,
	    center: {lat: 42.964508, lng: -81.367793}
	  });
		map.data.setStyle(function(feature) {
	          return /** @type {google.maps.Data.StyleOptions} */({
	            fillColor: feature.getProperty('color'),
	            strokeWeight: 1
	          });
	        });
	
	
        
	map.data.addListener('click', function(event) {
	  changeValuesTabs(event.feature.getProperty("id"));
	  
	});
	
	function changeValuesTabs(id)
	{
		returnUnits(sensorsR[id][0], "sens1", valuesR[id][0]);
		returnUnits(sensorsR[id][1], "sens2", valuesR[id][1]);
		returnUnits(sensorsR[id][0], "sens3", valuesR[id][0]);
		advancedInfo(id);	
	}
	
	function advancedInfo (id)
	{
		var esInstance = 0;
		for(var i = 0; i < nameR.length; i++)
		{
			if(earthstationsR[i] == id)
			{
				esInstance = i;
				i = nameR.length;	
			}
		}
		document.getElementById("sensType1").innerHTML = "<b>Sensor 1: </b> " + identSens(sensorsR[id][0]) + "";
		document.getElementById("sensType2").innerHTML = "<b>Sensor 2: </b> " + identSens(sensorsR[id][1]) + "";
		document.getElementById("sensType3").innerHTML = "<b>Sensor 3: </b> " + identSens(sensorsR[id][2]) + "";
		document.getElementById("esname").innerHTML = "<b>Earth Station Name: </b> " + nameR[esInstance] + "";
		document.getElementById("esid").innerHTML = "<b>Earth Station ID: </b> " + id + "";
		document.getElementById("edit").innerHTML = '<a href="editES.php?q=' + id + '"><i class="fa fa-fw fa-pencil"></i></a>';
	}
	
	function identSens (sensor)
	{
		switch(sensor)
		{
			case "1":
			return "Moisture";
			break;
			
			case "2":
			return "Temperature";
			break;
			
			case "3":
			return "Nitrogen";
			break;
		}
	}
	
	function returnUnits(sensorID, sensor, value)
	{
		switch(sensorID)
		{
			case "1":
			var percentage = Math.round(value/1024*100);
			document.getElementById(sensor).innerHTML = "<i class='fa fa-anchor fa-4x'></i></br>";
			document.getElementById(sensor).innerHTML = "<center>" + document.getElementById(sensor).innerHTML + "<h4>" + percentage + "%</h4></br>Moisture</center>";
			break;
			
			case "2":
			document.getElementById(sensor).innerHTML = "<center><i class='fa fa-sun-o fa-4x'></i></br></center>";
			document.getElementById(sensor).innerHTML = "<center>" + document.getElementById(sensor).innerHTML + "<h4>" + value + "℃</h4></br>Temperature</center>";
			break;
		}
	}
	
	
	
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
		            polygons.push(new google.maps.Polygon({
		                paths: arr,
		                strokeColor: '#FF0000',
		                strokeOpacity: 0.8,
		                strokeWeight: 2,
		                fillColor: '#FF0000',
		                fillOpacity: 0.2
		            }));
		            polygons[polygons.length-1].setMap(map);
		        }
		  map.fitBounds(bounds);
	  map.data.loadGeoJson(
	      'esdata.json');
		}
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
		
		google.maps.event.addDomListener(window, 'load', initialize);
    </script> 
    <div id="map" class="col-lg-7"></div>
    <div class="col-lg-1"></div>
    <div class="col-lg-4"><br>
    		<?php echo $popup;?>
		<form method="GET" action="retrieveData.php">
		     <input type="hidden" value="<?php echo $id;?>" name="q">	
		     <select class="form-control" name="n">
		         <option value="0">Select One...</option>                                     
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
		     </select><br>
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Map it!" name="submit"><br>
		
		</form>

     <div class="row">
                <div id="tabs">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Basic Tabs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#real" data-toggle="tab">Real Time Data</a>
                                </li>
                                <li><a href="#advanced" data-toggle="tab">Advanced Info</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="real">
                                    <h3>Real Time Data</h3>
                                    </br><div class="col-lg-4" id="sens1"></div><div class="col-lg-4" id="sens2"></div><div class="col-lg-4" id="sens3"></div>
                                </div>
                                <div class="tab-pane fade" id="advanced">
                                    <h3>Advanced Info</h3>
                                    <div class="col-lg-11"><div id="esname"></div><div id="esid"></div><div id="sensType1"></div><div id="sensType2"></div><div id="sensType3"></div></div><div class="col-lg-1"><div id="edit"></div></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
           </div>
                </div>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&callback=initMap">
    </script>
    
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

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    </script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>