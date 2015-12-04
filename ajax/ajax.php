<?php
require('functions.php');

$action = "";
if (isset($_GET["action"])){
	$action = $_GET["action"];
}

$idx = "";
if (isset($_GET["idx"])){
	$idx = $_GET["idx"];
}

$command = "";
if (isset($_GET["command"])){
	$command = $_GET["command"];
}

$get_md5 = "";
if (isset($_GET["md5"])){
	$get_md5 = $_GET["md5"];
}

$scene = "";
if (isset($_GET["scene"])){
	$scene = $_GET["scene"];
}

$level = "100";
if (isset($_GET["level"])){
	$level = $_GET["level"];
}

if ($action == "getLightStatus"){
	
	$ret_md5 = getLightStatus("true");
	$ii = 0;
	
	while (($ret_md5 == $get_md5) && ($ii <= 20)){
		$ii++;
		sleep(1);
		$ret_md5 = getLightStatus("true");
	}
	
	$result = getLightStatus("false");
	echo $result;
} elseif ($action == "setDimmerStatus"){
	$result = setDimmerStatus($idx, $command, "true", $level);
	echo $result;
} elseif ($action == "setStatus"){
	$result = setStatus($idx, $command, "true");
	echo $result;
}  elseif ($action == "getDoorStatus"){
	
	$ret_md5 = getDoorStatus("true");
	$ii = 0;
	
	while (($ret_md5 == $get_md5) && ($ii <= 20)){
		$ii++;
		sleep(1);
		$ret_md5 = getDoorStatus("true");
	}
	
	$result = getDoorStatus("false");
	echo $result;
}  elseif ($action == "getAllStatus"){
	
	$ret_md5 = getAllStatus("true");
	$ii = 0;
	
	while (($ret_md5 == $get_md5) && ($ii <= 20)){
		$ii++;
		sleep(1);
		$ret_md5 = getAllStatus("true");
	}
	
	$result = getAllStatus("false");
	echo $result;
} elseif ($action == "setSceneStatus"){
	setSceneStatus($scene);
}

?>