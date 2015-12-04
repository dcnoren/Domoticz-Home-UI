<?php
require('functions.php');

$timestamp = timestamp();

$idx = $_GET["idx"];
$action = $_GET["action"];
$internalAction = "";
$deviceName = "";
$motionID = "27";
$nestAway = "16";

if ($idx == 19){
	$deviceName = "PIR Family Room";
	$internalAction = "motionSensor";
} elseif ($idx == 24){
	$deviceName = "PIR Kitchen";
	$internalAction = "motionSensor";
} elseif ($idx == 27){
	$deviceName = "Motion Presence";
	$internalAction = "presence";
} elseif ($idx == 28){
	$deviceName = "Front Door";
	$internalAction = "door";
} elseif ($idx == 29){
	$deviceName = "Garage Door";
	$internalAction = "door";
} elseif ($idx == 30){
	$deviceName = "Back Door";
	$internalAction = "door";
}

if ($internalAction == "motionSensor" && $action == "on"){
	setStatus($motionID,"On");
	$statusResulted = retAllStatus();
	foreach ($statusResulted["result"] as $i2=>$v2){
		$statusIdx = $v2["idx"];
		if ($statusIdx == $nestAway){
			$motionStatus = $v2["Status"];
		}
	}
	
	if ($motionStatus == "On"){
		setStatus($nestAway,"Off");
		$message = '{"Timestamp":"' . $timestamp . '","Host":"phpConnector","Event":"Nest Away Off","Sensor":' . $nestAway . ',"Sensor_Name":"Nest"}';
		logThis($message);
	}
	
	$message = '{"Timestamp":"' . $timestamp . '","Host":"phpConnector","Event":"Motion Detected","Sensor":' . $idx . ',"Sensor_Name":"' . $deviceName . '"}';
	logThis($message);
	
}

if ($internalAction == "door" && $action == "open"){
	//Front Door
	if ($idx == 28){

		$statusResulted = retAllStatus();
		foreach ($statusResulted["result"] as $i2=>$v2){
			$statusIdx = $v2["idx"];
			if ($statusIdx == "27"){
				$motionStatus = $v2["Status"];
			}
		}
		
		if ($motionStatus == "Off"){
			setDimmerStatus(9,"On");
		} elseif ($motionStatus == "On"){
			$timeisit = dayTime();
			if ($timeisit != "day"){
				setDimmerStatus(10,"On");
			}
		}
	
	//Garage Door
	} elseif ($idx == 29){
		
		$statusResulted = retAllStatus();
		foreach ($statusResulted["result"] as $i2=>$v2){
			$statusIdx = $v2["idx"];
			if ($statusIdx == "27"){
				$motionStatus = $v2["Status"];
			}
		}
		if ($motionStatus == "Off"){
			setDimmerStatus(7,"On");
		}
	}
}

if ($internalAction == "presence"){
	if ($action == "on"){
		$message = '{"Timestamp":"' . $timestamp . '","Host":"phpConnector","Event":"Presence Started","Sensor":' . $idx . ',"Sensor_Name":"' . $deviceName . '"}';
		logThis($message);
	} elseif ($action == "off"){
		$message = '{"Timestamp":"' . $timestamp . '","Host":"phpConnector","Event":"Presence Stopped","Sensor":' . $idx . ',"Sensor_Name":"' . $deviceName . '"}';
		logThis($message);
	}
}

?>