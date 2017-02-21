<?php
 session_start();
 ob_start();
$userid = $_SESSION['id'];
$uname = $_SESSION['uname'];
if ($userid == 0)
{
  header('Location: login.php?rt=1'); 
}
  include('config.php');
	
	// Create connection
	
?>
<?php
$sql = "SELECT * FROM ESInfo WHERE UserID=$userid";
$result = $conn->query($sql);
$numOfES = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $numOfES++;
      $lat = $row['lat'];
      $lon = $row['lon'];
      }}

$sql = "SELECT * FROM FarmInfo WHERE UserID=$userid";
$result = $conn->query($sql);
$numOfCom = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $numOfCom++;
      }}
      

$sql = "SELECT * FROM UserImages WHERE UserID=$userid";
$result = $conn->query($sql);
$numOfImg = 0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $numOfImg++;
}}

$sql = "SELECT * FROM alerts WHERE id ='$userid' LIMIT 6";
$i = 0;
$result = $conn->query($sql);
	if ($result->num_rows > 0) {
			    // output data of each row
		    while($row = $result->fetch_assoc()) {
			$icon[$i] = $row['alertType'];
			$text[$i] = $row['alert'];
			$date[$i] = $row['time'];
			$i++;
	}}
	
  $curDate = date('Y-m-d');
  $addAlert = true;
  $addAlertCloud = true;
  $addAlertSun = true;
  $j = 0;
  $sql = "SELECT * FROM alerts WHERE id='$userid' AND time='$curDate' AND alertType='cloud'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
	    // output data of each row
  	while($row = $result->fetch_assoc()) {
  		$alertType[$j] = $row['alertType'];
  		$alertDate[$j] = $row['date'];
  		$j++;
  		$addAlertCloud = false;
  	}
  }
  
  $sql = "SELECT * FROM alerts WHERE id='$userid' AND time='$curDate' AND alertType='sun-o'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
	    // output data of each row
  	while($row = $result->fetch_assoc()) {
  		$alertType[$j] = $row['alertType'];
  		$alertDate[$j] = $row['date'];
  		$j++;
  		$addAlertSun = false;
  	}
  }
  
  if(!$addAlertSun && !$addAlertCloud)
  	$addAlert = false;
   
  if($addAlert)
  {
	  $n = 0;
	  $sql = "SELECT * FROM FarmInfo WHERE UserID ='$userid'";
				
	  $result = $conn->query($sql);
	
	  if ($result->num_rows > 0) {
		    // output data of each row
	  	while($row = $result->fetch_assoc()) {
	  		$FarmID[$n] = $row['FarmID'];
	  		$FarmName[$n] = $row['FName'];
			  		
			$sql1 = "SELECT * FROM ESInfo WHERE FarmID ='$FarmID[$n]'";
						
			$result1 = $conn->query($sql1);
			
			if ($result1->num_rows > 0) {
				    // output data of each row
				while($row1 = $result1->fetch_assoc()) {
					$lat[$n] = $row1['lat'];
					$lon[$n] = $row1['lon'];
				}
			}
			$n++;	
	  	}
	  }
	  
	  $sum = 300;  
	  /*$json_string = file_get_contents("http://api.wunderground.com/api/b4cd263c8d359c83/forecast/q/" . $lat[0] . ", " . $lon[0] . ".json");
	  $parsed_json = json_decode($json_string);
	  $location = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'};
	  for($i = 0; $i < 7; $i+=2)
	  {
	  	echo $i;
	  	$sum = $sum + intval($location[$i]->{'pop'});
	  }*/
	  $avg = $sum/4;
	  
	  if($avg > 50 && $addAlertCloud)
	  {
	  	$chance = $avg . '% POP at ' . $FarmName[0];
	  	$long = 'There will be no need to water ' . $FarmName[0] . ' over the next several days, as there is a ' . $avg . '% Chance of downpour!';
	  	$sql = "INSERT INTO alerts(id, alertType, alert, longAlert, time) VALUES ('$userid', 'cloud', '$chance', '$long', '$curDate')";
		if ($conn->query($sql) === TRUE) {}	
	  }
	  else if($avg < 50 && $addAlertSun)
	  {
	  	$chance = $avg . '% POP at ' . $FarmName[0];
	  	$long = 'There will be great need to water ' . $FarmName[0] . ' over the next several days, as there is a low chance of rain!';
	  	$sql = "INSERT INTO alerts(id, alertType, alert, longAlert, time) VALUES ('$userid', 'sun-o', '$chance', '$long', '$curDate')";
		if ($conn->query($sql) === TRUE) {}	
	  }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>

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
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

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

<script src = "resemble.js"></script>

</head>



<body>
<div id="wrapper">

       <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Managment Panel</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                        <?php
                        $sql = "SELECT * FROM alerts WHERE id ='$userid' LIMIT 6";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
			       
                        
                            echo "<a href='alerts.php?q=". $row["alertID"]."'>
                                <div>
                                    <i class='fa fa-". $row["alertType"]." fa-fw'></i> ". $row["alert"]."
                                    <span class='pull-right text-muted small'>". $row["time"]."</span>
                                </div>
                            </a>"; 
                            }
			} 
                            ?>
                        </li>
                        
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="alerts.php?">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
<?php 
$TopId = 1;
$sql = "SELECT * FROM TopBar WHERE id ='$TopId'";
        $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $barCode = $row["barCode"];
      }}
      echo $barCode ;
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="alert alert-info">Welcome, <?php echo $uname;?>, to Drip Dashboard 2.0!</div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-desktop fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $numOfCom;?></div>
                                    <div>Communication Hubs</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewCom.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-laptop fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $numOfES;?></div>
                                    <div>Earth Stations</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewES.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-camera fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $numOfImg;?></div>
                                    <div>Photos/Notes</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewImages.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-8">
                <object data="http://precisionfarming.tk/SQL/weatherapp.php?lat=<?php echo $lat;?>&lon=<?php echo $lon;?>" width="100%" height="410"></object></br>
            
                    
                    <!-- /.panel -->
              
                   </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> Notifications Panel
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="list-group">
                            <?php 
                            if($i == 0)
                            {
                            	echo '<p>Nothing to see here!</p>';
                            }
                            for($n = 0; $n != $i; $n++)
                            {
                               echo '<a href="alerts.php" class="list-group-item">';
                               echo '<i class="fa fa-' . $icon[$n] . ' fa-fw"></i> ' . $text[$n];
                               echo '<span class="pull-right text-muted small"><em>' . $date[$n] . '</em>';
                               echo '</span>';
                               echo '</a>';
                            } 
                            ?>  
                            </div>
                            <!-- /.list-group -->
                            <a href="alerts.php" class="btn btn-default btn-block">View All Alerts</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
       
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
