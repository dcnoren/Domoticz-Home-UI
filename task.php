<?php
require('ajax/functions.php');

$getAllStatus = getAllStatus("null", "array");

//print_r ($getAllStatus);

//echo "<br />";

echo $getAllStatus["comfort"]["43"]["Humidity"];

if (($getAllStatus["comfort"]["43"]["Humidity"] - $getAllStatus["comfort"]["15"]["Humidity"]) >= 10){
	echo "43 is " . $getAllStatus["comfort"]["43"]["Humidity"] . " and 15 is " . $getAllStatus["comfort"]["15"]["Humidity"];
	setStatus("41", "On");
	echo "<br />41 on";
} elseif (($getAllStatus["comfort"]["43"]["Humidity"] - $getAllStatus["comfort"]["15"]["Humidity"]) <= 10){
	echo "43 is " . $getAllStatus["comfort"]["43"]["Humidity"] . " and 15 is " . $getAllStatus["comfort"]["15"]["Humidity"];
	setStatus("41", "Off");
	echo "<br />41 off";
}

?>