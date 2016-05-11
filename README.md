As of January 2016, I am no longer updating this project. The JSON API approach to interfacing with Domoticz was highly inefficient, and PHP is inefficient at daemon-like tasks that were needed here. I have since moved to MQTT and Node.JS for this. You can find the ported project in my other GitHub repositories.



This is meant to be used in conjunction with Domoticz (http://www.domoticz.com) which is a home automation control panel software suite. While Domoticz has a powerful events system and user interface, everything can become rather cluttered and difficult to maintain exactly how one wishes. Additionally, it is not necessarily user-friendly.

This project utilizes jQuery to create a user-friendly, WAF-passing (wife acceptability factor), simple interface. It tackles a few design issues with Domoticz, such as fixing the general issues with Z-Wave light switches that prevent instant-status updates. When using this UI, the Z-Wave commands will be run multiple times over less than 5 seconds to 'force' the lights to reflect the current physical status.

Current state is limited to doors, lights, fans, and PIR motion sensors. Open an issue and I would be happy to build in additional functionality, as long as I have or can easily get the appropriate equipment.


===
1.0 SETUP
===

1.1 Clone Master branch

1.2 Unzip into a directory of an Apache/PHP server. Modify ajax/config_default.php to suit your needs, and RENAME to config.php.

1.3 You may need to modify the criteria for 'light switches' and 'fans' and 'doors', in the functions.php page. Right now, it will only pull in Z-Wave light switches, and doors that have the name "door" in them -- among other criteria.

1.4 Create any scenes you wish directly in Domoticz, and they will reflect on the UI. Groups not (yet) supported.

1.5 If desired, you can program information in to the scene description to let the UI reflect when a scene is activated. To do so, you need the MD5 hash of the current light status, which is generally easy to do - as I give it to you in the ajax call. After creating some scenes, click on a scene button in the UI to activate the scene. Then, visit your application URL (e.g. http://example.com/home-automation/ajax/ajax.php?action=getAllStatus ) and look for the JSON value for the "lightd5" key. Copy that value, which is 32 characters (do not include quotation marks), and enter that into the description for the scene on Domoticz. Once you save the scene, any time the scene is active, you will see it highlighted in blue with a "rolling cinema film" icon.

===
2.0 ACTIONS
===

If you wish to have "if this then that" type functionality, the PHP script "ajax/connector.php" is the script that handles all functionality. You must call it via Domoticz by entering on/off actions for various devices. Currently, you must have PIR (or other motion sensors) and/or doors with reed switches for this to work.

2.1 Create a dummy switch in Domoticz for a motion decay. Set the off delay to 60 seconds. Set the On action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=on and the off action to http://URL/ajax/connector.php?idx=[ID-of-dummy-switch]&action=off - for example, http://example.com/home-automation/ajax/connector.php?idx=24&action=on.

2.2 Set the "motionID" variable in ajax/connector.php to the idx of the dummy switch.

2.3 Set the nestAway variable in ajax/connector.php to the idx of the Nest Away switch in Domoticz, assuming you have a Nest thermostat. If you don't have one, then you will need to modify functionality of this script.

2.4 Open Domoticz and enter "ON" actions for any motion sensors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-motion-sensor]&action=on". For example, http://example.com/home-automation/ajax/connector.php?idx=14&action=on

2.5 Open Domoticz and enter "ON" actions for any doors you have. Set the "ON" action to be "http://URL/ajax/connector.php?idx=[ID-of-door]&action=open". For example, http://example.com/home-automation/ajax/connector.php?idx=15&action=open

2.6 Modify the contents of ajax/connector.php to build in the linkages you wish.

===
3.0 TODO
===

3.1 Logging is only for ajax/connector.php

3.2 Limited devices currently

3.3 Security tab does not integrate with Domoticz security system

3.4 Security tab will eventually allow arming/disarming system and setting parameters for alarms

3.5 Nest thermostat is required if you use connector - this is to enable/disable "away mode" on Nest. Future releases will make this optional.
