#!/usr/bin/php

<?php

    /* PMR 446 MHz power channel analyzer */

    // Initialize stored gains for each channel
    $pmr[1]=-100;
    $pmr[2]=-100;
    $pmr[3]=-100;
    $pmr[4]=-100;
    $pmr[5]=-100;
    $pmr[6]=-100;
    $pmr[7]=-100;
    $pmr[8]=-100;

    // Define frequency for each channel
    $channels = array(
	1 => 446006250,
	2 => 446018750,
	3 => 446031250,
	4 => 446043750,
	5 => 446056250,
	6 => 446068750,
	7 => 446081250,
	8 => 446093750
    );

    // Define tolerancy for each channel (in Hz)
    $tot = 9999;

    // Call to external power meter - https://github.com/AD-Vega/rtl-power-fftw
    $power = shell_exec('rtl_power_fftw -f 446006250:446093750');

    // Process scan results
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $power) as $line)
    {
	if(substr_count($line, '#') || empty($line)) continue;

	$bin = explode(' ', $line);

	// Detected frequency and power
	$freq_bin = (float)$bin[0];
	$power_bin = (float)$bin[1];

	foreach($channels as $channel => $freq)
	{
	    if(abs($freq_bin - $freq) < $tot && $pmr[$channel] < $power_bin)
	    {
		// Update power for each channel if tolerance not exceeded and power is bigger
		$pmr[$channel] = $power_bin;
	    }
	}
    }

    for($i=1; $i<=8; $i++)
    {
	$pmr_json[] = array(
	    'entity_id' => $i,
	    'name' => 'PMR '.$i,
	    'frequency' => $channels[1]/1000,
	    'bandwidth' => 0.0125,
	    'rssi' => $pmr[$i],
	    'extra' => []
	);
    }

    echo json_encode($pmr_json);
?>