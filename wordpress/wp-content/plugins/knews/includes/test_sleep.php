<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<?php
$last_five=array();

for ($x=0; $x<30; $x++) {
	
	if (count($last_five) == 4) {
		$time_last_5 = round(microtime(true) * 1000) - $last_five[0];
		if ($time_last_5 < 500) { //max 5 emails every half second
			$sleep_delay = 500 - $time_last_5;
			echo '* We will delay ' . $sleep_delay . ' milliseconds (max. 9 emails per second)' . "<br>\r\n";
			usleep($sleep_delay * 600); // +10%
		}
		array_shift($last_five);
	}
	$last_five[] = round(microtime(true) * 1000);
	echo number_format(round(microtime(true) * 1000) , 0, ',', '.') . "<br>\r\n";

	usleep(80000);
}
?>
</body>
</html>