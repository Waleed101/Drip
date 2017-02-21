<?php 
  session_start();
  ob_start();
  $result1;
  $redriect = $_GET['rt'];
  $redriecturl = 'index.php';
  switch ($redriect){
      case 0:
      $result1;
      break;
      case 1:
      $result1 = '<div class="alert alert-danger">Please enter in your user credentials before continuing!</div>';
      break;
  	}
  include('config.php');
  if (isset($_POST["submit"])) {
		
  	$user = $_POST['user'];
  	$pass = $_POST['password'];
	
	$sql = "SELECT UserID, username, pass, fName FROM UserInfo WHERE username ='$user'";
        $result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $id = $row["UserID"];
      $dusername = $row["username"];
      $dpassword = $row["pass"];
      $dname = $row["fName"];

       if ($pass == $dpassword)
        {
         $_SESSION['id'] = $id;
         $_SESSION['name'] = $dname;
         $_SESSION['uname'] = $dname;
         header('Location:'. $redriecturl); 
          $result1='<div class="alert alert-success">Thank you '.$dname.' for logging in! You will be redriected shortly </div>';
         
         end();
        }
        else
          $result1='<div class="alert alert-danger">Sorry, Incorrect Password!</div>';
        }
      }
      else 
       $result1='<div class="alert alert-danger">Sorry, Incorrect Username!</div>';}?>
<!DOCTYPE html>
<html lang="en">
 
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Amra IT - Employee Panel</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

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

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="login.php">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="user" type="user" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" required>
                                </div>
                               
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" value="Login" class="btn btn-lg btn-success btn-block" name="submit">
                              <br><?php echo $result1;?>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
