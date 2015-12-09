<?php
//Pull in configurations
require('config.php');


function timestamp(){
	$epoch = time();
	$timestamp = date("c", $epoch);
	return $timestamp;
}

function dayTime(){
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=getSunRiseSet");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$statusResult = json_decode(curl_exec($curl), true);
	curl_close($curl);
	
	$testTime = explode(" ",$statusResult["ServerTime"]);
	$testTime = $testTime[1];
	$testTime = explode(":",$testTime);
	$testTime = $testTime[0] . ":" . $testTime[1];
	$currentTime = strtotime($testTime);
	$sunrise = strtotime($statusResult["Sunrise"]);
	$sunset = strtotime($statusResult["Sunset"]);
	
	if ($currentTime <= $sunrise){
		return "morning";
	} elseif ($currentTime >= $sunset){
		return "night";
	} else {
		return "day";
	}
}

function retAllStatus(){
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=devices&rid=");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$statusResult = json_decode(curl_exec($curl), true);
	curl_close($curl);
	return $statusResult;
}

function setStatus($idx, $cmd){
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=$idx&switchcmd=$cmd");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	curl_close($curl);
	return "ok";
}

function setSecurity($status){
	if ($status == "Normal"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=" . SECURITY_ID . "&switchcmd=Disarm");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
	}
	if ($status == "ArmAway"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=" . SECURITY_ID . "&switchcmd=Arm%20Away");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
	}
	if ($status == "ArmHome"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=" . SECURITY_ID . "&switchcmd=Arm%20Home");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
	}
}

function setDimmerStatus($idx, $cmd, $force, $level){
	
	if ($cmd == "On"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=$idx&switchcmd=Set%20Level&level=100");
	} elseif ($cmd == "Off"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=$idx&switchcmd=$cmd");
	} elseif ($cmd == "Set%20Level"){
		$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=$idx&switchcmd=Set%20Level&level=$level");
	}
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	
	if ($force = false){
		$result = curl_exec($curl);
		curl_close($curl);
		return "ok 1";
	} else {
		$result = curl_exec($curl);
		sleep(1);
		$result = curl_exec($curl);
		sleep(2);
		$result = curl_exec($curl);
		curl_close($curl);
		return "ok 3";
	}
}

