<?php
	include('config.php');
	$id = $_GET["q"];
	$farmid = $_GET["comid"];
	$ftime = true;
	if($farmid == 0)
	{
		$sql = "SELECT * FROM FarmInfo WHERE UserID=$id";
		$i = 0;
		$result = $conn->query($sql);
		  if ($result->num_rows > 0) {
			 while($row = $result->fetch_assoc()) {
				$farmid = $row['FarmID'];
			}
		  }	
	}
	$sql = "SELECT * FROM ESInfo WHERE ComID=$farmid";
	$i = 0;
	$result = $conn->query($sql);
	  if ($result->num_rows > 0) {
		 while($row = $result->fetch_assoc()) {
			$name[$i] = $row['name'];
			$esid[$i] = $row['ESID'];
			$lat[$i] = $row['lat'];
			$lon[$i] = $row['lon'];
		  	$plusLat[$i] = $row['lon'] + 0.0005;
		    	$minusLat[$i] = $row['lon'] - 0.0005;
		    	$plusLon[$i] = $row['lat'] + 0.0005;
		    	$minusLon[$i] = $row['lat'] - 0.0005;
		    	$i++;
		}
	  }
 	 else
  		$response = '<div class="alert alert-danger">There was an error adding this Earth Station. The support team has been notified!</div>';

	for($j = 0; $j < $i; $j++)
	{
		$p = 0;
		$sql = "SELECT * FROM ESHis WHERE ESID=$esid[$j]";
		$result = $conn->query($sql);
		  if ($result->num_rows > 0) {
			 while($row = $result->fetch_assoc()) {
			 	 if($ftime)
			 	 	$timestamp[$p] = $row['TimeStamp'];
			        $hexF1 = '#00' . dechex(round($row['value']/4.01568627451));
				$value[$j][$p] = $hexF1 . '00';
				$p++;
			}
		  }
	}
