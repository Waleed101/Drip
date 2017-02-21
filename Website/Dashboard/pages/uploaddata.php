<?php
	include('config.php');
	$esid  = $_GET["esid"];
	$data[0] = $_GET["data1"];
	$data[1] = $_GET["data2"];
	$data[2] = $_GET["data3"];

	$sql = "SELECT * FROM ESInfo WHERE ESID=$esid";
	
	$result = $conn->query($sql);
	
	
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    		$type[0] = $row["Type_SENS1"];
		$type[1] = $row["Type_SENS2"];
		$type[2] = $row["Type_SENS3"];
		$plantID = $row["PlantID"]; 
		$comid = $row["ComID"];
		$userid = $row["UserID"];
		$lat = $row['lat'];
		$lon = $row['lon'];
		$name = $row['name'];
	}}
	echo $plantID;
	for($i = 0; $i < 3; $i++)
	{
		$timestamp = time();
		$sql = "INSERT INTO ESHis (recType, ESID, PlantID, UserID, TimeStamp, value) VALUES ('$type[$i]', '$esid', '$plantID', '$userid', '$timestamp', '$data[$i]')";
		
		if ($conn->query($sql) === TRUE) {
			echo 'Success!';
		}
		else
			echo 'Failure!';
	}
	$sql = "DELETE FROM ESInfo WHERE ESID=$esid";
	if ($conn->query($sql) === TRUE)
	{
		$val = 0;
		$SoilTemp;
		$phLevel;
		$SoilType;
		$SoilMask;
		$TANXRWB;
		$TAXGOUSDA;
		$sql = "INSERT INTO ESInfo (ESID, ComID, FarmID, UserID, name, Type_SENS1, Data_SENS1, Type_SENS2, Data_SENS2, Type_SENS3, Data_SENS3, lat, lon, LastUpdate, PlantID, SoilTemp, pHValue, SoilType, SoilMask, TANXRWB, TAXGOUSDA) VALUES ('$esid', '$comid', '$comid', '$userid',  '$name', '$type[0]', '$data[0]', '$type[1]', '$data[1]', '$type[2]', '$data[2]', '$lat', '$lon', '$timestamp', '$plantID', '$SoilTemp', '$phLevel', '$SoilType', '$SoilMask', '$TANXRWB', '$TAXGOUSDA')";
		if ($conn->query($sql) === TRUE) {
		   echo 'Success!';
		} else {
		  echo 'Failure';
		} 
	}	
	else
		echo 'Failure';	
/*echo $plantID;	
$sql = "SELECT * FROM PlantInformation WHERE PlantID=$plantID";
	
	$result = $conn->query($sql);
	
	
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    		$maxMoisture = $row['maxMoisture'];
    		$minMoisture = $row['minMoisture'];
	}}
echo $minMoisture;
if($maxMoisture < $data[0])
{
	echo 'Max';
	createAlert("Moisture Value is to high on Earth Station $name", 'sun-o');
}

else if($minMoisture > $data[0])
{
	createAlert("Moisture Value is low on Earth Station $name", 'sun-o');
	echo 'Min';
}

function createAlert($notification, $icon)
{
		$sql = "INSERT INTO alerts (id, alertType, alert) VALUES ('$userid', '$icon', '$notification')";
		if ($conn->query($sql) === TRUE) {
		   echo 'Success!';
		} else {
		  echo 'Failure';
		}
	
}*/	
	
?>