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
				
				var securityItems = [];

				if (data.security){
				$.each(data.security, function(key, val) {
					securityItems.push('<div class="ui-block-b"><div id="' + key + '" class="door ui-bar ui-bar-a ' + val.Status + '" style="height:80px"><center><h1>' + val.Name + '</h1></center></div></div>');
				});
				$('#securityBoard').html(securityItems).enhanceWithin();
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