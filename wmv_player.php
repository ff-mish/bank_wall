<?php
	$video = $_GET['file'];
  // Get video thumbnail ratio, use to resize the wmv video
	$cover = str_replace('wmv','jpg',$video);
	$size = getimagesize("./api/".$cover);
	$ratio = $size[0] / $size[1];
?>
<!DOCTYPE>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>SG WALL</title>
    <link href="css/layout.css" rel="stylesheet" type="text/css" />
    <link href="css/animation.css" rel="stylesheet" type="text/css" />
    <link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <style>
        html,body {height:100%;width:100%;text-align: center;}
	    #wmp {z-index:1;position: relative;}
		.wmp .poster {position:absolute;width:100%;height:100%;z-index:2;display:none;}
		.loading {display:none;}
		.playbtn {cursor: pointer;}
    </style>

</head>
<body class="wmp">
<div class="poster"><img width="100%" src="./api/<?php echo $cover;?>" /></div>
<object id="wmp" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" standby="Loading Microsoft® Windows® Media Player components..." width="100%" height="100%" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsm p2inf.cab#Version=6,4,7,1112">
    <param name="URL" value="./api<?php echo $video;?>">
    <param name="AutoStart" value="false">
    <param name="showcontrols" value="false">
	<param name="controls" value="false">
	<param name="windowlessVideo" value="true">
	<param name="ShowDisplay" value="false">
	<param name="showstatusbar" value="false">
    <param name="enablepositioncontrols" value="true">
    <param name="showpositioncontrols" value="true">
    <param name="StretchToFit" value="true">
    <param name="showaudiocontrols" value="false">
    <param name="enablecontextmenu" value="true">
	<param name="uiMode" value="none">
</object>
<div class="loading">Loading</div>


<div class="playbtn paused">
	<div class="icon"></div>
</div>

<div class="control">
	<div class="bar-wrap">
		<div class="bar-percent"></div>
	</div>

	<div class="fullscreen"></div>
</div>

<script src="js/jquery/jquery-1.102.js"></script>
<script>
	jQuery.fn.extend({
		ensureLoad: function(handler) {
			return this.each(function() {
				if(this.complete) {
					handler.call(this);
				} else {
					$(this).load(handler);
				}
			});
		}
	});
</script>


<script>
	var playInterval;
	var player = document.getElementById("wmp");
	$('.playbtn').click(function(){
		if($(this).hasClass('paused')){
			$(this).removeClass('paused');
			player.controls.play();
			$('.control').fadeIn();
		}
		else {
			$(this).addClass('paused');
			player.controls.pause();
			$('.control').fadeOut();
		}
	});

	$('.fullscreen').click(function(){
		player.fullScreen=true;
	});

	$('.playbtn').mouseenter(function(){
		$('.control').fadeIn(1000);
	});

	$('.playbtn').mouseleave(function(){
		$('.control').fadeOut(1000);
	});


	function handler(type) {
		var a = arguments;
		window.top.wmvPause();
		if(a[1] == 9) {
			$('.loading').show();
		}
		if(a[1] == 3) {
			window.top.wmvPlaying();
			if(!playInterval) {
				var duration = player.currentMedia.duration;
				playInterval = setInterval(function(){
					var currentPos = player.controls.currentPosition;
					var percent = currentPos / duration;
					if(percent > 0) {
						//$('.control').fadeIn();
						$('.loading').remove();
						$('.poster').hide();
					}
					$('.bar-percent').css({width: percent*100 + '%'});
				}, 300);
			}
		}
		if(a[1] == 1) {
			$('.bar-percent').css({width:0});
			//$('.control').fadeOut();
			$('.playbtn').addClass('paused');
			clearInterval(playInterval);
			playInterval = null;
		}
	};

	$(window).resize(function(){
		var marginTop = ($(window).height() - ($(window).width() / <?php echo $ratio;?>)) / 2;
		$('.poster img').css({marginTop: marginTop});
	});



	$('.poster img').ensureLoad(function(){
		setTimeout(function(){
			$(window).trigger('resize');
			$('.wmp .poster').fadeIn();
		}, 3000);
	});

	var iframePlay = function() {
		$('.playbtn').click();
	}

</script>
<script for="wmp" event="playstatechange(newState)">
	handler.call(this, "playstatechange", newState);
</script>
</body>
</html>


