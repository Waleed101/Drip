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
                    <h1 class="page-header">Historical Data | Results</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
               <div>
                    <div class="col-lg-12">
                    
                       <div class="dataTable_wrapper">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Earth Station ID</th>
                                            <th>Sensor Type</th>
                                            <th>Date</th>
                                            <th>Plant Type</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    	$date = $_GET['date'];                    	
                    	$es = $_GET['es'];
                    	$rec = $_GET['rec'];
                    	$plant = $_GET['plant'];
                    	$timestamp = strtotime($date);
                    	
                    	
                    	if($date != 0)
                    	{
                    		
                    			if($es != 0)
	                    			{
	                    			 if($rec != 0)
	                    				{
	                    					if($plant != 0)
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND ESID='$es' AND recType='$rec' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
	                    					 //all
	                    					}
	                    					
	                    					else
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND ESID='$es' AND recType='$rec' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but   plant	
	                    					}
	                    				}
	                    			else 
	                    				{
	                    					if($plant != 0)
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND ESID='$es' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  rec 
	                    					}
	                    					
	                    					else
	                    					{
	                    					
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND ESID='$es' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  rec and plant	
	                    					}
	                    					
	                    				}
	                    			
	                    			}
	                    			
	                    			 if($rec != 0)
		                    			{
		                    			
		                    				if($plant != 0)
		                    				{
		                    				
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND recType='$rec' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  es 
		                    				}
		                    					
		                    				else
		                    				{
		                    				
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND recType='$rec' ";$sqlBool = true;
		                    				 //all but  es and plant
		                    				}
		                    			}
		                    			
		                    		 else
		                    			{
		                    				if($plant != 0)
		                    				{
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  es, and rec
		                    				}
		                    					
		                    				else
		                    				{
	                    					 $sql = "SELECT * FROM ESHis WHERE TimeStamp >='$timestamp' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  es, rec, and plant		
		                    				}
		                    			}
	                    				
	                    			}
	                    	
	                    	else
	                    	{
	                    	
	                    		if($es != 0)
	                    			{
	                    			 if($rec != 0)
	                    				{
	                    					if($plant != 0)
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE ESID='$es' AND recType='$rec' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  time	
	                    					}
	                    					
	                    					else
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE ESID='$es' AND recType='$rec' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  time and plant	
	                    					}
	                    				}
	                    			else 
	                    				{
	                    					if($plant != 0)
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE ESID='$es' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  time and rec 
	                    					}
	                    					
	                    					else
	                    					{
	                    					 $sql = "SELECT * FROM ESHis WHERE ESID='$es' AND UserID='$userid'";$sqlBool = true;
	                    					 //all but  time, rec and plant	
	                    					}
	                    					
	                    				}
	                    			
	                    			}
	                    			
	                    		else
	                    		        {
	                    			 if($rec != 0)
		                    			{
		                    			
		                    				if($plant != 0)
		                    				{
	                    					 $sql = "SELECT * FROM ESHis WHERE recType='$rec' AND PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  time and es 
		                    				}
		                    					
		                    				else
		                    				{
	                    					 $sql = "SELECT * FROM ESHis WHERE recType='$rec' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  time, es and plant
		                    				}
		                    			}
		                    			
		                    		 else
		                    			{
		                    				if($plant != 0)
		                    				{
	                    					 $sql = "SELECT * FROM ESHis WHERE PlantID='$plant' AND UserID='$userid'";$sqlBool = true;
		                    				 //all but  time, es, and rec
		                    				}
		                    					
		                    				else
		                    				{
	                    					 $sqlBool = false;
	                    					 //all but  time, es, rec, and plant		
		                    				}
		                    			}
	                    				
	                    			}
	                    		}
                    	if($sqlBool)
                    	{
                    		if($timestamp != 0)
                    		{
                    			$orderBy = "ORDER BY TimeStamp";
                    			$sql = $sql . $orderBy;
                    		}
				$result = $conn->query($sql);
				$i = 0;
				if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) {
				    	       $i++;
					       $recType = $row['recType'];
					       $esid = $row['ESID'];
					       $plantID = $row['PlantID'];
					       $timestamp = $row['TimeStamp'];
					       $value = $row['value'];
					       switch($recType)
					       {
					       	case 1:
					       	$sensor = "Moisture";
					       	break;
					       }
					       
					       $sqlP = "SELECT * FROM PlantID WHERE PlantID ='$plantID'";
					       $resultP = $conn->query($sqlP);
					       while($row = $resultP->fetch_assoc()) {
					       	$plant = $row['CommonName'];
					       }
					       
					       $sqlE = "SELECT * FROM ESInfo WHERE ESID ='$esid'";
					       $resultE = $conn->query($sqlE);
					       while($row = $resultE->fetch_assoc()) {
					       	$esname = $row['name'];
					       }
					       
					        echo "<tr>";
					        echo "<td>" . $esid . "</td>";
					        echo "<td>" . $sensor . "</td>";
						echo "<td>" . date('m/d/Y' , $timestamp) . "</td>";
						echo "<td>" . $plant . "</td>";
						if($sensor == 'Moisture')
							echo "<td>" . round($value/1024*100) . "%</td>";
						else
							echo "<td>" . $value . "</td>"; 
						echo "</tr>";
					       
				      }}
	                    		
                    	}
                    	if($sensor !=0)
                    	{
		               			        echo '<div class="alert alert-info col-lg-2">Sensor: ' . $sensor .'</div>';
		               			        echo '<div class="col-lg-1"></div>';
		        } 
		         
		        if($plant != 0)
		        { 
		               			        echo '<div class="alert alert-info col-lg-2">Plant: ' . $plant .'</div>';
		               			        echo '<div class="col-lg-1"></div>';
		        }
		        
		        if($esname != 0)
		        {
		               			        echo '<div class="alert alert-info col-lg-2">Station: ' . $esname .'</div>';
		               			        echo '<div class="col-lg-1"></div>';
		        }
		        
		        if($date != 0)
		        {
		               			        echo '<div class="alert alert-info col-lg-2">Date: ' . $date .'</div>';
		               			        echo '<div class="col-lg-1"></div>';
	                }
	                echo '</br></br></br></br>';
	                echo '<b>Number of Results: </b>' . $i . '</br></br>';
	                    	
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
