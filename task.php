<?php

/**
This is an example script for running tasks. The idea is to set a cron job to call this page via CURL or WGET every minute or even 30 seconds.
**/

$debug = $_GET["debug"];
if ($debug = "1"){
	$debug = true;
}

require('ajax/functions.php');

$getAllStatus = getAllStatus("null", "array");

/**
Example of, if the bathroom (idx 43) is 15 or more percentage points more humid than the rest of the house (idx 15), then turn the fan on. 
**/
if (($getAllStatus["comfort"]["43"]["Humidity"] - $getAllStatus["comfort"]["15"]["Humidity"]) >= 15){
	setStatus("41", "On");
	setStatus("44", "On"); //Set the dummy switch on so we know we turned the fan on
} elseif ((($getAllStatus["comfort"]["43"]["Humidity"] - $getAllStatus["comfort"]["15"]["Humidity"]) < 15) && ($getAllStatus["dummy"]["44"]["Status"] == "On")){
	setStatus("41", "Off");
	setStatus("44", "Off"); //Set the dummy switch off so we know we turned the fan off
}

?>