<?php
require('ajax/functions.php');

$getAllStatus = getAllStatus();

$comfort = $getAllStatus["comfort"];

if (($comfort["43"] - $comfort["15"]) >= 10){
	setStatus("41", "On");
	echo "41 on";
} elseif (($comfort["43"] - $comfort["15"]) <= 10){
	setStatus("41", "Off");
	echo "41 off";
}

?>