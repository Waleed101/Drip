<?php
 session_start();
 ob_start();
$id = $_SESSION['id'];
$uname = $_SESSION['uname'];
if ($id == 0)
{
  header('Location: login.php?rt=1'); 
}

include('config.php');
$sql = "SELECT * FROM ESInfo";
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
    
    $colorAverage = round($dataAverage/100);
    
    if($colorAverage <= 3)
    	$colorCode = '#FF0000';
    
    else if($colorAverage > 3 && $colorAverage <= 6)
    	$colorCode = '#FFF000';
    
    else if($colorAverage > 6 && $colorAverage <= 8)
    	$colorCode = '#78FF00';
    	
    else
    	$colorCode = '#001FFF';
    	
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
file_put_contents('jsonfun.json', json_encode($geojson, JSON_NUMERIC_CHECK));

$sql = 'SELECT * FROM ESInfo';
$esid = array();
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
	$numOfES++;
	$esid[] = $row['ESID'];
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
                        $sql = "SELECT * FROM alerts WHERE id ='$id' LIMIT 6";
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
                            <a class="text-center" href="alerts.php">
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
                    <h1 class="page-header">Real Time</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">

            </div>
               <div class="col-lg-12">
              	
		<object data="retrieveData.php?q=<?php echo $id;?>" width="100%" height="900"></object>

     </div>
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

   <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>