function getLightStatus($md5_only){
	$ajax = array();
	$meta = array();
	$result = retAllStatus();
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if (($statusType == "Lighting 1" OR $statusType == "Lighting 2") AND (strpos($statusName,"Aux") == false) AND ($statusHardware == "Z-Wave")){
			
			if(strpos($v2["Status"],"Set") !== false){
				$ajax["devices"][$v2["idx"]]["Status"] = "Transition";
			} else {
				$ajax["devices"][$v2["idx"]]["Status"] = $v2["Status"];
			}
			
			$ajax["devices"][$v2["idx"]]["Level"] = $v2["Level"];
			$ajax["devices"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["devices"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	$md5 = md5(print_r($ajax, true));
	
	if ($md5_only == "true"){
		return $md5;
	} else {
		$ajax["meta"]["md5"] = $md5;
		$meta["meta"]["md5"] = $md5;
		$timestamp = timestamp();
		$ajax["meta"]["timestamp"] = $timestamp;
		$meta["meta"]["timestamp"] = $timestamp;
		$ret_md5 = $_GET["md5"];

		if ($ret_md5 == $md5){
			return json_encode($meta);
		} else {
			return json_encode($ajax);
		}
	}
}

function logThis($message){
	if(defined('LOGGING_ENABLE')){
		$curl = curl_init(LOGGING_URL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "$message");
		$statusResult = json_decode(curl_exec($curl), true);
		curl_close($curl);
	}
}

function getDoorStatus($md5_only){
	$ajax = array();
	$meta = array();
	$result = retAllStatus();
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if (($statusType == "Lighting 1" OR $statusType == "Lighting 2") AND (strpos($statusName,"Door") == true) AND ($statusHardware == "MySensors")){
			$ajax["devices"][$v2["idx"]]["Status"] = $v2["Status"];
			$ajax["devices"][$v2["idx"]]["Level"] = $v2["Level"];
			$ajax["devices"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["devices"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	$md5 = md5(print_r($ajax, true));
	
	if ($md5_only == "true"){
		return $md5;
	} else {
		$ajax["meta"]["md5"] = $md5;
		$meta["meta"]["md5"] = $md5;
		$timestamp = timestamp();
		$ajax["meta"]["timestamp"] = $timestamp;
		$meta["meta"]["timestamp"] = $timestamp;
		$ret_md5 = $_GET["md5"];

		if ($ret_md5 == $md5){
			return json_encode($meta);
		} else {
			return json_encode($ajax);
		}
	}
}

function getAllStatus($md5_only, $format){
	$ajax = array();
	$meta = array();
	$result = retAllStatus();
	
	//Doors
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if (($statusType == "Lighting 1" OR $statusType == "Lighting 2") AND (strpos($statusName,"Door") == true) AND ($statusHardware == "MySensors")){
			$ajax["doors"][$v2["idx"]]["Status"] = $v2["Status"];
			$ajax["doors"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["doors"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	
	//Comfort
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if ($statusType == "Temp + Humidity"){
			$ajax["comfort"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["comfort"][$v2["idx"]]["Name"] = $v2["Name"];
			$ajax["comfort"][$v2["idx"]]["Temperature"] = number_format((float)$v2["Temp"], 2, '.', '');
			$ajax["comfort"][$v2["idx"]]["Humidity"] = $v2["Humidity"];
			$ajax["comfort"][$v2["idx"]]["ComfortLevel"] = $v2["HumidityStatus"];
		}
	}
	
	//Dummy
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if ($statusHardware == "Hardware Dummy"){
			
			if(strpos($v2["Status"],"Set") !== false){
				$ajax["dummy"][$v2["idx"]]["Status"] = "Transition";
			} else {
				$ajax["dummy"][$v2["idx"]]["Status"] = $v2["Status"];
			}
			
			if ($v2["Status"] == "Off"){
				$ajax["dummy"][$v2["idx"]]["Level"] = "0";
			} else {
				$ajax["dummy"][$v2["idx"]]["Level"] = $v2["Level"];
			}
			
			$ajax["dummy"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["dummy"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	
	//Security - must enable this
	if(defined('SECURITY_ENABLE')){
		foreach ($result["result"] as $i2=>$v2){
			$security_idx = $v2["idx"];
			if ($security_idx == SECURITY_ID){
			
				$securityStatus = $v2["Status"];
				
				$ajax["security"]["Normal"]["Name"] = "Normal";
				$ajax["security"]["Normal"]["Status"] = "disabled";
				$ajax["security"]["ArmAway"]["Name"] = "Arm Away";
				$ajax["security"]["ArmAway"]["Status"] = "disabled";
				$ajax["security"]["ArmHome"]["Name"] = "Arm Home";
				$ajax["security"]["ArmHome"]["Status"] = "disabled";
				
				if ($securityStatus == "Normal"){
					$ajax["security"]["Normal"]["Status"] = "enabled";
				} elseif ($securityStatus == "Arm Away"){
					$ajax["security"]["ArmAway"]["Status"] = "enabled";
				} elseif ($securityStatus == "Arm Home"){
					$ajax["security"]["ArmHome"]["Status"] = "enabled";
				}
			}
			
		}
	}
	
	//Lights
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if (($statusType == "Lighting 1" OR $statusType == "Lighting 2") AND (strpos($statusName,"Aux") == false) AND (strpos($statusName,"Bath") == false) AND (strpos($statusName,"Fan") == false) AND ($statusHardware == "Z-Wave")){
			
			if(strpos($v2["Status"],"Set") !== false){
				$ajax["lights"][$v2["idx"]]["Status"] = "Transition";
			} else {
				$ajax["lights"][$v2["idx"]]["Status"] = $v2["Status"];
			}
			
			if ($v2["Status"] == "Off"){
				$ajax["lights"][$v2["idx"]]["Level"] = "0";
			} else {
				$ajax["lights"][$v2["idx"]]["Level"] = $v2["Level"];
			}
			
			$ajax["lights"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["lights"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	
	$md5Lights = md5(print_r($ajax["lights"], true));
	
	//Fans
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if ((strpos($statusName,"Fan") == true) AND (strpos($statusName,"Bath") == false) AND ($statusHardware == "Z-Wave")){
			
			if(strpos($v2["Status"],"Set") !== false){
				$ajax["fans"][$v2["idx"]]["Status"] = "Transition";
			} else {
				$ajax["fans"][$v2["idx"]]["Status"] = $v2["Status"];
			}
			
			$ajax["fans"][$v2["idx"]]["Level"] = $v2["Level"];
			$ajax["fans"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["fans"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	
	$curlScene = curl_init(DOMOTICZ_JSON_URL . "?type=scenes");
	curl_setopt($curlScene, CURLOPT_RETURNTRANSFER, 1);
	$sceneResult = json_decode(curl_exec($curlScene), true);
	curl_close($curlScene);
	
	foreach ($sceneResult["result"] as $i3=>$v3){
		
		$ajax["scenes"][$v3["idx"]]["Name"] = $v3["Name"];
		
		$pos = strpos($v3["Description"], $md5Lights);
		
		if ($pos === false){
			$ajax["scenes"][$v3["idx"]]["Status"] = "Deactivated";
		} else {
			$ajax["scenes"][$v3["idx"]]["Status"] = "Activated";
		}
		
		
	}
	
	
	$md5 = md5(print_r($ajax, true));
	
	if ($md5_only == "true"){
		return $md5;
	} else {
		$ajax["meta"]["md5"] = $md5;
		$meta["meta"]["md5"] = $md5;
		$ajax["meta"]["lightd5"] = $md5Lights;
		$meta["meta"]["lightd5"] = $md5Lights;
		$timestamp = timestamp();
		$ajax["meta"]["timestamp"] = $timestamp;
		$meta["meta"]["timestamp"] = $timestamp;
		$ret_md5 = $_GET["md5"];

		if ($ret_md5 == $md5){
			if ($format == "array"){
				return $meta;
			} else {
				return json_encode($meta);
			}
		} else {
			if ($format == "array"){
				return $ajax;
			} else {
				return json_encode($ajax);
			}
		}
	}
}

function setSceneStatus($scene){
	
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchscene&idx=" . $scene . "&switchcmd=On");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$statusResult = json_decode(curl_exec($curl), true);
	curl_close($curl);
	
	sleep(1);
	
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchscene&idx=" . $scene . "&switchcmd=On");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$statusResult = json_decode(curl_exec($curl), true);
	curl_close($curl);
	
	sleep(2);
	
	$curl = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchscene&idx=" . $scene . "&switchcmd=On");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$statusResult = json_decode(curl_exec($curl), true);
	curl_close($curl);
	
}

?>