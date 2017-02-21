<?php
	include('config.php');
	$id = $_GET['q'];
	$ftime = true;
	$sql = "SELECT * FROM ESInfo WHERE UserID=$id";
	$i = 0;
	$result = $conn->query($sql);
	  if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) {
			$name[$i] = $row['name'];
			$esid[$i] = $row['ESID'];
			$lat[$i] = $row['lat'];
			$lon[$i] = $row['lon'];
		    	$i++;
		}
	  }
 	 else
  		$response = '<div class="alert alert-danger">There was an error adding this Earth Station. The support team has been notified!</div>';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
    html { height: 100% }
    body { height: 100%; margin: 0; padding: 0 }
    #map { height: 90%; width: 100% }
</style>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
 
<select class="form-control" id="dropdown" onchange="changeES()">
	<?php 
		for($i = 0; $i < count($lat); $i++)
		{
			echo '<option value="' . $i . '">' . $name[$i] . '</option>';	
		}
	?>

</select> 
  <br>  
<script type="text/javascript">
      
    var curEarthStation = 0;
    var pointsLatR = <?php echo json_encode($lat);?>;
    var pointsLonR = <?php echo json_encode($lon);?>;
    var pointsLat = pointsLatR.map(Number);
    var pointsLon = pointsLonR.map(Number);
        
    var marker = new Array();
    var circle = new Array();
    var map;
    
    function changeES() {
    	circle[curEarthStation].setRadius(10);
    	var e = document.getElementById("dropdown");
	curEarthStation = e.options[e.selectedIndex].value;
	var center = new google.maps.LatLng(pointsLat[curEarthStation], pointsLon[curEarthStation]);
	map.setCenter(center)
    }
    
    function initialize() {
        
        var mapOptions = {
            center: {lat: 42.962623, lng: -81.365819},
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        map = new google.maps.Map(document.getElementById("map"),
                        mapOptions);
        
        for(i = 0; i < pointsLat.length; i++)
        { 
	  marker[i] = new google.maps.Marker({
		  map: map,
		  position: new google.maps.LatLng(pointsLat[i], pointsLon[i]),
		  title: 'Some location'
		});
		
		// Add circle overlay and bind to marker
	  circle[i] = new google.maps.Circle({
		  map: map,
		  radius: 10,    // 10 miles in metres
		  fillColor: '#AA0000'
	  });
	  circle[i].bindTo('center', marker[i], 'position');  
	  
	}

        var direction = 1;
        var rMin = 10, rMax = 20;
        setInterval(function() {
            var radius = circle[curEarthStation].getRadius();
            if ((radius > rMax) || (radius < rMin)) {
                direction *= -1;
            }
            circle[curEarthStation].setRadius(radius + direction * 10);
        }, 125);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>

    <!-- Bootstrap Core CSS -->

    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


   
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

</head>

    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCX_LMpAYnBx8wWxghrgpt2LQX2tfDxqDI&callback=initMap">
    </script>
<body>
    <div id='map'></div>
    
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