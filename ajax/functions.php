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

function getAllStatus($md5_only){
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
	
	//Lights
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if (($statusType == "Lighting 1" OR $statusType == "Lighting 2") AND (strpos($statusName,"Aux") == false) AND (strpos($statusName,"Fan") == false) AND ($statusHardware == "Z-Wave")){
			
			if(strpos($v2["Status"],"Set") !== false){
				$ajax["lights"][$v2["idx"]]["Status"] = "Transition";
			} else {
				$ajax["lights"][$v2["idx"]]["Status"] = $v2["Status"];
			}
			
			$ajax["lights"][$v2["idx"]]["Level"] = $v2["Level"];
			$ajax["lights"][$v2["idx"]]["Type"] = $v2["Type"];
			$ajax["lights"][$v2["idx"]]["Name"] = $v2["Name"];
		}
	}
	
	//Fans
	foreach ($result["result"] as $i2=>$v2){
		$statusType = $v2["Type"];
		$statusHardware = $v2["HardwareName"];
		$statusName = $v2["Name"];
		if ((strpos($statusName,"Fan") == true) AND ($statusHardware == "Z-Wave")){
			
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
	
	//Scenes
	$ajax["scenes"]["1"]["Name"] = "All Off";
	$ajax["scenes"]["1"]["Status"] = "Deactivated";
	$ajax["scenes"]["2"]["Name"] = "All Full";
	$ajax["scenes"]["2"]["Status"] = "Deactivated";
	$ajax["scenes"]["3"]["Name"] = "Inside Full";
	$ajax["scenes"]["3"]["Status"] = "Deactivated";
	$ajax["scenes"]["4"]["Name"] = "Night Away";
	$ajax["scenes"]["4"]["Status"] = "Deactivated";
	$ajax["scenes"]["5"]["Name"] = "Movie";
	$ajax["scenes"]["5"]["Status"] = "Deactivated";
	$ajax["scenes"]["6"]["Name"] = "Entertaining";
	$ajax["scenes"]["6"]["Status"] = "Deactivated";
	
	/**
	7 = Down Hall
	8 = Garage
	9 = Entry
	10 = Front Porch
	34 = Dining Room
	36 = Family Room
	38 = Landing
	39 = Breakfast Room
	($ajax["lights"]["6"]["Level"] == "10")
	($ajax["lights"]["6"]["Status"] == "Transition")
	**/
	
	//Scene 1 - All Off
	if (
			($ajax["lights"]["7"]["Status"] == "Off")	//Down Hall
		&&	($ajax["lights"]["8"]["Status"] == "Off")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "Off")	//Entry
		&&	($ajax["lights"]["10"]["Status"] == "Off")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "Off")	//Dining Room
		&&	($ajax["lights"]["36"]["Status"] == "Off")	//Family Room
		&&	($ajax["lights"]["38"]["Status"] == "Off")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "Off")	//Breakfast Room
		)	
	{
		$ajax["scenes"]["1"]["Status"] = "Activated";
	}
	//Scene 2 - All Full
	elseif (
			($ajax["lights"]["7"]["Status"] == "On")	//Down Hall
		&&	($ajax["lights"]["8"]["Status"] == "On")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "On")	//Entry
		&&	($ajax["lights"]["10"]["Status"] == "On")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "On")	//Dining Room
		&&	($ajax["lights"]["36"]["Status"] == "On")	//Family Room
		&&	($ajax["lights"]["38"]["Status"] == "On")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "On")	//Breakfast Room
		)
	{
		$ajax["scenes"]["2"]["Status"] = "Activated";
	}
	//Scene 3 - Inside Full
	elseif (
			($ajax["lights"]["7"]["Status"] == "On")	//Down Hall
		&&	($ajax["lights"]["8"]["Status"] == "Off")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "On")	//Entry
		&&	($ajax["lights"]["10"]["Status"] == "Off")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "On")	//Dining Room
		&&	($ajax["lights"]["36"]["Status"] == "On")	//Family Room
		&&	($ajax["lights"]["38"]["Status"] == "On")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "On")	//Breakfast Room
		)
	{
		$ajax["scenes"]["3"]["Status"] = "Activated";
	}
	//Scene 4 - Night Away
	elseif (
			($ajax["lights"]["7"]["Status"] == "Off")	//Down Hall
		&&	($ajax["lights"]["8"]["Status"] == "Off")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "Off")	//Entry
		&&	($ajax["lights"]["10"]["Status"] == "On")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "Off")	//Dining Room
		&&	($ajax["lights"]["36"]["Status"] == "Off")	//Family Room
		&&	($ajax["lights"]["38"]["Status"] == "Off")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "Off")	//Breakfast Room
		)
	{
		$ajax["scenes"]["4"]["Status"] = "Activated";
	}
	//Scene 5 - Movie
	elseif (
			($ajax["lights"]["7"]["Status"] == "Transition")	//Down Hall
			&& ($ajax["lights"]["7"]["Level"] == "10")
		&&	($ajax["lights"]["8"]["Status"] == "Off")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "Off")	//Entry
		&&	($ajax["lights"]["10"]["Status"] == "On")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "Transition")	//Dining Room
			&& ($ajax["lights"]["34"]["Level"] == "10")
		&&	($ajax["lights"]["36"]["Status"] == "Transition")	//Family Room
			&& ($ajax["lights"]["36"]["Level"] == "10")
		&&	($ajax["lights"]["38"]["Status"] == "Off")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "Transition")	//Breakfast Room
			&& ($ajax["lights"]["39"]["Level"] == "25")
		)
	{
		$ajax["scenes"]["5"]["Status"] = "Activated";
	}
	//Scene 6 - Entertaining
	elseif (
			($ajax["lights"]["7"]["Status"] == "On")	//Down Hall
		&&	($ajax["lights"]["8"]["Status"] == "Off")	//Garage
		&&	($ajax["lights"]["9"]["Status"] == "On")	//Entry
			&& ($ajax["lights"]["9"]["Level"] == "80")
		&&	($ajax["lights"]["10"]["Status"] == "On")	//Front Porch
		&&	($ajax["lights"]["34"]["Status"] == "On")	//Dining Room
			&& ($ajax["lights"]["34"]["Level"] == "80")
		&&	($ajax["lights"]["36"]["Status"] == "On")	//Family Room
		&&	($ajax["lights"]["38"]["Status"] == "On")	//Landing
		&&	($ajax["lights"]["39"]["Status"] == "On")	//Breakfast Room
		)
	{
		$ajax["scenes"]["6"]["Status"] = "Activated";
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

function setSceneStatus($scene){
	
	if ($scene == "1"){
		
		/*
		setDimmerStatus("7", "Off", "false");	//Down Hall
		setDimmerStatus("8", "Off", "false");	//Garage
		setDimmerStatus("9", "Off", "false");	//Entry
		setDimmerStatus("10", "Off", "false");	//Front Porch
		setDimmerStatus("34", "Off", "false");	//Dining Room
		setDimmerStatus("36", "Off", "false");	//Family Room
		setDimmerStatus("38", "Off", "false");	//Landing
		setDimmerStatus("39", "Off", "false");	//Breakfast Room
		setDimmerStatus("7", "Off", "true");	//Down Hall
		setDimmerStatus("8", "Off", "true");	//Garage
		setDimmerStatus("9", "Off", "true");	//Entry
		setDimmerStatus("10", "Off", "true");	//Front Porch
		setDimmerStatus("34", "Off", "true");	//Dining Room
		setDimmerStatus("36", "Off", "true");	//Family Room
		setDimmerStatus("38", "Off", "true");	//Landing
		setDimmerStatus("39", "Off", "true");	//Breakfast Room
		*/
		
		$scene = array();
		
		$scene[] = array(
			"idx" => "7",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "8",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "9",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "10",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "34",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "36",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "38",
			"setting" => "Off",
			"percent" => "100"
		);
		$scene[] = array(
			"idx" => "39",
			"setting" => "Off",
			"percent" => "100"
		);
		
		$cm = curl_multi_init();
		
		foreach ($scene as $sceneSet){
			$ch = curl_init(DOMOTICZ_JSON_URL . "?type=command&param=switchlight&idx=" . $sceneSet['idx'] . "&switchcmd=" . $sceneSet['setting']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_multi_add_handle($cm, $ch);
		}
		
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);
		sleep(1);
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);
		sleep(2);
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);
		curl_close($cm);
		
	} elseif ($scene == "2"){
		setDimmerStatus("7", "On", "false");	//Down Hall
		setDimmerStatus("8", "On", "false");	//Garage
		setDimmerStatus("9", "On", "false");	//Entry
		setDimmerStatus("10", "On", "false");	//Front Porch
		setDimmerStatus("34", "On", "false");	//Dining Room
		setDimmerStatus("36", "On", "false");	//Family Room
		setDimmerStatus("38", "On", "false");	//Landing
		setDimmerStatus("39", "On", "false");	//Breakfast Room
		setDimmerStatus("7", "On", "true");		//Down Hall
		setDimmerStatus("8", "On", "true");		//Garage
		setDimmerStatus("9", "On", "true");		//Entry
		setDimmerStatus("10", "On", "true");	//Front Porch
		setDimmerStatus("34", "On", "true");	//Dining Room
		setDimmerStatus("36", "On", "true");	//Family Room
		setDimmerStatus("38", "On", "true");	//Landing
		setDimmerStatus("39", "On", "true");	//Breakfast Room
	} elseif ($scene == "3"){
		setDimmerStatus("7", "On", "false");	//Down Hall
		setDimmerStatus("8", "Off", "false");	//Garage
		setDimmerStatus("9", "On", "false");	//Entry
		setDimmerStatus("10", "Off", "false");	//Front Porch
		setDimmerStatus("34", "On", "false");	//Dining Room
		setDimmerStatus("36", "On", "false");	//Family Room
		setDimmerStatus("38", "On", "false");	//Landing
		setDimmerStatus("39", "On", "false");	//Breakfast Room
		setDimmerStatus("7", "On", "true");	//Down Hall
		setDimmerStatus("8", "Off", "true");	//Garage
		setDimmerStatus("9", "On", "true");	//Entry
		setDimmerStatus("10", "Off", "true");	//Front Porch
		setDimmerStatus("34", "On", "true");	//Dining Room
		setDimmerStatus("36", "On", "true");	//Family Room
		setDimmerStatus("38", "On", "true");	//Landing
		setDimmerStatus("39", "On", "true");	//Breakfast Room
	} elseif ($scene == "4"){
		setDimmerStatus("7", "Off", "false");	//Down Hall
		setDimmerStatus("8", "Off", "false");	//Garage
		setDimmerStatus("9", "Off", "false");	//Entry
		setDimmerStatus("10", "On", "false");	//Front Porch
		setDimmerStatus("34", "Off", "false");	//Dining Room
		setDimmerStatus("36", "Off", "false");	//Family Room
		setDimmerStatus("38", "Off", "false");	//Landing
		setDimmerStatus("39", "Off", "false");	//Breakfast Room
		setDimmerStatus("7", "Off", "true");	//Down Hall
		setDimmerStatus("8", "Off", "true");	//Garage
		setDimmerStatus("9", "Off", "true");	//Entry
		setDimmerStatus("10", "On", "true");	//Front Porch
		setDimmerStatus("34", "Off", "true");	//Dining Room
		setDimmerStatus("36", "Off", "true");	//Family Room
		setDimmerStatus("38", "Off", "true");	//Landing
		setDimmerStatus("39", "Off", "true");	//Breakfast Room
	} elseif ($scene == "5"){
		setDimmerStatus("7", "Set%20Level", "false", "11");	//Down Hall
		setDimmerStatus("8", "Off", "false");	//Garage
		setDimmerStatus("9", "Off", "false");	//Entry
		setDimmerStatus("10", "On", "false");	//Front Porch
		setDimmerStatus("34", "Set%20Level", "false", "11");	//Dining Room
		setDimmerStatus("36", "Set%20Level", "false", "11");	//Family Room
		setDimmerStatus("38", "Off", "false");	//Landing
		setDimmerStatus("39", "Set%20Level", "false", "26");	//Breakfast Room
		setDimmerStatus("7", "Set%20Level", "true", "11");	//Down Hall
		setDimmerStatus("8", "Off", "true");	//Garage
		setDimmerStatus("9", "Off", "true");	//Entry
		setDimmerStatus("10", "On", "true");	//Front Porch
		setDimmerStatus("34", "Set%20Level", "true", "11");	//Dining Room
		setDimmerStatus("36", "Set%20Level", "true", "11");	//Family Room
		setDimmerStatus("38", "Off", "true");	//Landing
		setDimmerStatus("39", "Set%20Level", "true", "26");	//Breakfast Room
	} elseif ($scene == "6"){
		setDimmerStatus("7", "On", "false");	//Down Hall
		setDimmerStatus("8", "Off", "false");	//Garage
		setDimmerStatus("9", "Set%20Level", "false", "81");	//Entry
		setDimmerStatus("10", "On", "false");	//Front Porch
		setDimmerStatus("34", "Set%20Level", "false", "81");	//Dining Room
		setDimmerStatus("36", "On", "false");	//Family Room
		setDimmerStatus("38", "On", "false");	//Landing
		setDimmerStatus("39", "On", "false");	//Breakfast Room
		setDimmerStatus("7", "On", "true");	//Down Hall
		setDimmerStatus("8", "Off", "true");	//Garage
		setDimmerStatus("9", "Set%20Level", "true", "81");	//Entry
		setDimmerStatus("10", "On", "true");	//Front Porch
		setDimmerStatus("34", "Set%20Level", "true", "81");	//Dining Room
		setDimmerStatus("36", "On", "true");	//Family Room
		setDimmerStatus("38", "On", "true");	//Landing
		setDimmerStatus("39", "On", "true");	//Breakfast Room
	}
}

?>