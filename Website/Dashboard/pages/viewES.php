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
                    <h1 class="page-header">View | Earth Station</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
               <div>
                    <div class="col-lg-12">
                    <?php 
	                    $esid = $_GET["q"];
			    if($esid != 0)
			    {
			    	$sql = "DELETE FROM ESInfo WHERE ESID= '$esid'";

				if ($conn->query($sql) === TRUE) {
				    echo '<div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Successfully Deleted Earth Station ID# '. $esid .'.</div>';
				} else {
				    echo '<div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Error Deleting Earth Station ID# '. $esid .'. The support team has been notified!</div>';
				}
			    }	                    
                    
                    ?>
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
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody> <?php
	
	$sql = "SELECT * FROM ESInfo WHERE UserID ='$id'";
			
        $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    $comid = $row["ComID"];
    $sql1 = "SELECT * FROM FarmInfo WHERE FarmID =$comid";
					
    $result1 = $conn->query($sql1);
						
    if ($result1->num_rows > 0) {
	// output data of each row
	while($row1 = $result1->fetch_assoc()) {
	  		$comName = $row1['FName'];
		}}
    echo "<tr>";
    echo "<td>" . $row["name"] . "</td>";
    echo "<td>" . $row["ESID"] . "</td>";
    echo "<td>" . $comName . "</td>";
    echo "<td>" . $comName . "</td>";
    echo "<td>" . $row["lat"] . "</td>";
    echo "<td>" . $row["lon"] . "</td>";
    echo "<td>" . $row["LastUpdate"] . "</td>";
    echo "<td><i class='fa fa-fw fa-smile-o'></i></td>"; 
    echo "<td><a href='editES.php?q=". $row["ESID"] ."'><i class = 'fa fa-pencil fa-fw'></i></a><a data-toggle='modal' data-target='#".$row['ESID']."'><i class = 'fa fa-trash fa-fw'></i></a></td>";
    echo "</tr>";
    echo '<div class="modal fade" id="' . $row['ESID'] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    echo '<h4 class="modal-title" id="myModalLabel">Delete Earth Station "'. $row["name"] .'"</h4>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo 'Are you sure you would like to delete Earth Station ID #' . $row["ESID"] . '? Deleting it will result in anybody being able to claim it! You will still be able to see historical data from this Earth Station, but you will not be able to search specifically for it.';
    echo '</div>';
    echo '<div class="modal-footer">';
    echo '<a href="viewES.php?q='. $row['ESID'] .'"><button type="button" class="btn btn-danger" >Delete Earth Station</button></a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    }
} 
 ?>
                                           
                                            
                                        </tbody></table>
                    </div><!-- /.panel -->
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
