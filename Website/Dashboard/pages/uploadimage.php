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
      include('config.php');
      $curDate = date('Y-m-d');
   if(isset($_FILES['image'])){
      $esid = $_POST["esid"];
      $des = $_POST['des'];
                    	    
      $sql = "SELECT * FROM ESInfo WHERE ESID = $esid";
      $result = $conn->query($sql);
			
      if ($result->num_rows > 0) {
				    // output data of each row
	while($row = $result->fetch_assoc()) {
		$curPic = $row['numOfPictures']+1;	    
	}}
      
	$fileName =  "user-uploaded/" . $esid . "_" . $curPic . ".jpg";
      $sql = "UPDATE ESInfo SET numOfPictures='$curPic' WHERE ESID='$esid'";
      if ($conn->query($sql) === TRUE) {
  	
	}
     $sql = "INSERT INTO UserImages (ESID, UserID, des, name, date) VALUES ('$esid', '$userid', '$des', '$fileName', '$curDate')";
     
     if ($conn->query($sql) === TRUE) {
	
     }  else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
     $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpg");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152) {
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,$fileName);
         $result1 = '<div class="alert alert-success">The image has been successfully added!</div>';
      }else{
         print_r($errors);
      }
      
      
      
   } 
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
                    <h1 class="page-header">Add Images</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
      		<form action = "" method = "POST" enctype = "multipart/form-data">
      		<?php echo $result1;?>
	      		<div class="col-lg-6">
                    		<select name="esid" class="form-control" id="esid"  onchange="changePictures()">
                    			<option value="0">Select One...</option>
                    			<?php
                    				for($p = 0; $p < count($esid); $p++)
                    					echo '<option value="' . $esid[$p] . '">' . $name[$p] . '</option>';
                    			?>
                    		</select>
	         	</div>

	         	<div class="col-lg-6">
	         		<input type="file" name="image" class="file" class="btn btn-primary btn-lg"></br>
	         	</div>
	         	
	         	<div class="col-lg-12"><textarea name="des" value="" class="form-control">Enter Image Description...</textarea></br></div>
	         	<div class="col-lg-1"></div>
	         	<div class="col-lg-10">
	         		<input type = "submit" value= "Add!" class="btn btn-primary btn-block"/>
			</div>
			
      </form>
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
   </body>
</html>