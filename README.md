WARNING - READ SECTION 3.0 BEFORE PROCEEDING

===
1.0 SETUP
===

1.1 Unzip into a directory of an Apache/PHP server. Modify ajax/config_default.php to suit your needs, and RENAME to config.php.
1.2 You may need to modify the criteria for 'light switches' and 'fans' and 'doors', in the functions.php page. Right now, it will only pull in Z-Wave light switches, and and doors that have the name "door" in them.


===
2.0 ACTIONS
===

If you wish to have if/then type functionality, the PHP script "ajax/connector.php" is the script that handles all functionality. You must call it via Domoticz by entering on/off actions for various devices. Currently, you must have PIR or other motion sensors for this to work, and/or doors.

2.1 Create a dummy switch in Domoticz for a motion decay. Set the off delay to 60 seconds. Set the On action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=on and the off action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=off - for example, http://example.com/home-automation/ajax/connector.php?idx=24&action=on.

2.2 Set the "motionID" variable in ajax/connector.php to the idx of the dummy switch.

2.3 Set the nestAway variable in ajax/connector.php to the idx of the Nest Away switch in Domoticz, assuming you have a Nest thermostat. If you don't have one, then you will need to modify functionality of this script.

2.4 Open Domoticz and enter "ON" actions for any motion sensors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-motion-sensor]&action=on". For example, http://example.com/home-automation/ajax/connector.php?idx=14&action=on

2.5 Open Domoticz and enter "ON" actions for any doors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-door]&action=open". For example, http://example.com/home-automation/ajax/connector.php?idx=15&action=open

2.6 Modify the contents of ajax/connector.php to build in the linkages you wish.

2.7 Modify ajax/functions.php to change scenes. Basically, the scenes allow for describing what the scene is, and setting the scene. Future updates will make this much easier, and abstract this functionality. Notice the mis-match in dimmer switch settings - this is due to a Domoticz issue. Be careful with this functionality out of the box, as it will be unpredictable in your environment. Further updates will allow a scene definition file so the code and scene definitions are separated.


===
3.0 TODO
===

This is basically a snap-shop of my configuration, including scenes and connectors, etc. Scenes will not work and may cause unpredictable results in your house (i.e. I do not know what will happen if the scripts attempts to turn on your sprinkler system, fire alarm, or any other device if you do not appropriately modify the scenes section of this script. PROCEED AT YOUR OWN RISK!!!!