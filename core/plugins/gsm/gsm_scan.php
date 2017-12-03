#!/usr/bin/php

<?php

    /* GSM scanner */

    // Call to show 900MHz GSM BTS
    $gsm900 = shell_exec('kal -s GSM900');

    // Process 900MHz scan results
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $gsm900) as $line)
    {
	if(substr_count($line, 'GSM-900') || empty($line)) continue;

	$bin = explode(' ', $line);

	$gsm_json[] = array(
	    'entity_id' => $bin[1],
	    'name' => 'Canal '.$bin[1],
	    'frequency' => substr(substr($bin[2], 0, -3), 1),
	    'bandwidth' => 0.2,
	    'rssi' => $bin[5],
	    'extra' => []
	);
    }

    // Call to show 1800MHz GSM BTS
    $dcs1800 = shell_exec('kal -s DCS');

    // Process 1800MHz scan results
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $dcs1800) as $line)
    {
	if(substr_count($line, 'DCS-1800') || empty($line)) continue;

	$bin = explode(' ', $line);

	$gsm_json[] = array(
	    'entity_id' => $bin[1],
	    'name' => 'Canal '.$bin[1],
	    'frequency' => substr(substr($bin[2], 0, -3), 1),
	    'bandwidth' => 0.2,
	    'rssi' => $bin[5],
	    'extra' => []
	);
    }

    echo json_encode($pmr_json);
?>