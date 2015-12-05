<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home Automation</title>
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery.mobile.custom.min.js"></script>
	<link rel="stylesheet" href="themes/controller.min.css" />
	<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="themes/jquery.mobile.structure-1.4.5.css" />
	<link rel="stylesheet" href="themes/style.css" />
	<script>

	
	$(document).ready(function(){	
	
		var lightHash;

		(function getAllStatus(){
			setTimeout(function(){
			$.ajax({
				type: "GET",
				dataType: "json",
				url: 'ajax/ajax.php?action=getAllStatus&md5=' + lightHash,
				async: true,
				
				success: function(data) {
				
					lightHash = data.meta.md5;
					
					var lightItems = [];
					
					if (data.lights){
					$.each(data.lights, function(key, val) {
						lightItems.push('<div class="ui-block-b"><div id="' + key + '" class="light ui-bar ui-bar-a ' + val.Status + '" style="height:80px"><center><h1>' + val.Name + '</h1></center></div></div>');
					});
					$('#lightBoard').html(lightItems).enhanceWithin();
					}
					
					var doorItems = [];
					
					if (data.doors){
					$.each(data.doors, function(key, val) {
						doorItems.push('<div class="ui-block-b"><div id="' + key + '" class="door ui-bar ui-bar-a ' + val.Status + '" style="height:80px"><center><h1>' + val.Name + '</h1></center></div></div>');
					});
					$('#doorBoard').html(doorItems).enhanceWithin();
					}
					
					var sceneItems = [];
					
					if (data.scenes){
					$.each(data.scenes, function(key, val) {
						sceneItems.push('<div class="ui-block-b"><div id="' + key + '" class="scene ui-bar ui-bar-a ' + val.Status + '" style="height:80px"><center><h1>' + val.Name + '</h1></center></div></div>');
					});
					$('#scenesBoard').html(sceneItems).enhanceWithin();
					}
					
					var fanItems = [];
					
					if (data.fans){
					$.each(data.fans, function(key, val) {
						fanItems.push('<div class="ui-block-b"><div id="' + key + '" class="fan ui-bar ui-bar-a ' + val.Status + '" style="height:80px"><center><h1>' + val.Name + '</h1></center></div></div>');
					});
					$('#fanBoard').html(fanItems).enhanceWithin();
					}
					
				},
				complete: getAllStatus
			})
			
			}, 100);
		})();
		
		
		$(document).on('click', '.light.Off', function() {
			$(this).addClass("Transition").removeClass("Off");
			myidx = $(this).attr("id");
			$.get('ajax/ajax.php?action=setDimmerStatus&idx=' + myidx + '&command=On&Level=100');
		});
		
		$(document).on('click', '.light.On', function() {
			$(this).addClass("Transition").removeClass("On");
			myidx = $(this).attr("id");
			$.get('ajax/ajax.php?action=setDimmerStatus&idx=' + myidx + '&command=Off');
		});
		
		$(document).on('click', '.light.Transition', function() {
			$(this).addClass("Transition").removeClass("On");
			myidx = $(this).attr("id");
			$.get('ajax/ajax.php?action=setDimmerStatus&idx=' + myidx + '&command=Off');
		});
		
		$(document).on('click', '.fan.Off', function() {
			$(this).addClass("Transition").removeClass("Off");
			myidx = $(this).attr("id");
			$.get('ajax/ajax.php?action=setStatus&idx=' + myidx + '&command=On');
		});
		
		$(document).on('click', '.fan.On', function() {
			$(this).addClass("Transition").removeClass("On");
			myidx = $(this).attr("id");
			$.get('ajax/ajax.php?action=setStatus&idx=' + myidx + '&command=Off');
		});
		
		$(document).on('click', '.Deactivated', function() {
			$(this).siblings(".Activated").removeClass("Activated");
			myscene = $(this).attr("id");
			$.get('ajax/ajax.php?action=setSceneStatus&scene=' + myscene);
			$("#scenesBoard").parent().addClass("ui-disabled").delay(3000).queue(function(next){
				$(this).removeClass("ui-disabled");
				next();
			});
		});
		
		
    });
	
	</script>
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
