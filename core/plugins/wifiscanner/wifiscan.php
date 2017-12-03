#!/usr/bin/env php
<?php
exec('nmcli -f ssid,bssid,mode,freq,rate,signal,security dev wifi', $output);

// Sample output:
// DIRECT-r8C43x Series  86:25:19:07:B5:88  Infra  2412 MHz  54 Mbit/s  40      WPA2

$data = [];
$foundBssids = [];
foreach($output as $line)
{
    preg_match('/^(.+)\s+(\S{2}:\S{2}:\S{2}:\S{2}:\S{2}:\S{2})\s+(\S+)\s+(\d+) MHz\s+(\d+ Mbit\/s)\s+(\d+)\s+(\S+)$/', $line, $matches);
    if($matches)
    {
        $bssid = trim($matches[2]);
        
        if(isset($foundBssids[$bssid]))
        {
            continue;
        }
        $foundBssids[$bssid] = 1;
        
        $data[] = [
            'name' => trim($matches[1]),
            'entity_id' => $bssid,
            'frequency' => $matches[4],
            'bandwidth' => 20,
            'rssi' => ($matches[6] / 2) - 100,
            'extra' => [
                'WiFiMode' => $matches[3],
                'WiFiRate' => $matches[5],
                'WiFiSecurity' => $matches[7]
            ]
        ];
    }
}

echo json_encode($data);
