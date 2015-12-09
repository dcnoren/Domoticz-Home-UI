<?php
require('ajax/functions.php');

$getAllStatus = getAllStatus();

print_r($getAllStatus);

if (($getAllStatus["comfort"]["43"] - $getAllStatus["comfort"]["15"]) >= 10){
	echo "43 is " . $getAllStatus["comfort"]["43"] . " and 15 is " . $getAllStatus["comfort"]["15"];
	setStatus("41", "On");
	echo "41 on";
} elseif (($getAllStatus["comfort"]["43"] - $getAllStatus["comfort"]["15"]) <= 10){
	echo "43 is " . $getAllStatus["comfort"]["43"] . " and 15 is " . $getAllStatus["comfort"]["15"];
	setStatus("41", "Off");
	echo "41 off";
}

?>