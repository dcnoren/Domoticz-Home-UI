WARNING - READ SECTION 3.0 BEFORE PROCEEDING

===
1.0 SETUP
===

1.1 Clone Master branch

1.2 Unzip into a directory of an Apache/PHP server. Modify ajax/config_default.php to suit your needs, and RENAME to config.php.

1.3 You may need to modify the criteria for 'light switches' and 'fans' and 'doors', in the functions.php page. Right now, it will only pull in Z-Wave light switches, and doors that have the name "door" in them.


===
2.0 ACTIONS
===

If you wish to have if/then type functionality, the PHP script "ajax/connector.php" is the script that handles all functionality. You must call it via Domoticz by entering on/off actions for various devices. Currently, you must have PIR (or other motion sensors) and/or doors with reed switches for this to work.

2.1 Create a dummy switch in Domoticz for a motion decay. Set the off delay to 60 seconds. Set the On action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=on and the off action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=off - for example, http://example.com/home-automation/ajax/connector.php?idx=24&action=on.

2.2 Set the "motionID" variable in ajax/connector.php to the idx of the dummy switch.

2.3 Set the nestAway variable in ajax/connector.php to the idx of the Nest Away switch in Domoticz, assuming you have a Nest thermostat. If you don't have one, then you will need to modify functionality of this script.

2.4 Open Domoticz and enter "ON" actions for any motion sensors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-motion-sensor]&action=on". For example, http://example.com/home-automation/ajax/connector.php?idx=14&action=on

2.5 Open Domoticz and enter "ON" actions for any doors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-door]&action=open". For example, http://example.com/home-automation/ajax/connector.php?idx=15&action=open

2.6 Modify the contents of ajax/connector.php to build in the linkages you wish.

2.7 Create any scenes you wish directly in Domoticz, and they will reflect on the UI. Groups not (yet) supported.

===
3.0 TODO
===

This is basically a snap-shop of my configuration, including scenes and connectors, etc. PROCEED AT YOUR OWN RISK!!!!