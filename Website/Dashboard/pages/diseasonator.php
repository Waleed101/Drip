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
	$i = 0;
	$sql = "SELECT * FROM ESInfo WHERE UserID ='$userid'";
	$result = $conn->query($sql);
	$plants = 0;
	$img = "d";
	if ($result->num_rows > 0) {
			    // output data of each row
		while($row = $result->fetch_assoc()) {
			$esid[$i] = $row['ESID'];
			$name[$i] = $row['name'];
			$numOfPictures[$i] = $row['numOfPictures'];
			$plantID[$i] = $row['PlantID'];
			$i++;			
		}
	}
	
	if($_GET["rdr"] ==  "view")
	{
		echo 'Redriected!!';
		$img = $_GET['imageName'];
		$plants = $_GET['plantID'];
		$sql = "SELECT * FROM PlantDisease WHERE PlantID=$plants";
		$i = 0;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
				    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    
		    	$diseaseID[$i] = $row['DiseaseID'];
		    	$diseaseName[$i] = $row['name'];
		    	$diseaseImage[$i] = $row['img'];
		    	$diseaseDes[$i] = $row['des'];
		    	$nameLength[$i] = $row['leng'];
		    	$i++;
		    }
		}
	}
	
	if($_POST['Submit'] == "Submit")
	{
		$img = $_POST['imageName'];
		$plants = $_POST['plantID'];
		$sql = "SELECT * FROM PlantDisease WHERE PlantID=$plants";
		$i = 0;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
				    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    
		    	$diseaseID[$i] = $row['DiseaseID'];
		    	$diseaseName[$i] = $row['name'];
		    	$diseaseImage[$i] = $row['img'];
		    	$diseaseDes[$i] = $row['des'];
		    	$nameLength[$i] = $row['leng'];
		    	$i++;
		    }
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

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Plant Disease Recomendation</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
               <div>
                    <div class="col-lg-12">
                    	<form method="POST" action="diseasonator.php">
                    		<select name="esid" class="form-control" id="esid"  onchange="changePictures()">
                    			<option value="0">Select One...</option>
                    			<?php
                    				for($p = 0; $p < count($esid); $p++)
                    					echo '<option value="' . $esid[$p] . '">' . $name[$p] . '</option>';
                    			?>
                    		</select>
                    	<script>
                    		var esid = <?php echo json_encode($esid);?>;
                    		var name = <?php echo json_encode($name);?>;
                    		var numOfPictures = <?php echo json_encode($numOfPictures);?>;
                    		var plantID = <?php echo json_encode($plantID);?>;
                    		var userID = <?php echo $userid;?>;
                    		var instance;
                    		//var esid = esidR.map(Number);
                    		//var numOfPictures = numOfPicturesR.map(Number);
                    		
                    		var id = <?php echo json_encode($diseaseID);?>;
				var disName = <?php echo json_encode($diseaseName);?>;
				var img = <?php echo json_encode($diseaseImage);?>;
				var des = <?php echo json_encode($diseaseDes);?>;
				var lengR = <?php echo json_encode($nameLength);?>;
				var leng = lengR.map(Number);
				
				if(id != null)
				{	
					var imgToCheck = <?php echo json_encode($img);?>;
	                    		var plant = <?php echo $plants;?>;	
				}
				
                    		if(plant > 0)
                    		{
					checkImage();
                    		}
                    		
                    		
                    		function changePictures()
                    		{
                    			var esidSelector = document.getElementById('esid');
					for(var i = 0; i < esid.length; i++)
					{
						if(esid[i] == esidSelector.value)
						{
							instance = i;
							i = esid.length;	
						}
					}
					var numberOfPics = numOfPictures[instance];
					document.getElementById("plantID").value = plantID[instance];
					if(numberOfPics == 0)
						document.getElementById("imageDisplay").innerHTML="No images under this Earth Station!";
					else
					{
						document.getElementById("imageDisplay").innerHTML = "";
						for(var n = numberOfPics; n > 0; n--)
						{
							document.getElementById("imageDisplay").innerHTML += '<div class="col-lg-2"><img src="http://precisionfarming.tk/dashboard/employee/pages/user-uploaded/' + esid[instance] + '_' +  n  + '.jpg" width="200" height="200" onclick="changeImageBox(' + n + ')"></div>';
						}
					}
					
                    		}
                    		
                    		function checkImage()
				{
					var file1 = "user-uploaded" + "/" + imgToCheck + ".jpg";
					var file2;
					var maxMatch = 0, highest = 0;
					var ftime = true;
					var p = 0;
					for(i = 0; i != parseInt(id.length); i++)
					{	
						file2 = "plant-diseases/" + img[i];
						var resembleControl;
						resembleControl = resemble(file1).compareTo(file2).onComplete(onComplete);
						function onComplete(data){
							var misMatchPercentage = parseFloat(data.misMatchPercentage);
								
							if(misMatchPercentage < maxMatch || maxMatch == 0)
							{
								maxMatch = misMatchPercentage;
								highest = p;								
								document.getElementById("dbimg").innerHTML = '<img src="http://precisionfarming.tk/dashboard/employee/pages/plant-diseases/cornsmut.jpg"  width="200" height="200">';
							}
							
							$('#mismatch').text("There is a " + maxMatch + "% chance that that is the disease");
							$('#diseaseTitle').text(disName[highest]);
							$('#des').text(des[highest]);
							p++;	
						}
						
					}
				}
				
                    		
                    		function changeImageBox(imgid)
                    		{
                    			var imageNameInput = document.getElementById("imageName");
                    			imageNameInput.value = "" + esid[instance]  + "_" + imgid + "";		
                    		}
                    	</script>
                    	
                    		</br>
                    		<div class="col-lg-6">
                    			<input name="imageName" id="imageName" type="text" class="form-control" required>
                    		</div>
                    		<div class="col-lg-6">
                    			<input name="plantID" id="plantID" type="text" class="form-control" required>
                    		</div>
                    		</br></br></br>
                    		<input type="Submit" value="Submit" name="Submit" class="btn btn-primary btn-lg btn-block"> </br>               		
                    	</form>
			<div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" id="diseaseTitle">Please Search Above</a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">   
                                        	<div class="col-lg-2">
                                        		<img src="http://precisionfarming.tk/dashboard/employee/pages/user-uploaded/<?php echo $img;?>.jpg"  width="200" height="200">
                                        		<b>Your Image</b>
                                        	</div>  
                                        	<div class="col-lg-2">
                                        		<div id="dbimg"></div>
                                        		<b>Database Image</b>
                                        	</div>
                    				<div class="col-lg-8">
                    					<h3>Description</h3>
							<h4 id="des"></h4></br></br></br>
							<div id="mismatch">Please Search</div>
                        			</div>
                                        </div>
                                    </div>
                                </div>
                    	
                    </div>
                    		<div id="imageDisplay"></div> 
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
