<?php
//
/* PODEŠAVANJE ROOT FOLDERA */
define ('ROOT', '/quantox/'); // kada je sajt na serveru moze se staviti i https://sajt.com/ ali OBAVEZNO završiti sa kosom crtom '/'

/* sistem */
if (isset($_GET['lat']) AND isset($_GET['lng'])) {
	
	$data = '';

	$file = file_get_contents("http://public-api.adsbexchange.com/VirtualRadar/AircraftList.json?lat=$_GET[lat]&lng=$_GET[lng]&fDstL=0&fDstU=100", FILE_USE_INCLUDE_PATH);
	$json = json_decode($file, true);

	$json = ($json['acList']);

	for ($i=0; $i < sizeof($json); $i++) {
		
		$data .= "<p>".'Flight Code: '.$json[$i]['Id'].' | Manufacturer: '.$json[$i]['Man'].' | Altitude: '."<b>".$json[$i]['Alt']."</b> <a href='".ROOT.'index.php?lat='.$_GET['lat']."&lng=".$_GET['lng']."&details=".$json[$i]['Id'].'_'.$i."'>Details --&gt;</a></p>";
	}

	if (isset($_GET['details'])) {

		$air = explode ('_', $_GET['details']);
		
		$j = $air[0];
		$k = $air[1];

		if ($json[$k]['Id'] == $j) {

			$airplane = $json[$k];

			if (isset($airplane['From']) AND isset($airplane['To'])) {
			
				$data = "
				<img id='man' src='https://logo.clearbit.com/".strtolower($airplane['Man']).".com?s=64'>
				<p><a href='".ROOT.'index.php?lat='.$_GET['lat']."&lng=".$_GET['lng']."'>&lt;-- All Airplane</a> Manufacturer: ".$airplane['Man']." | Model: ".$airplane['Mdl']." | <b>From: </b>".$airplane['From'].' <b>To:</b> '.$airplane['To']."</p>";
			} else {

				$data = "
				<img id='man' src='https://logo.clearbit.com/".strtolower($airplane['Man']).".com?s=64'>
				<p><a href='".ROOT.'index.php?lat='.$_GET['lat']."&lng=".$_GET['lng']."'>&lt;-- All Airplane</a> Manufacturer: ".$airplane['Man']." | Model: ".$airplane['Mdl']." | <b>Operater:</b> ".$airplane['Op']."</p>";
			}
		}
	}
}

?>
<!doctype html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<meta name='description' content='Quantox'>
	<meta name='keywords' content='quantox'>
	<meta name='author' content='Vuk Mileusnic'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<link rel='icon' type='image/png' href='favicon.png'>
	<link rel='stylesheet' type='text/css' href='look/css/style.css'>
	<script src='scripts/jquery-3.3.1.min.js'></script>
	<script>
		var x = document.getElementById("loc");

		function getLocation() {
			if (navigator.geolocation) {
		    	navigator.geolocation.getCurrentPosition(showPosition);
			} else { 
		    	x.innerHTML = "Geolocation is not supported by this browser.";
			}
		}

		function showPosition(position) {
			window.location.href = "<?php echo ROOT.'index.php?lat='; ?>" + position.coords.latitude + "&lng=" + position.coords.longitude;
		}
	</script>
	<title>Quantox Check Airplane</title>
</head>

<body>
	<div id='logo'>
		<a href='<?php echo ROOT; ?>index.php'><img src='look/img/logo.png'></a>
		<h1>Quantox Check Airplane</h1>
	</div>
<div id='main'>
	<button class='button' onclick='getLocation()'>Try it</button>
	<div id='loc'>
		<?php

		if (isset($_GET['lat']) AND isset($_GET['lng']) AND !isset($_GET['details'])) {
			echo 'Latitude: '.$_GET['lat']."<br>";
			echo 'Longitude: '.$_GET['lng']."<br><br>";
			echo $data;
		} else if (isset($_GET['lat']) AND isset($_GET['lng']) AND isset($_GET['details'])) {
			echo $data;
		} else {
			echo 'Check location...';
		}

		?>	
	</div>
</div>
<div id='footer'>
	<p>Copyright (c) 2018. Quantox Team</p>
</div>
</body>
</html>