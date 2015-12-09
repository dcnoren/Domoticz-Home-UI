<?php
require('ajax/functions.php');

$getAllStatus = getAllStatus();

$comfort = array();
$comfort = $getAllStatus["comfort"];

if (($comfort["43"] - $comfort["15"]) >= 10){
	echo "43 is " . $comfort["43"] . " and 15 is " . $comfort["15"];
	setStatus("41", "On");
	echo "41 on";
} elseif (($comfort["43"] - $comfort["15"]) <= 10){
	echo "43 is " . $comfort["43"] . " and 15 is " . $comfort["15"];
	setStatus("41", "Off");
	echo "41 off";
}

?>