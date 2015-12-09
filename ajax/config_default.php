<?php

$config = array();

/* Domoticz Information */
define( 'DOMOTICZ_JSON_URL',	'http://192.168.1.201:8080/json.htm' ); //MUST BE FULL PATH, BUT NOT INCLUDE QUESTION MARK


/* Logging Information */
//define( 'LOGGING_ENABLE',		'YES' ); //ENABLE THIS IF YOU WANT LOGGING ENABLED. MUST DEFINE LOGGING URL. CURRENTLY UNCOMMENTING THIS LINE WILL ENABLE LOGGING, EVEN IF YOU DEFINE AS NO.
define( 'LOGGING_URL',			'' );


/* Security Panel */
$config["securityEnable"] = "No";
//$config["securityEnable"] = "Yes"; //uncomment this if you wish to enable security panel compatibility

?>