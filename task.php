<?php
require('ajax/functions.php');

$getAllStatus = getAllStatus();

$comfort = $getAllStatus["comfort"];

if (($comfort["43"] - $comfort["15"]) >= 10){
	setStatus("41", "On");
} elseif (($comfort["43"] - $comfort["15"]) <= 10){
	setStatus("41", "Off");
}

?>