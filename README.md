WARNING - READ SECTION 3.0 BEFORE PROCEEDING

====
1.0 SETUP
====

1.1 Unzip into a directory of an Apache/PHP server. Modify ajax/config_default.php to suit your needs, and RENAME to config.php. 


====
2.0 ACTIONS
====

If you wish to have if/then type functionality, the PHP script "ajax/connector.php" is the script that handles all functionality. You must call it via Domoticz by entering on/off actions for various devices. Currently, you must have PIR or other motion sensors for this to work, and/or doors.

2.1 Create a dummy switch in Domoticz for a motion decay. Set the off delay to 60 seconds. Set the On action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=on and the off action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=off - for example, http://example.com/home-automation/ajax/connector.php?idx=24&action=on.

2.2 Set the "motionID" variable in ajax/connector.php to the idx of the dummy switch.

2.3 Set the nestAway variable in ajax/connector.php to the idx of the Nest Away switch in Domoticz, assuming you have a Nest thermostat. If you don't have one, then you will need to modify functionality of this script.

2.4 Open Domoticz and enter "ON" actions for any motion sensors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-motion-sensor]&action=on". For example, http://example.com/home-automation/ajax/connector.php?idx=14&action=on

2.5 Open Domoticz and enter "ON" actions for any doors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-door]&action=open". For example, http://example.com/home-automation/ajax/connector.php?idx=15&action=open

2.6 Modify the contents of ajax/connector.php to build in the linkages you wish.


====
3.0 TODO
====

This is basically a snap-shop of my configuration, including scenes and connectors, etc. Scenes will not work and may cause unpredictable results in your house (i.e. I do not know what will happen if the scripts attempts to turn on your sprinkler system, fire alarm, or any other device if you do not appropriately modify the scenes section of this script. PROCEED AT YOUR OWN RISK!!!!