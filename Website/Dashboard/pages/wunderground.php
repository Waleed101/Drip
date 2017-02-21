<?php
  include ('config.php');
  $id = 12345;
  $curDate = date('Y-m-d');
  echo time();
  $addAlert = true;
  $addAlertCloud = true;
  $addAlertSun = true;
  $j = 0;
  $sql = "SELECT * FROM alerts WHERE id='$id' AND date='$curDate' AND alertType='cloud'";
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
  
  $sql = "SELECT * FROM alerts WHERE id='$id' AND date='$curDate' AND alertType='sun-o'";
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
  
  if($addAlertSun && $addAlertCloud)
  	$addAlert = false;
   
  if($addAlert)
  {
	  $n = 0;
	  $sql = "SELECT * FROM FarmInfo WHERE UserID ='$id'";
				
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
	  echo 'Average = ' . $avg;
	  
	  if($avg > 50 && $addAlertCloud)
	  {
	  	$chance = $avg . '% POP at ' . $FarmName[0];
	  	$long = 'There will be no need to water ' . $FarmName[0] . ' over the next several days, as there is a ' . $avg . '% Chance of downpour!';
	  	$sql = "INSERT INTO alerts(id, alertType, alert, longAlert, date) VALUES ('$id', 'cloud', '$chance', '$long', '$curDate')";
		if ($conn->query($sql) === TRUE) {}	
	  }
	  else if($avg < 50 && $addAlertSun)
	  {
	  	$chance = $avg . '% POP at ' . $FarmName[0];
	  	$long = 'There will be great need to water ' . $FarmName[0] . ' over the next several days, as there is a low chance of rain!';
	  	$sql = "INSERT INTO alerts(id, alertType, alert, longAlert, date) VALUES ('$id', 'sun-o', '$chance', '$long', '$curDate')";
		if ($conn->query($sql) === TRUE) {}	
	  }
   }
?>