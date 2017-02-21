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
	
	// Create connection
	
?>
<!--
*/-->
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
<?php
	//error_reporting(0);
			$plantid= $_GET['q'];
			$sql = "SELECT * FROM PlantInformation WHERE PlantID ='$plantid'";
 	
		        $result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
		
		
			     $latinName = $row["LatinName"];
			     $commonName = $row["CommonName"];
			     $family = $row['Family'];
			     $habitats = $row['Habitats'];
			     $height = $row['Height'];
			     $soilType = $row['TypeofSoil'];
			     $ph = $row['SoilpHLevel'];
			     $mstrval = $row['MoistureValue'];
			     $temp = $row['Temperature'];
			     $sun = $row['Sunlight'];
	             }
 		     $sql = "SELECT * FROM Plant_Compo WHERE PlantID ='$plantid'";
 	
		   $result = $conn->query($sql);
		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
				     $water = $row['water'];
				     $calories = $row['calories'];
				     $protein = $row['protein'];
				     $fat = $row['fat'];
				     $carb = $row['carb'];
				     $fibre = $row['fibre'];
				     $ash = $row['ash'];
				     $calc = $row['calc'];
				     $phos = $row['phos'];
				     $iron = $row['iron'];
				     $mag = $row['mag'];
				     $sodium = $row['sodium'];
				     $sum = $water+$calories+$protein+$fat+$carb+$fibre+$ash+$calc+$phos+$iron+$mag+$sodium;
		     }
		    }
		    
 		     $sql = "SELECT * FROM PlantID WHERE PlantID ='$plantid'";
 	
		     $result= $conn->query($sql);
		     if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
				     $des = $row['des'];
				     $img = $row['img'];
		     }
		    }
		    
		     
	        }
	    ?>
<?php 

	switch($ph)
	{
		case 1:
		$type = "acid";
		break;
		
		case 2:
		$type = "neutral";
		break;
		
		case 3:
		$type = "basic";
		break;
		
		case 4:
		$type = "acid, neutral, and basic";
		break;
	}

?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Plant Information - <?php echo $commonName;?> </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
               <div>
     		<div class="col-lg-3">
     			<img src="http://www.pfaf.org/Admin/PlantImages/<?php echo $img;?>">
     			<center><font size="2"><p><i>Credits to PFAF</i></p></font></center>
     			<h5><b>Latin Name: </b><?php echo $latinName;?></h5>
     			<h5><b>Common Name: </b><?php echo $commonName;?></h5>
     		</div>
     		<div class="col-lg-9">
     			<h3>Basic Plant Information</h3>
     			<p><b>Description: </b><?php echo $des;?></p>
     			<p><b>Family: </b><?php echo $family;?></p>
     			<p><b>Habitat: </b><?php echo $habitats;?></p>
     			<p><b>Average Height (m): </b><?php echo $height;?> soils</p>
     			
     		 	<h3>Advanced Plant Information</h3>
     			<p><b>Soil PH Level(s): </b><?php echo $type;?> soils</p>
     			<p><b>Good Temperature: </b><?php echo $temp;?>&deg;C</p>
     			<p><b>Good Soil Moisture: </b><?php echo $mstrval;?></p>
     			<p><b>Sun Exposure: </b><?php if($sun == 'N'){echo 'Low';}else{echo 'High';}?></p>
     		</div>
     		
                <div class="col-lg-12">
     			<h3>Plant Composition</h3>
                <div class="col-lg-6">
                        <!-- /.panel-heading -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>1</td><td>Water</td><td><?php echo round($water/$sum*100);?></td></tr>
                                        <tr><td>2</td><td>Calories</td><td><?php echo round($calories/$sum*100);?></td></tr>
                                        <tr><td>3</td><td>Protein</td><td><?php echo round($protein/$sum*100);?></td></tr>
                                        <tr><td>4</td><td>Fat</td><td><?php echo round($fat/$sum*100);?></td></tr>
                                        <tr><td>5</td><td>Carbohydrates</td><td><?php echo round($carb/$sum*100);?></td></tr>
                                        <tr><td>6</td><td>Fibre</td><td><?php echo round($fibre/$sum*100);?></td></tr>
                                    </tbody>
                                </table>
                            </div></div>
                            
                <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>7</td><td>Ash</td><td><?php echo round($ash/$sum*100);?></td></tr>
                                        <tr><td>8</td><td>Calcium</td><td><?php echo round($calc/$sum*100);?></td></tr>
                                        <tr><td>9</td><td>Phosphorus</td><td><?php echo round($phos/$sum*100);?></td></tr>
                                        <tr><td>10</td><td>Iron</td><td><?php echo round($iron/$sum*100);?></td></tr>
                                        <tr><td>11</td><td>Magnesium</td><td><?php echo round($mag/$sum*100);?></td></tr>
                                        <tr><td>12</td><td>Sodium</td><td><?php echo round($sodium/$sum*100);?></td></tr>
                                    </tbody>
                                </table>
                            </div></div>
                    <!-- /.panel -->
                </div> 
                <h3>Earth Stations Monitoring this Plant</h3>
                  <div class="dataTable_wrapper">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>ID</th>
                                            <th>ComHub ID</th>
                                            <th>Subfarm ID</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Last Update</th>
                                        </tr>
                                    </thead>
                                    <tbody> <?php
	
	$sql = "SELECT * FROM ESInfo WHERE UserID ='$id' AND PlantID ='$plantid'";
			
        $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["ESID"] . "</td>";
    echo "<td>" . $row["ComID"] . "</td>";
    echo "<td>" . $row["FarmID"] . "</td>";
    echo "<td>" . $row["lat"] . "</td>";
    echo "<td>" . $row["lon"] . "</td>";
    echo "<td>" . $row["LastUpdate"] . "</td>";
    echo "</tr>";

    }
} 
 ?>
                                           
                                            
                                        </tbody></table>
                    </div><!-- /.panel -->
                               
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