?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script><style>
      #map {
        height: 90%;
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
      .player{
	margin: 50px 0;
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

</head>

<body>      
    <script>
    var timestamp = <?php echo json_encode($timestamp);?>;
    var esid = <?php echo json_encode($esid);?>;
    var value = <?php echo json_encode($value);?>;
    var latR = <?php echo json_encode($lat);?>;
    var lonR = <?php echo json_encode($lon);?>;
    var plusLatR = <?php echo json_encode($plusLat);?>;
    var minusLatR = <?php echo json_encode($minusLat);?>; 
    var plusLonR = <?php echo json_encode($plusLon);?>;
    var minusLonR = <?php echo  json_encode($minusLon);?>; 
    var lat = latR.map(Number);
    var lon = lonR.map(Number);
    var plusLat = plusLatR.map(Number);
    var minusLat = minusLatR.map(Number);
    var plusLon = plusLonR.map(Number);
    var minusLon = minusLonR.map(Number);
    var map;
    var intervals = 2000;
    var curArray = 0;
    var curArrayPause = 0;
    var timeintervals = new Array();
    var ftime = true;
    var rectangle;
    timeintervals["4000"]=4000;
    timeintervals["3000"]=3000;
    timeintervals["2000"]=2000;
    timeintervals["1000"]=1000;
    timeintervals["100"]=100;
    timeintervals["50"]=50;
    var state = 'stop';
    
    
    if(timestamp === null)
    {
    	lat = 0;
    	lon = 0;
    	plusLat = 0.0005;
    	plusLon = 0.0005;
    	minusLat = -0.0005;
    	minusLon = -0.0005;
    	alert(lat);
    }
    
     
    
         function initMap() 
         {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: {lat: lat[0], lng: lon[0]}, 
                mapTypeId: 'hybrid'
            });
         }
            
         function changeTime()
	 {  
	    var theForm = document.forms["timeForm"];
	    var selectedInterval = theForm.elements["time"];
	    for(var i = 0; i < selectedInterval.length; i++)
	    {
	        if(selectedInterval[i].checked)
	        {
	            intervals = timeintervals[selectedInterval[i].value];
	            break;
	        }
	    }
	 } 
        
        
         function pause(){
         	curArrayPause = curArray;
         	curArray = timestamp.length;
         	ftime = true;
         	state = 'pause';
         	changeButtonPlay();
         } 
         
      
         function addToCounter(){
         	if(!(curArray+5 >= value.length))
         		curArray+=5;
         	else
         		curArray+=value.length-curArray;
         } 
         
         function minusToCounter(){
         	if(!(curArray-5 < 0))
         		curArray-=5;
         	else
         		curArray = 0;
         }
          
         function stop(){
            curArray = timestamp.length;
            state = 'stop';
    	    var button = d3.select("#button_play").classed('btn-success', false);
    	    button.select("i").attr('class', "fa fa-play");
         }
         
         function changeButtonPlay()
         {
	          if(state=='stop'){
		      state='play';
		      var button = d3.select("#button_play").classed('btn-success', true); 
		      button.select("i").attr('class', "fa fa-pause");  
		  }
		  else if(state=='play' || state=='resume'){
		      state = 'pause';
		      d3.select("#button_play i").attr('class', "fa fa-play"); 
		  }
		  else if(state=='pause'){
		      state = 'resume';
		      d3.select("#button_play i").attr('class', "fa fa-pause");        
		  }
         }
         
      /*   function clearRectangles()
         {
           for(i = 0; i < lat.length; i++)
           {
           	rectangle[i].setMap(null);
           }	
         }*/
         function createNotification(alert)
         {
         	if (!("Notification" in window)) {
		    alert("This browser does not support desktop notification");
		  }
		
		  // Lets check whether notification permissions have already been granted
		  else if (Notification.permission === "granted") {
		    // If it's okay let's create a notification
		     	   var options = {
			      body: alert,
			      icon: 'http://precisionfarming.tk/dashboard/employee/pages/favicon.jpg'
			     }
			  var n = new Notification('Drip - Your Farming Tool',options);
		  }
		
		  // Otherwise, we need to ask the user for permission
		  else if (Notification.permission !== 'denied') {
		    Notification.requestPermission(function (permission) {
		      // If the user accepts, lets create a notification
		      if (permission === "granted") {
			     var options = {
			      body: alert,
			      icon: 'http://precisionfarming.tk/dashboard/employee/pages/favicon.jpg'
			     }
			  var n = new Notification('Drip - Your Farming Tool',options);
		      }
		    });
		  }
         }
                  
         function timelapse(){
            var numOfES = lat.length; 
            rectangle = new Array(numOfES);
            if(ftime == true)
            {
            	changeButtonPlay();
            	curArray = curArrayPause;
            	ftime = false;
            }
            for(i = 0; i < numOfES; i++)
            {
	            rectangle[i] = new google.maps.Rectangle({
	              strokeColor: '#808080',
	              strokeOpacity: 0.8,
	              strokeWeight: 2,
	              fillColor: '#808080',
	              fillOpacity: 0.8,
	              map: map,
	              bounds: {
	                north: plusLon[i],
	                south: minusLon[i],
	                east: plusLat[i],
	                west: minusLat[i]
	              }
	            });
	    }
            var m_timer = window.setInterval(function(){
              curArray++;
              if (curArray <= value.length) {
                var complete = curArray/value.length;
		document.getElementById("progress").style.width = complete*1000;
		for(i = 0; i < numOfES; i++)
		{
	                rectangle[i].setOptions({
	                  fillColor: value[i][curArray]
	                });
                }
              }  
              if (curArray === value.length) {
                window.clearInterval(m_timer);
                state = 'stop';
               // clearRectangles();
              }
            }, intervals);
        }
    </script>
    <form method="GET" action="timelapse.php">
    	    <input type="hidden" name="q" value="<?php echo $id;?>">
	    <div class="col-lg-10">
	    </br>
                   <select class="form-control" name="comid">
                   <?php
                     $id = $_GET['q'];
	             $sql = "SELECT * FROM FarmInfo WHERE UserID =$id";
					
		     $result = $conn->query($sql);
						
		     if ($result->num_rows > 0) {
						    // output data of each row
			while($row = $result->fetch_assoc()) {
						     	
				echo '<option value="'. $row['FarmID'] .'">'. $row['FName'] .'</option>';
				}
		     }
                                               
                                               ?>
                </select>	    
	    </div>
	    <div class="col-lg-2">
	    </br>
		<input type="submit" value="Search" class="btn btn-primary btn-lg btn-block">
	    </div>
    </br>
    <div id="map"></div><br>
    <div class="col-lg-4">
<div class="progress progress-striped active"><div class="progress-bar progress-bar-info progress progress-striped active" id="progress" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax=""></div></div></div>
 <button type="button" id="button_fbw" class="btn" onclick='minusToCounter()'>
      <i class="fa fa-fast-backward"></i>
    </button>

    <button type="button" id="button_play" class="btn" onclick='timelapse()'>
      <i class="fa fa-play"></i>
    </button>

    <button type="button" id="button_stop" class="btn" onclick='stop()'>
      <i class="fa fa-stop"></i>
    </button>

    <button type="button" id="button_ffw" class="btn" onclick='addToCounter()'>
      <i class="fa fa-fast-forward"></i>
    </button>
   
    <form action="" id="timeForm" onsubmit="return false;">
    <label class="radio-inline">
        <input type="radio" name="time" value="50" onclick="changeTime()" checked>50
    </label>
    <label class="radio-inline">
        <input type="radio" name="time" value="100" onclick="changeTime()" checked>100
    </label>
    <label class="radio-inline">
        <input type="radio" name="time" value="1000" onclick="changeTime()" checked>1000
    </label>
    <label class="radio-inline">
    	<input type="radio" name="time" value="2000" onclick="changeTime()">2000
    </label>
    <label class="radio-inline">
        <input type="radio" name="time" value="3000" onclick="changeTime()" checked>3000
    </label>
    <label class="radio-inline">
        <input type="radio" name="time" value="4000" onclick="changeTime()" checked>4000
    </label>
   </form>
    
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