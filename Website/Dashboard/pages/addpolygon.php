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
    
                     	    
                            $l = $_GET['l'];
                            
                            if($l == 1)
                            	$result1 = '<div class="alert alert-success">The Communication Hub has been successfully added!</div>';
                     	    echo $result1;
                            
                            ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Basic Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            
                                <div class="col-lg-6">
                                    <form method="GET" action="addCom.php">
                                        <div class="form-group">
                                            <input type="hidden" name="l" value="1">	
                                        
                                            <label>Communication Hub ID Number: </label>
                                            <input class="form-control" name="comid">
                                            <p class="help-block">Ex. 111-000-111</p>
                                            
                                            <label>Communication Hub Name: </label>
                                            <input class="form-control" name="name"><br>
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
		var geocoder;
		var map;
		var polygonArray = [];
		var lat = new Array();
		var lon = new Array();
		
		function initialize() {
		  map = new google.maps.Map(
		    document.getElementById("map"), {
		      center: new google.maps.LatLng(42.963123, -81.364319),
		      zoom: 13,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    });
		  var drawingManager = new google.maps.drawing.DrawingManager({
		    drawingMode: google.maps.drawing.OverlayType.POLYGON,
		    drawingControl: true,
		    drawingControlOptions: {
		      position: google.maps.ControlPosition.TOP_CENTER,
		    },
		    /* not useful on jsfiddle
		    markerOptions: {
		      icon: 'images/car-icon.png'
		    }, */
		    circleOptions: {
		      fillColor: '#ffff00',
		      fillOpacity: 1,
		      strokeWeight: 5,
		      clickable: false,
		      editable: true,
		      zIndex: 1
		    },
		    polygonOptions: {
		      fillColor: '#BCDCF9',
		      fillOpacity: 0.5,
		      strokeWeight: 2,
		      strokeColor: '#57ACF9',
		      clickable: false,
		      editable: false,
		      zIndex: 1
		    }
		  });
		  console.log(drawingManager)
		  drawingManager.setMap(map)
		
		  google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
		    for (var i = 0; i < polygon.getPath().getLength(); i++) {
			  var stringPoints = polygon.getPath().getAt(i).toUrlValue(5);
			  var points = stringPoints.split(",");
			  lat[i] = points[0];
			  lon[i] = points[1];
			  document.getElementById('lat').innerHTML = JSON.stringify(lat);
			  document.getElementById('lon').innerHTML = JSON.stringify(lon);
		    }
			  document.getElementById('lat').innerHTML = JSON.stringify(lat);
			  document.getElementById('lon').innerHTML = JSON.stringify(lon);
		  });	
		}
		google.maps.event.addDomListener(window, "load", initialize);	
		
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
           
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Add Communication Hub!" name="submit"><br>
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