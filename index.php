<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home Automation</title>
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/functions.js"></script>
	<script src="js/jquery.mobile.custom.min.js"></script>
	<link rel="stylesheet" href="themes/controller.min.css" />
	<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="themes/jquery.mobile.structure-1.4.5.css" />
	<link rel="stylesheet" href="themes/style.css" />
</head>
<body>
<div data-role="page" id="lightsdoors">

	<div data-role="navbar">
		<ul>
			<li><a class="ui-disabled" href="#lightsdoors">Lights / Doors</a></li>
			<li><a href="#security">Security</a></li>
			<li><a href="#scenes">Scenes</a></li>
		</ul>
	</div><!-- /navbar -->
	
	<div data-role="header">
		<h1> Light Switches </h1>
	</div><!-- /header -->

	<div data-role="content">	
		<div id="lightBoard" class="ui-grid-d">
		</div><!-- /grid-d -->
	</div><!-- /content -->
	
	
	<div data-role="header">
		<h1> Fans </h1>
	</div><!-- /header -->

	<div data-role="content">	
		<div id="fanBoard" class="ui-grid-d">
		</div><!-- /grid-d -->
	</div><!-- /content -->
	
	
	<div data-role="header">
		<h1> Doors </h1>
	</div><!-- /header -->
	
	<div data-role="content">	
		<div id="doorBoard" class="ui-grid-d">
		</div><!-- /grid-d -->
	</div><!-- /content -->

	<div data-role="footer">
		<h4>&nbsp;</h4>
	</div><!-- /footer -->
</div><!-- /page -->


<div data-role="page" id="scenes">

	<div data-role="navbar">
		<ul>
			<li><a href="#lightsdoors">Lights / Doors</a></li>
			<li><a href="#security">Security</a></li>
			<li><a class="ui-disabled" href="#scenes">Scenes</a></li>
		</ul>
	</div><!-- /navbar -->
	
	<div data-role="header">
		<h1> Scenes </h1>
	</div><!-- /header -->

	<div data-role="content">	
		<div id="scenesBoard" class="ui-grid-d">
		</div><!-- /grid-d -->
	</div><!-- /content -->

	<div data-role="footer">
		<h4>&nbsp;</h4>
	</div><!-- /footer -->
</div><!-- /page -->



<div data-role="page" id="security">

	<div data-role="navbar">
		<ul>
			<li><a href="#lightsdoors">Lights / Doors</a></li>
			<li><a class="ui-disabled" href="#security">Security</a></li>
			<li><a href="#scenes">Scenes</a></li>
		</ul>
	</div><!-- /navbar -->
	
	<div data-role="header">
		<h1> Security </h1>
	</div><!-- /header -->

	<div data-role="content">	
		<div id="securityBoard" class="ui-grid-d">
		</div><!-- /grid-d -->
	</div><!-- /content -->

	<div data-role="footer">
		<h4>&nbsp;</h4>
	</div><!-- /footer -->
</div><!-- /page -->



</body>
</html>
